<?php

namespace App\Http\Controllers\Admin\TwoD;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwoDUserService;
use Illuminate\Http\Request;

class ManageTwoDUserController extends Controller
{
    protected $userService;

    public function __construct(TwoDUserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the users with their agents.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = $this->userService->getAllTwoDUsersWithAgents();

        return view('admin.two_d.two_d_user.index', compact('users'));
    }

    public function limitCorindex()
    {
        $users = $this->userService->getAllTwoDUsersWithAgents();

        return view('admin.two_d.two_d_user.limit_cor_index', compact('users'));
    }

    public function updateLimits(Request $request)
    {
        $data = $request->input('users', []);

        try {
            foreach ($data as $userId => $limits) {
                $user = User::findOrFail($userId);
                $user->update($limits);
            }

            return redirect()->route('admin.2dusers.limit_cor')->with('success', 'User limits updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.2dusers.limit_cor')->with('error', 'An error occurred while updating user limits: '.$e->getMessage());
        }
    }

    public function updateCor(Request $request)
    {
        $data = $request->input('users', []);

        try {
            foreach ($data as $userId => $commission) {
                $user = User::findOrFail($userId);
                $user->update($commission);
            }

            return redirect()->route('admin.2dusers.limit_cor')->with('success', 'User commission updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.2dusers.limit_cor')->with('error', 'An error occurred while updating user commission: '.$e->getMessage());
        }
    }
}
