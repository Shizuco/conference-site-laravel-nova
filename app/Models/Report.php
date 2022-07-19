<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Conference;
use App\Models\User;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'thema',
        'start_time',
        'end_time',
        'description',
        'presentation',
    ];

    public function conferences()
	{
		return $this->hasOne(Conference::class);
	}

    public function users()
	{
		return $this->hasOne(User::class);
	}
}
