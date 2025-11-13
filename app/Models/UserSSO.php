<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSSO extends Model
{
    protected $connection = 'mysql_sso'; 
    protected $table = 'users';
    protected $guard = 'id';
}
