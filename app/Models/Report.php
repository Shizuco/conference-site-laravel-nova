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
    public $table = "conferece_user_reports";
    use HasFactory;

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

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
        'zoom_meeting_id'
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

    public function scopeFilters($query, $request, $id)
    {
        $query->where('conference_id', $id);
        if ($request->duration !== null) {
            $query->where('duration', '=', $request->duration * 60);
        }
        if($request->categories !== null){
            $categories = explode(",", $request->categories);
            $query->with('category')->whereIn('category_id', $categories);
        }
        if($request->start_time !== null){
            $query->where('start_time', '>=', $request->start_time.' 00:00:00');
        }
        if($request->end_time !== null){
            $query->where('start_time', '<=', $request->end_time.' 00:00:00.000');
        }
        return $query->paginate(5);
    }
}
