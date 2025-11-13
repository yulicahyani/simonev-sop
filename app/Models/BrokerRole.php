<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrokerRole extends Model
{
    protected $connection = 'mysql_sso'; 
    protected $table = 'broker_roles';
    protected $guard = 'id';
}
