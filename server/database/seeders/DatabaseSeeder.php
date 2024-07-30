<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Serie;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\School;
use App\Models\Organ;
use App\Models\Classe;

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
        // User::factory(10)->create();
        // Serie::factorty(10)->create();
        Student::factorty(10)->create();
        Organ::factorty(10)->create();
        School::factorty(10)->create();
        Teacher::factorty(10)->create();
        Classe::factorty(10)->create();

        if($data){
            $data['profile'] = User::P_ADMIN;
            $data['modules'] = User::list_modules();
            $data['status']  = true;
            User::factory()->create($data);
        }else{
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'octus@mail.com',
                'username' => 'octus@mail.com',
                'password' => Hash::make('senha123'),
                'profile' => User::P_ADMIN,
                'modules' => User::list_modules(),
                'status' => true
            ]);
        }
    }
}
