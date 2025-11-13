<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultMonev extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql'; 
    protected $table = 'result_monev';
    protected $guarded = [];

    /**
     * Relasi balik ke SOP
     */
    public function sop()
    {
        return $this->belongsTo(SOP::class, 'sop_id', 'id');
    }

    /**
     * Relasi ke tabel hasil instrumen F01
     * Satu monev punya banyak detail jawaban instrumen.
     */
    public function result_f01()
    {
        return $this->hasMany(ResultMonevF01::class, 'result_monev_id', 'id');
    }
}
