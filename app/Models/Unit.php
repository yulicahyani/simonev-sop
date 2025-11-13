<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $connection = 'mysql_sso'; 
    protected $table = 'units';
    protected $guard = 'id';
}
