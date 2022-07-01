<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Conference extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'country',
        'address_lat',
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
}
