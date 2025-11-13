<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SOP extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql'; 
    protected $table = 'sop';
    protected $guarded = [];

    /**
     * Relasi ke hasil monev utama (result_monev)
     * Satu SOP bisa punya banyak hasil monev dari berbagai periode.
     */
    public function monev()
    {
        return $this->hasMany(ResultMonev::class, 'sop_id', 'id');
    }

}
