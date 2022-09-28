<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ticket extends Model
{
    use HasFactory;

    //workflow
    const NHAP = 0;
    const GUI_MKT = 1;
    const DANG_DUYET = 2;
    const DANG_THAO_LUAN = 3;
    const DANG_CHAY  = 4;
    const TAM_DUNG  = 5;
    const KET_THUC  = 6;

    const WORKFLOW = [
        self::NHAP => 'Nháp',
        self::GUI_MKT => 'Đã gửi MKT',
        self::DANG_DUYET => 'Đang duyệt',
        self::DANG_THAO_LUAN => 'Đang thảo luận',
        self::DANG_CHAY => 'Đang chạy',
        self::TAM_DUNG => 'Tạm dừng',
        self::KET_THUC => 'Đã kết thúc',
    ];

    //gender
    const OTHER = 0;
    const MALE = 1;
    const FEMALE = 2;

    const GENDERS = [
        self::MALE => 'Nam',
        self::FEMALE => 'Nữ',
        self::OTHER => 'Tất cả',
    ];

    //kind
    const NEW = 0;
    const OLD = 1;
    const KINDS = [
        self::NEW => 'Chạy ND mới',
        self::OLD => 'ND cũ chạy lại',
    ];
    protected $dates = ['start_date', 'end_date'];

    public function creator() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function marketer() {
        return $this->belongsTo(User::class, 'mkt_user_id');
    }

    public function video() {
        return $this->belongsTo(Video::class, 'video_id');
    }

    public function getTotalUnreadCommentAttribute() {
        return $this->hasMany(TicketComment::class)
            ->whereTicketId($this->id)
            ->where('user_id', '<>', Auth::user()->id)
            ->where('is_read', 0)->count();
    }
}
