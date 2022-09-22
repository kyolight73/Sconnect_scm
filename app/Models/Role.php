<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded=[];
    public function permissions(){
        return $this->belongsToMany(Permission::class,'permission_roles','role_id','permission_id');
    }
    public function getRole($id)
    {
        $query = self::select('name')->where('id',$id)->first();
        return $query;
    }
}
