<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'topic',
        'type',
        'start_time',
        'duration',
        'agenda',
        'timezone'
    ];
}
