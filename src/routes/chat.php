<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\MythinkUsersFollowing;
use App\Models\MythinkUsersInfo;
use App\Models\MythinkChat;

$app->group('/api/v1', function(){

    $this->get('/chat/friends/list/{id}', function($request, $response){
        $id= $request->getAttribute('id');

        $param = MythinkUsersFollowing::select('mt_user_id_following')->where('mt_user_id', $id)->get();

        $users= [];
        foreach ($param as $key => $parametro) {
            array_push($users, $parametro['mt_user_id_following']);
        }

        $listFriends = MythinkUsersInfo::select('mt_user_id', 'mt_status', 'mt_last_view', 'mt_name')->whereIn("mt_user_id", $users)
                      ->join('mythink_users', 'mythink_users_infos.mt_user_id', '=', 'mythink_users.mt_id')->get();

        return $response->withJson($listFriends);

    });

    $this->post('/chat/newMessage', function($request, $response){
        $data= $request->getParsedBody();
        $newMessage= MythinkChat::create($data);
        $a= $newMessage['id'];
        $newMessage= MythinkChat::select('mythink_users.mt_name', 'mythink_chats.mt_id', 'mythink_chats.mt_user_id', 'mythink_chats.mt_user_id_from', 'mythink_chats.mt_message', 'mythink_chats.mt_status', 'mythink_chats.mt_send_date')
        ->where([
            ['mythink_chats.mt_user_id_from', $data['mt_user_id_from'] ],
            ['mythink_chats.mt_id', $a],
            ])
        ->join('mythink_users', 'mythink_chats.mt_user_id_from', '=', 'mythink_users.mt_id')
        ->get();
        return $response->withJson($newMessage);
    });

    $this->get('/chat/recoverConversation/{friendId}/{userId}',  function($request, $response){
        $friendId= $request->getAttribute('friendId');
        $userId= $request->getAttribute('userId');

        $received= MythinkChat::select('mt_user_id', 'mt_user_id_from', 'mt_message', 'mt_status', 'mt_send_date')
                                ->where([
                                    ['mt_user_id_from' , $userId], ['mt_user_id', $friendId],
                                    ]);

        $messages = MythinkChat::select('mt_user_id', 'mt_user_id_from', 'mt_message', 'mt_status', 'mt_send_date')
                                ->where([
                                    ['mt_user_id_from' , $friendId], ['mt_user_id', $userId],
                                    ])
                                    ->union($received)
                                    ->orderBy('mt_send_date', 'asc')
                                    ->get();
        return $response->withJson($messages);
    });

});