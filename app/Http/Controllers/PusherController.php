<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PusherController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('index');
    }

    public function broadcast(Request $request): Factory|View|Application
    {
        broadcast(new PusherBroadcast($request->get('message')))->toOthers();

        return view('broadcast', ['message' => $request->get('message')]);
    }

    public function receive(Request $request): Factory|View|Application
    {
        return view('receive', ['message' => $request->get('message')]);
    }
}
