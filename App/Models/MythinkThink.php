<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MythinkThink extends Model{

    protected $fillable = [
        'mt_user_id', 'mt_thinks', 'mt_type_think'
    ];
}