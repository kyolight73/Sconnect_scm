<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortlinkRecord extends Model
{
    use HasFactory;

    protected $table = 'shortlink_record';

    protected $fillable = [
        'link_id',
        'country_code',
        'record_reference',
        'guid',
        'title',
        'click_count',
    ];
}
