<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Chat2Model;

class Chat2Controller extends Controller
{
    public function chat2(Request $request)
    {
        $data['header_title'] = "My Chat";
        $sender_id = Auth::user()->id;
        if(!empty($request->receiver_id))
        {
            $receiver_id = base64_decode($request->receiver_id);
            if($receiver_id == $sender_id)
            {
                return redirect()->back()->with('error', 'You can not message your self');
                exit();
            }

            Chat2Model::updateCount($sender_id, $receiver_id);
            $data['getReceiver'] = User::getSingle($receiver_id);
            $data['getChat'] = Chat2Model::getChat($receiver_id, $sender_id);
        }

        $data['getChatUser'] = Chat2Model::getChatUser($sender_id);
        
        return view('chat2.list',$data);
    }

    public function submit_message(Request $request)
    {
        $chat = new Chat2Model();
        $chat->sender_id = Auth::user()->id;
        $chat->receiver_id =$request->receiver_id;
        $chat->message =$request->message;
        $chat->created_date = time();
        $chat->save();

        $getChat = Chat2Model::where('id', '=', $chat->id)->get();
    
        return response()->json([
            "status" => true,
            "success" => view('chat._single', [
                "getChat" => $getChat
            ])->render(),
        ],200);
    }
}
