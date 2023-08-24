<?php

namespace Database\Seeders;

use App\Models\Photo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Photo::factory()->create([
            "url" => config('info.default_contact_photo'),
            "name" => config('info.default_photo_name'),
            "ext" => config('info.ext'),
            "user_id" => 1,
            "size" => rand(1, 100)
        ]);
        
        $files = Storage::allFiles('public/photo');
        Storage::delete($files);
    }
}
