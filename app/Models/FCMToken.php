<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FCMToken extends Model
{
    use HasFactory;
    protected $table='fcmtoken';
    public $timestamps=false;
    protected $fillable=[
        'token',
        'user_id'
    ];
}
