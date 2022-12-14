<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fanpage extends Model
{
    use HasFactory;

    protected $table = 'fanpage';

    protected $fillable = [
        'page_id',
        'page_name',
        'picture',
        'page_theme',
        'page_url',
        'likes_count',
        'access_token',
    ];
}
