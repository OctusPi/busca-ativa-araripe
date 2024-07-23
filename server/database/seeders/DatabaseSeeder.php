<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(?array $data = null): void
    {
        // \App\Models\User::factory(10)->create();

        if($data){
            $data['profile'] = User::P_ADMIN;
            $data['modules'] = User::list_modules();
            $data['status']  = true;
            User::factory()->create($data);
        }else{
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'username' => 'test@example.com',
                'password' => Hash::make('senha123'),
                'profile' => User::P_ADMIN,
                'modules' => User::list_modules(),
                'status' => true
            ]);
        }
    }
}
