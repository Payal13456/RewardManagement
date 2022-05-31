<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'  =>  'Reward Management',
            'short_name'    =>  'admin',
            'email' =>  'admin@mailinator.com',
            'phone_code'    =>  '+91',
            'phone' =>  '951xxxx753',
            'password'  =>  bcrypt('123456'),
            'role'  =>  1
        ]);
    }
}
