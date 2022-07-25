<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Report;

class Comment extends Model
{
    public $table = "comment_user_reports";
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
		return $this->belongsTo(User::class, 'user_id');
	}

    public function comments(){
        return $this->belongsTo(Comment::class);
    }
}
