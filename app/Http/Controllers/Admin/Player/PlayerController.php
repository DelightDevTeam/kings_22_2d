<?php

namespace App\Http\Controllers\Admin\Player;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlayerRequest;
use App\Models\Admin\TransferLog;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class PlayerController extends Controller
{
    private const PLAYER_ROLE = 3;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(
            Gate::denies('player_index'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden |You cannot  Access this page because you do not have permission'
        );
        //kzt
        $users = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('role_id', self::PLAYER_ROLE);
            })
            ->where('agent_id', auth()->id())
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.player.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(
            Gate::denies('player_create'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden |You cannot  Access this page because you do not have permission'
        );
        $player_name = $this->generateRandomString();

        return view('admin.player.create', compact('player_name'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlayerRequest $request)
    {
        abort_if(
            Gate::denies('player_store'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden |You cannot Access this page because you do not have permission'
        );

        try {
            $agent = Auth::user();
            $inputs = $request->validated();

            if (isset($inputs['main_balance']) && $inputs['main_balance'] > $agent->main_balance) {
                throw ValidationException::withMessages([
                    'main_balance' => 'Insufficient balance for transfer.',
                ]);
            }

            $userPrepare = array_merge(
                $inputs,
                [
                    'password' => Hash::make($inputs['password']),
                    'agent_id' => Auth()->user()->id,
                    'type' => UserType::Player,
                ]
            );
            Log::info('User prepared: '.json_encode($userPrepare));

            $player = User::create($userPrepare);
            $player->roles()->sync(self::PLAYER_ROLE);

            $player->main_balance += $inputs['main_balance'];
            $player->save();

            $agent->main_balance -= $inputs['main_balance'];
            $agent->save();

            TransferLog::create([
                'from_user_id' => $agent->id,
                'to_user_id' => $player->id,
                'amount' => $request['main_balance'],
                'type' => 'deposit',
            ]);

            return redirect()->back()
                ->with('success', 'Player created successfully')
                ->with('url', env('APP_URL'))
                ->with('password', $request->password)
                ->with('phone', $player->phone);

        } catch (Exception $e) {
            Log::error('Error creating user: '.$e->getMessage());

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort_if(
            Gate::denies('player_show'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden |You cannot  Access this page because you do not have permission'
        );

        $user_detail = User::findOrFail($id);

        return view('admin.player.show', compact('user_detail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $player)
    {
        return response()->view('admin.player.edit', compact('player'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $player)
    {
        $request->validate([
            'phone' => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'unique:users,phone,'.$player->id],
        ]);
        $player->update($request->all());

        return redirect()->route('admin.player.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $player)
    {
        User::destroy($player->id);

        return redirect()->route('admin.player.index')->with('success', 'User deleted successfully');
    }

    public function massDestroy(Request $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    }

    public function makeBanPlayer(Request $request, $id)
    {
        abort_if(
            ! $this->ifChildOfParent(request()->user()->id, $id),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden |You cannot  Access this page because you do not have permission'
        );
        $user = User::find($id);
        $user->update([
            'status' => $user->status == 1 ? 0 : 1,
            'note' => $request->note ?? null,
        ]);

        return redirect()->route('admin.player.index')->with(
            'success',
            'User '.($user->status == 1 ? 'activate' : 'inactive').' successfully'
        );
    }

    public function getCashIn(User $player)
    {
        abort_if(
            Gate::denies('make_transfer'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden |You cannot  Access this page because you do not have permission'
        );

        return view('admin.player.cash_in', compact('player'));
    }

    public function makeCashIn(Request $request, User $player)
    {
        abort_if(
            Gate::denies('make_transfer') || ! $this->ifChildOfParent(request()->user()->id, $player->id),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden |You cannot  Access this page because you do not have permission'
        );

        try {
            $inputs = $request->validate([
                'main_balance' => 'required',
            ]);
            $inputs['refrence_id'] = $this->getRefrenceId();

            $agent = Auth::user();
            $cashIn = $inputs['main_balance'];

            if ($cashIn > $agent->main_balance) {

                return redirect()->back()->with('error', 'You do not have enough balance to transfer!');
            }

            $player->main_balance += $cashIn;
            $player->save();

            $agent->main_balance -= $cashIn;
            $agent->save();

            TransferLog::create([
                'from_user_id' => $agent->id,
                'to_user_id' => $player->id,
                'amount' => $inputs['main_balance'],
                'type' => 'deposit',
            ]);

            return redirect()->back()
                ->with('success', 'CashIn submitted successfully!');
        } catch (Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getCashOut(User $player)
    {
        abort_if(
            Gate::denies('make_transfer') || ! $this->ifChildOfParent(request()->user()->id, $player->id),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden |You cannot  Access this page because you do not have permission'
        );

        return view('admin.player.cash_out', compact('player'));
    }

    public function makeCashOut(Request $request, User $player)
    {
        abort_if(
            Gate::denies('make_transfer') || ! $this->ifChildOfParent(request()->user()->id, $player->id),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden |You cannot  Access this page because you do not have permission'
        );

        try {
            $inputs = $request->validate([
                'main_balance' => 'required',
            ]);
            $inputs['refrence_id'] = $this->getRefrenceId();

            $agent = Auth::user();
            $cashOut = $inputs['main_balance'];

            if ($cashOut > $player->main_balance) {

                return redirect()->back()->with('error', 'You do not have enough balance to transfer!');
            }

            $player->main_balance -= $cashOut;
            $player->save();

            $agent->main_balance += $cashOut;
            $agent->save();

            TransferLog::create([
                'from_user_id' => $player->id,
                'to_user_id' => $agent->id,
                'amount' => $inputs['main_balance'],
                'type' => 'withdraw',
            ]);

            return redirect()->back()
                ->with('success', 'CashOut submitted successfully!');
        } catch (Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getChangePassword($id)
    {
        $player = User::find($id);

        return view('admin.player.change_password', compact('player'));
    }

    public function makeChangePassword($id, Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $player = User::find($id);
        $player->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()
            ->with('success', 'Player Change Password successfully')
            ->with('password', $request->password)
            ->with('phone', $player->phone);
    }

    public function getbanPlayer($id)
    {
        $player = User::find($id);

        return view('admin.player.ban', compact('player'));
    }

    private function generateRandomString()
    {
        $randomNumber = mt_rand(10000000, 99999999);

        return 'SB'.$randomNumber;
    }

    private function getRefrenceId($prefix = 'REF')
    {
        return uniqid($prefix);
    }
}
