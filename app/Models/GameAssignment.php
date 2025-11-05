<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'user_id',
        'device_token',
        'role',
        'assigned_by',
        'expires_at',
        'active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'active' => 'boolean',
    ];

    /**
     * Relationship: Assignment belongs to a Game
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Relationship: Assignment belongs to a User (the person with this role)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Assignment was created by a User
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Check if this assignment is still valid (not expired)
     */
    public function isValid()
    {
        if (!$this->active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Mark assignment as inactive
     */
    public function deactivate()
    {
        $this->update(['active' => false]);
        return $this;
    }

    /**
     * Scope: Get only active assignments
     */
    public function scopeActive($query)
    {
        return $query->where('active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope: Get assignments for a specific role
     */
    public function scopeForRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope: Get scorer assignments
     */
    public function scopeScorers($query)
    {
        return $query->where('role', 'scorer');
    }

    /**
     * Scope: Get stat-keeper assignments
     */
    public function scopeStatKeepers($query)
    {
        return $query->where('role', 'stat_keeper');
    }
}