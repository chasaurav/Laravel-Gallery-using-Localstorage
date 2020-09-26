<?php

namespace App\Http\Controllers;

use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Select all users except loggedin user
        $users = User::where('id', '!=', FacadesAuth::id())->get();

        // count how many unread messages are unread for the selected user
        $users = DB::select("SELECT users.id, users.name, users.avatar, users.email, count(is_read) AS unread FROM users LEFT JOIN messages ON users.id = messages.from AND is_read = 0 AND messages.to = ".FacadesAuth::id()." WHERE users.id <> ".FacadesAuth::id()." GROUP BY users.id, users.name, users.avatar, users.email");

        return view('home', ['users' => $users]);
    }

    public function getMessage($id)
    {
        $my_id = FacadesAuth::id();

        // when click to view msg, update the selected users msgs as viewed
        Message::where(['from' => $id, 'to' => $my_id])->update(['is_read' => 1]);

        // getting all messages for the selected user
        $messages = Message::where(function ($query) use ($id, $my_id){
            $query->where('from', $my_id)->where('to', $id);
        })->orWhere(function ($query) use ($id, $my_id){
            $query->where('from', $id)->where('to', $my_id);
        })->get();
        return view('messages.index', ['messages' => $messages]);
     }

    public function sendMessage(Request $request)
    {
        $from = FacadesAuth::id();
        $to = $request->receiver_id;
        $msg = $request->message;

        $data = new Message();
        $data->from = $from;
        $data->to = $to;
        $data->message = $msg;
        $data->is_read = 0;
        $data->save();

        $options = ['cluster' => 'ap2', 'useTLS' => true];
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $data = ['from' => $from, 'to' => $to];
        $pusher->trigger('my-channel', 'my-event', $data);
    }

}
