<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shortlink extends Model
{
    use HasFactory;

    protected $table = 'shortlink';

    protected $fillable = [
        'organization_guid',
        'guid',
        'link_id',
        'title',
        'access_token',
        'link_date',
        'created_by',
        'short_url',
        'long_url',
        'click_count',
    ];
}
