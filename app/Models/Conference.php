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

    protected $fillable = [
        'title',
        'country',
        'address_lat',
        'category_id',
        'address_lon',
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
}
