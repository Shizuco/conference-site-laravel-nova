<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Report;

class Comment extends Model
{
    public $table = "comment";
    use HasFactory;

    protected $fillable = [
        'user_id',
        'report_id',
        'conference_id',
        'comment',
    ];

    public function reports()
	{
		return $this->belongsToOne(Report::class);
	}

    public function users()
	{
		return $this->belongsToOne(User::class);
	}

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
