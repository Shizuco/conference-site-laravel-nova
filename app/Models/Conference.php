<?php

namespace App\Models;

use App\Models\User;
use App\Models\Category;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'date:Y-m-d'
    ];

    protected $fillable = [
        'title',
        'country',
        'latitude',
        'category_id',
        'longitude',
        'date',
        'time',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'conference_user');
    }

    public function conferences()
    {
        return $this->hasMany(Conference::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilters($query, $request)
    {
        if ($request->numberOfReports !== null) {
            $query->withCount('reports')->having('reports_count', '=', $request->numberOfReports);
        }
        if ($request->categories !== null) {
            $categories = explode(",", $request->categories);
            $query->with('category')->whereIn('category_id', $categories);
        }
        if ($request->dateFrom !== null) {
            $query->where('date', '>=', $request->dateFrom);
        }
        if ($request->dateTo !== null) {
            $query->where('date', '<=', $request->dateTo);
        }
        return $query->paginate(5);
    }

}
