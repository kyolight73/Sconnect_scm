<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortlinkThemeRecord extends Model
{
    use HasFactory;

    protected $table = 'shortlink_theme_record';

    protected $fillable = [
        'country_code',
        'record_date',
        'guid',
        'title',
        'click_count',
    ];
}
