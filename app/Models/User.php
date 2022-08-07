<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    protected $table = "sys_user";
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
