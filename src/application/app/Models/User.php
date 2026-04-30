<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    protected $table = 'users'; // テーブル名が users なら省略可
    protected $primaryKey = 'user_id'; // 主キーが id 以外なら指定が必要
    public $timestamps = false; // created_at 等の自動更新をしないなら false
    public $incrementing = false;

    protected $fillable = [
        'user_name', 'password',
    ];

    protected $hidden = [
        'password',
    ];
}
