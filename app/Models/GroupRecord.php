<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupRecord extends Model
{
    use HasFactory;

    protected $table = 'group_record';

    protected $fillable = [
        'record_date',
        'group_id',
        'member_count',
        'name',
    ];
}
