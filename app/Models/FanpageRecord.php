<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FanpageRecord extends Model
{
    use HasFactory;

    protected $table = 'fanpage_record';

    protected $fillable = [
        'record_date',
        'page_id',
        'likes_count',
        'name',
    ];
}
