<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    const TRANG_CHU = 2;
    const CONG_TY  = 3;
    const PHONG_BAN  = 4;
    const NHAN_VIEN  = 5;
    const PHAN_QUYEN  = 6;

    const HE_THONG = [
        self::TRANG_CHU => 'Trang chủ',
        self::CONG_TY => 'Công ty',
        self::PHONG_BAN => 'Phòng ban/Chức danh',
        self::NHAN_VIEN => 'Nhân viên',
        self::PHAN_QUYEN => 'Phân Quyền',
    ];

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
