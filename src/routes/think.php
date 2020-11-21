<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\MythinkThink;
use App\Models\MythinkUsersFollowing;
//Routes
$app->group('/api/v1', function(){
    $this->get('/think/list/{userId}', function($request, $response){

        $userId= $request->getAttribute('userId');
        $param = MythinkUsersFollowing::select('mt_user_id_following')->where('mt_user_id', $userId)->get();

        $users= [];
        foreach ($param as $key => $parametro) {
            array_push($users, $parametro['mt_user_id_following']);
        }

        array_push($users, $userId);

        $think = MythinkThink::selectRaw("mythink_thinks.mt_user_id, mythink_thinks.mt_thinks, mythink_thinks.mt_type_think, DATE_FORMAT(mythink_thinks.mt_send_date, '%Y-%m-%d %H:%i:%s') as data, mythink_users.mt_name")
                ->WhereIn('mt_user_id', $users)->where('mythink_thinks.mt_type_think', 'public')->join('mythink_users', 'mythink_thinks.mt_user_id', '=', 'mythink_users.mt_id')
                ->orderBy('data', 'desc')->get();
        return $response->withJson($think);         
    });

    $this->post('/think/newThink', function($request, $response){
        $data= $request->getParsedBody();
        $think= MythinkThink::create($data);
        return $response->withJson($think);
    });

});