<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KonsultasiOnline extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql'; 
    protected $table = 'konsultasi_online';
    protected $guarded = [];

    public function konsultasi_chatting()
    {
        return $this->hasMany(KonsultasiOnlineChatting::class, 'konsultasi_online_id', 'id');
    }
}
