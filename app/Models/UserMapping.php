<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMapping extends Model
{
    use HasFactory;

    protected $connection = 'mysql'; 
    protected $table = 'user_mapping';
    protected $guarded = [];
}
