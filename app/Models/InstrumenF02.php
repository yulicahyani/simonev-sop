<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstrumenF02 extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql'; 
    protected $table = 'instrumen_f02';
    protected $guarded = [];
}
