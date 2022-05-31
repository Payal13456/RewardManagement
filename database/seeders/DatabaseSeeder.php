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
            'email' =>  'admin@mailinator.com',
            'mobile_no' =>  '951xxxx753',
            'password'  =>  bcrypt('123456'),
            'location'  =>  null,
            'emirates_id'   =>  null,
            'passport_no'   =>  null,
            'dob'   =>  null,
            'address'   =>  null,
            'role'  =>  1,
            'status'    =>  1,
            'otp_status'    =>  1
        ]);
    }
}
