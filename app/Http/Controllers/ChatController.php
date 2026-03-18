<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChatController extends Controller {
    public function index()
    {
        return view('chat.index');
    }

    public function initChat(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        $allowed_privs = [];
        
        if($user->priv == 1){
            $allowed_privs = [1,2];
        } else if($user->priv == 2){
            $allowed_privs = [1,2,3,4,5];
        } else if(in_array($user->priv, [3,4,5])){
            $allowed_privs = [2,3,4,5];
        }

        $senders = DB::table("users")->select("users.name", "users.id as user_id", "chat_messages.message", "chat_messages.updated_at", "chat_messages.read_at")
            ->leftJoin("chat_messages", function($join) use ($user) {
                $join->on("users.id", "=", "chat_messages.sender_id")
                ->where("chat_messages.receiver_id", "=", $user->id);
            });

        if($user->priv != 1 && $user->priv != 2) {
            $senders = $senders->where("parent_id", $user->parent_id);
        } else if($user->priv == 2){
            $senders = $senders->where(function($query) use ($user){
                $query->where("users.parent_id", $user->parent_id)->orWhere("users.priv", 1);
            });
        }

        $senders = $senders->where("users.id", "!=", $user->id)->whereIn("users.priv", $allowed_privs)->orderBy("chat_messages.updated_at", "DESC")->get();

        $recivers = DB::table("users")->select("users.name", "users.id as user_id", "chat_messages.message", "chat_messages.updated_at", "chat_messages.read_at")
            ->leftJoin("chat_messages", function($join) use ($user) {
            $join->on("users.id", "=", "chat_messages.receiver_id")->where("chat_messages.sender_id", "=", $user->id);
        });

        if($user->priv != 1 && $user->priv != 2) {
            $recivers = $recivers->where("parent_id", $user->parent_id);
        } else if($user->priv == 2){
            $recivers = $recivers->where(function($query) use ($user){
                $query->where("users.parent_id", $user->parent_id)->orWhere("users.priv", 1);
            });
        }

        $recivers = $recivers->where("users.id", "!=", $user->id)->whereIn("users.priv", $allowed_privs)->orderBy("updated_at", "DESC")->get();

        $chat_log = [];
        $chat_log = $this->getChatLogs($chat_log, $senders, 's');
        $chat_log = $this->getChatLogs($chat_log, $recivers, 'r');

        $chat_log = array_values($chat_log);

        $data["chat_log"] = $chat_log;
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    private function getChatLogs($chat_log, $senders, $c){

        foreach ($senders as $chat) {
            if(isset($chat_log[$chat->user_id])){

                if($chat->message){
                    if(strtotime($chat_log[$chat->user_id]->updated_at) < strtotime($chat->updated_at)){
                        $chat_log[$chat->user_id] = $chat;
                    }
                }
            } else {
                $chat_log[$chat->user_id] = $chat;
            }
        }

        return $chat_log;
    }

    public function getChat(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        
        $data["success"] = true;

        return response()->json($data,200,[]);
    }    

    public function thread(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function send(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }
}
