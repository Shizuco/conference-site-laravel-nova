<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Conference;
use App\Models\User;

class Report extends Model
{
    public $table = "conferece_user_reports";
    use HasFactory;

    protected $fillable = [
        'conference_id',
        'user_id',
        'thema',
        'start_time',
        'end_time',
        'description',
        'presentation',
    ];

    public function conferences()
	{
		return $this->belongsToOne(Conference::class);
	}

    public function users()
	{
		return $this->belongsToOne(User::class);
	}

    public function reports(){
        return $this->hasMany(Report::class);
    }
}
