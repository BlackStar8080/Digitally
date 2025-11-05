<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegistrationCode;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RegistrationCodeSeeder extends Seeder
{
    public function run()
    {
        // Create some default registration codes
        RegistrationCode::create([
            'code' => 'ADMIN2025',
            'description' => 'Admin registration code',
            'is_active' => true,
            'max_uses' => null, // Unlimited uses
            'used_count' => 0,
            'expires_at' => null, // Never expires
        ]);

        RegistrationCode::create([
            'code' => 'ORGANIZER2025',
            'description' => 'Tournament organizer code',
            'is_active' => true,
            'max_uses' => 50, // Limited to 50 uses
            'used_count' => 0,
            'expires_at' => Carbon::now()->addYear(), // Expires in 1 year
        ]);

        RegistrationCode::create([
            'code' => 'TEMP2025',
            'description' => 'Temporary registration code',
            'is_active' => true,
            'max_uses' => 10,
            'used_count' => 0,
            'expires_at' => Carbon::now()->addMonths(3), // Expires in 3 months
        ]);

        // Create a disabled code (for testing)
        RegistrationCode::create([
            'code' => 'DISABLED2025',
            'description' => 'Disabled code for testing',
            'is_active' => false,
            'max_uses' => null,
            'used_count' => 0,
            'expires_at' => null,
        ]);
    }
}