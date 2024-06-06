<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => "user 1",
            'email' => "user@gmil.com",
            "email_verified_at" =>now(),
            'password' => 123456789,
            'role' => "user",
        ]);
    }
}
