<?php

namespace App\Http\Controllers\PC;

use App\Events\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NormalTalkController extends Controller
{
    public function index()
    {
        return view('pc.chat');
    }

    public function add(Request $request)
    {
        broadcast(new PushNotification($request->input('name'),$request->input('msg')));
        return response()->json(['result' => 'ok'], 200);
    }
}
