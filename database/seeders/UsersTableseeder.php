<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;

class UsersTableseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // $user = new Users();
        // $user->first_name = 'admin';
        // $user->last_name = 'admin';
        // $user->email='admin@gmail.com';
        // $user->password = 'admin';
        // $user->role_id = 1;
        // $user->status = 1;
        // $user->save();
        Users::truncate();
        Users::insert([
            [
                 'first_name' => "Admin",
                 'last_name' => "Singh",
                 'email'  => 'admin@gmail.com',
                 'password'  => '$2y$10$zCb58rNTBpgI5XGTuYp33.L4hpKZe5ND35iyzpTf2yhCzYhdPso3K',
                 'joining_date'  => '2024-07-01',
                 'role_id' => 1,
                 'status' => 1

            ],
			[
                 'first_name' => "Harpreet",
                 'last_name' => "Singh",
                 'email'  => 'harpreet@gmail.com',
                 'password'  => '$2y$10$zCb58rNTBpgI5XGTuYp33.L4hpKZe5ND35iyzpTf2yhCzYhdPso3K',
                 'joining_date'  => '2024-07-01',
                 'role_id' => 3,
                 'status' => 1
                 
            ],
            [
                'first_name' => "Shubham",
                'last_name' => "Sharma",
                 'email'  => 'shubham@gmail.com',
                 'password'  => '$2y$10$zCb58rNTBpgI5XGTuYp33.L4hpKZe5ND35iyzpTf2yhCzYhdPso3K',
                 'role_id' => 3,
                 'joining_date'  => '2024-07-01',
                 'status' => 1
            ]
        ]);
        
    }
}