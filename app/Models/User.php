<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\Conference;
use App\Models\Report;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'birthday',
        'country',
        'surname',
        'left_joins'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'date'
    ];

    public function conferences()
    {
        return $this->belongsToMany(Conference::class, 'conference_user')->withTimestamps();
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'conference_user_reports');
    }

    public function favorite_reports()
    {
        return $this->belongsToMany(Report::class, 'user_report_favorites');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function subsciptions()
    {
        return $this->hasOne(Subscription::class, 'subscriptions');
    }
}
