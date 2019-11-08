<?php

namespace App\Models;

class User extends Model
{
    protected $hidden = ['password','deleted_at'];
}
