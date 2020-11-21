<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Firebase\JWT\JWT;
use App\Models\MythinkUser;
use App\Models\MythinkUsersInfo;

$app->group('/api/v1', function(){

    $this->post('/login', function($request, $response){
        $data = $request->getParsedBody();

        $mt_email= $data['mt_email'] ?? null;
        $mt_password= $data['mt_password'] ?? null;

        $user = MythinkUser::where('mt_email', $mt_email)->first();

        if(!is_null($user) && md5(md5($mt_password)) === $user->mt_password){

            $secretKey= $this->get('settings')['secretKey'];
            $token= JWT::encode($user, $secretKey);

            $user = [
                'mt_id' => $user->mt_id,
                'mt_name'=> $user->mt_name,
                'mt_email'=>$user->mt_email
            ];

            return $response->withJson([
                'token' => $token,
                'user' => $user
            ]);
        }
        
        return $response->withJson([
            'status' => 'not found'
        ]);
    });

    $this->put('/status/{userId}', function($request, $response){

        $userId = $request->getAttribute('userId');
        $update = MythinkUsersInfo::where('mt_user_id', $userId)->update(['mt_status' => 'online']);

        return $response->withJson($update);
    });
});