<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Mahmoud Abdelrahim' ,
            'email' => 'mahmoud@gmail.com' ,
            'password' => Hash::make('Osha.com01640164#') ,
        ]) ;
    }
}
