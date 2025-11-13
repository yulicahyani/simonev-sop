<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    use HasFactory;

    protected $connection = 'mysql'; 

    protected $table = 'permissions';

    protected $hidden = ['created_at', 'updated_at'];

    protected $guarded = [];

    public $fillable = [
        'is_parent',
        'parent_id',
        'name',
        'display_name',
    ];

    public function permission_roles()
	{
		return $this->hasMany(PermissionRole::class);
	}

	public function parent()
	{
		return $this->hasOne('App\Models\Permission', 'id', 'parent_id');
	}
}
