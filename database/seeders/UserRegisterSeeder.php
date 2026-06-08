<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRegisterSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'جهاد عبدالرحيم' ,
            'email' => 'gehad@gmail.com' ,
            'password' => Hash::make('123456789') ,
        ]) ;

        User::create([
            'name' => 'محمد عبدالرحيم' ,
            'email' => 'mohamed@gmail.com' ,
            'password' => Hash::make('123456789') ,
        ]) ;

        User::create([
            'name' => 'اميرة عبدالرحيم' ,
            'email' => 'amira@gmail.com' ,
            'password' => Hash::make('123456789') ,
        ]) ;
    }
}
