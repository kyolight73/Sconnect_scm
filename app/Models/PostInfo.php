<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostInfo extends Model
{
    use HasFactory;

    protected $table = 'post_info';

    protected $fillable = [
        'page_id',
        'post_id',
        'message',
        'post_create',
        'link',
        'like_count',
        'angry_count',
        'sad_count',
        'love_count',
        'wow_count',
        'haha_count',
        'shares_count',
    ];
}
