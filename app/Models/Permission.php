<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $guarded = [];


    const MOD_HE_THONG = 1;
    const TRANG_CHU = 2;
    const CONG_TY  = 3;
    const PHONG_BAN  = 4;
    const NHAN_VIEN  = 5;
    const PHAN_QUYEN  = 6;


    const MODULE_HE_THONG = [
        self::MOD_HE_THONG => 'Hệ thống',
    ];
    const HE_THONG = [
        self::TRANG_CHU => 'Trang chủ',
        self::CONG_TY => 'Công ty',
        self::PHONG_BAN => 'Phòng ban/Chức danh',
        self::NHAN_VIEN => 'Nhân viên',
        self::PHAN_QUYEN => 'Phân Quyền',
    ];
    const MEDIA = [
        'san_pham_goc' => 'Sản phẩm gốc',
        'nen_tang' => 'Nền tảng chia sẻ video',
        'chu_de' => 'Chủ đề',
        'loai_kenh' => 'Loại kênh',
        'kenh_video' => 'Kênh video',
        'video' => 'Video',
    ];
    const MARKETING = [
        'shortlink' => 'Shortlink',
        'quang_cao' => 'Quảng cáo',
        'binh_luan' => 'Bình Luận',
    ];

    public function permissionsChildrent()
    {
        return $this->hasMany(Permission::class, 'parent_id');
    }

}
