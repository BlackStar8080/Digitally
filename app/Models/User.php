<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    ];

     public function tallysheets()
    {
        return $this->hasMany(Tallysheet::class);
    }

    /**
     * Get all volleyball tallysheets submitted by this user
     */
    public function volleyballTallysheets()
    {
        return $this->hasMany(VolleyballTallysheet::class);
    }

    /**
     * Get all tallysheets (both basketball and volleyball) submitted by this user
     */
    public function allTallysheets()
    {
        $basketball = $this->tallysheets()->with('game.bracket.tournament')->get();
        $volleyball = $this->volleyballTallysheets()->with('game.bracket.tournament')->get();
        
        return $basketball->merge($volleyball);
    }
}