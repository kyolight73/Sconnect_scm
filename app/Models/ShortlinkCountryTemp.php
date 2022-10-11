<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortlinkCountryTemp extends Model
{
    use HasFactory;

    protected $table = 'shortlink_country_temp';

    protected $fillable = [
        'group_id',
        'country_code',
        'record_reference',
        'click_count',
    ];
}
