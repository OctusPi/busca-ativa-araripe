<?php

namespace Database\Seeders;

use App\Models\BaseModel;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name'       => 'Test User',
            'email'      => 'test@example.com',
            'username'   => 'test@example.com',
            'password'   => 'senha123',
            'token'      => null,
            'organs'     => null,
            'profile'    => BaseModel::P_ADMIN,
            'modules'    => json_encode(User::list_modules()),
            'passchange' => false,
            'status'     => BaseModel::S_ACTIVE,
            'lastlogin'  => null,
            'nowlogin'   => null
        ]);
    }
}
