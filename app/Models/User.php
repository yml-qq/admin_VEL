<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //
    public $table = 'users';
    public $primaryKey = 'Id';
    public $fillable = ['UserName','PassWord','Sex','Phone','Email'];
}
