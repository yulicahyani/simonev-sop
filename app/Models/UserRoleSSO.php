<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRoleSSO extends Model
{
     protected $connection = 'mysql_sso'; 
    protected $table = 'user_roles';
    protected $guard = 'id';
}
