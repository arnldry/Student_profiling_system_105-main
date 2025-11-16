<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'capstoneone2025@gmail.com',
            'password' => Hash::make('123123123'),
            'role' => 'superadmin',
        ]);
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123123123'),
            'role' => 'admin',
        ]);

        $user = User::create([
            'name' => 'Arnold Rey Strader Lapuz',
            'email' => 'student@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'student',
        ]);

        $user = User::create([
            'name' => 'Arnold Rey',
            'email' => 'student1@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'student',
        ]);


        // $faker = Faker::create();
        // $users = [];

        // for ($i = 1; $i <= 125; $i++) {
        //     $domain = $faker->randomElement(['gmail.com', 'yahoo.com']);
        //     $username = $faker->unique()->userName;
        //     $email = strtolower($username . '@' . $domain);

        //     $users[] = [
        //         'name' => $faker->name(),
        //         'email' => $email,
        //         'password' => Hash::make('123'),
        //         'role' => 'student',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ];

        
        //     if ($i % 500) {
        //         User::insert($users);
        //         $users = [];
        //     }
        // }

        // if (!empty($users)) {
        //     User::insert($users);
        // }


    }
}
