<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            "name" => "Aung Hmuee",
            "email" => "aht@gmail.com",
            "password" => Hash::make("asdffdsa"),
        ]);

        User::factory(4)->create();

        User::factory()->create([
            "name" => "Aye Min Tun",
            "email" => "amt@gmail.com",
            "password" => Hash::make("asdffdsa"),
            "role" => "admin"
        ]);
    }
}
