<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuTamuKonsultasi extends Model
{
    use HasFactory;

    protected $connection = 'mysql'; 
    protected $table = 'buku_tamu_konsultasi';
    protected $guarded = [];
}
