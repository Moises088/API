<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MythinkChat extends Model{

    protected $fillable = [
        'mt_user_id', 'mt_user_id_from', 'mt_message', 'mt_status'
    ];
}