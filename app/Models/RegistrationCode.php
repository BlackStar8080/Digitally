<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RegistrationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'is_active',
        'max_uses',
        'used_count',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Check if the code is valid
     */
    public function isValid(): bool
    {
        // Check if active
        if (!$this->is_active) {
            return false;
        }

        // Check if expired
        if ($this->expires_at && Carbon::now()->greaterThan($this->expires_at)) {
            return false;
        }

        // Check if max uses reached
        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * Increment the usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    /**
     * Scope to get only valid codes
     */
    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', Carbon::now());
            })
            ->where(function($q) {
                $q->whereNull('max_uses')
                  ->orWhereRaw('used_count < max_uses');
            });
    }
}