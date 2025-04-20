<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Chat2Model extends Model
{
    use HasFactory;

    protected $table = 'chats';

    static public function getChat($sender_id, $receiver_id)
    {
        $query = self::select('chats.*')
                 ->where(function($q) use ($receiver_id, $sender_id){
                    $q->where(function($q) use ($receiver_id, $sender_id){
                        $q->where('receiver_id', $sender_id)
                          ->where('sender_id', $receiver_id)
                          ->where('status', '>', '-1');
                    })->orWhere(function($q) use ($receiver_id, $sender_id){
                            $q->where('receiver_id', $receiver_id)
                              ->where('sender_id', $sender_id);
                        });
                 })
                 ->where('message', '!=', '')
                 ->orderBy('id', 'asc')
                 ->get();

        return $query;
    }

    public function getSender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function getReceiver()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function getConnectUser()
    {
        return $this->belongsTo(User::class, 'connect_user_id');
    }

    static public function getChatUser($user_id)
    {
        $getuserchat = self::select("chats.*", DB::raw('(CASE WHEN chats.sender_id = "'.$user_id.'"
                THEN chats.receiver_id ELSE chats.sender_id END) AS connect_user_id'))
                ->join('users as sender', 'sender.id', '=', 'chats.sender_id')
                ->join('users as receiver', 'receiver.id', '=', 'chats.receiver_id');

        $getuserchat = $getuserchat->whereIn('chats.id', function($query) use($user_id){
            $query->selectRaw('max(chats.id)')->from('chats')
            ->where('chats.status','<',2)
            ->where(function($query) use($user_id){
                $query->where('chats.sender_id', '=', $user_id)
                ->orWhere(function($query) use($user_id){
                    $query->where('chats.receiver_id', '=', $user_id)
                          ->where('chats.status','>','-1');
                });
            })
            ->groupBy(DB::raw('CASE WHEN chats.sender_id = "'.$user_id.'"
                THEN chats.receiver_id ELSE chats.sender_id END'));
        })

        ->orderBy('chats.id', 'desc')
        ->get();

        $result = array();

        foreach($getuserchat as $value)
        {
            $data = array();
            $data['id'] = $value->id;
            $data['message'] = $value->message;
            $data['created_date'] = $value->created_date;
            $data['user_id'] = $value->connect_user_id;
            $data['name'] = $value->getConnectUser->name.''.$value->getConnectUser->last_name;
            $data['profile_pic'] = $value->getConnectUser->getImage();
            $data['message_count'] = $value->CountMessage($value->connect_user_id, $user_id);
            $result[] = $data;
        }
        return $result;
    }

    static public function CountMessage($connect_user_id, $user_id)
    {
        return self::where('sender_id', '=', $connect_user_id)
                    ->where('receiver_id', '=', $user_id)
                    ->where('status', '=', 0)->count();
    }

    static public function updateCount($sender_id, $receiver_id)
    {
        return self::where('sender_id', '=', $receiver_id)
                    ->where('receiver_id', '=', $sender_id)
                    ->where('status', '=', 0)->update(['status'=>'1']);
    }

    
}
