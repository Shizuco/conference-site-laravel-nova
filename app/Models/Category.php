<?php

namespace App\Models;

use App\Models\Conference;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NestedSet;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use HasFactory, NodeTrait;

    protected $fillable = [
        'name'
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function conferences()
    {
        return $this->hasMany(Conference::class);
    }
}
