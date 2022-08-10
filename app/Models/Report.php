<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\Conference;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $table = "conference_user_reports";
    use HasFactory;

    protected $fillable = [
        'category_id',
        'conference_id',
        'user_id',
        'duration',
        'thema',
        'start_time',
        'end_time',
        'description',
        'presentation',
    ];

    public function conferences()
    {
        return $this->belongsTo(Conference::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_report');
    }
}
