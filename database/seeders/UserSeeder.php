<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->upsert([
            ['name' => 'admin', 'email' => 'admin@email.com', 'password' => Hash::make('password')],
        ], ['email', 'phone']);
    }
}
