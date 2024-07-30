<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Serie;
use App\Models\Student;
use App\Models\Organ;
use App\Models\School;
use App\Models\Teacher;
use App\Models\Classe;
use App\Models\Subject;
use App\Models\Grid;

use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(?array $data = null): void
    {
        // Cria registros para os modelos
        Organ::factory(5)->create();
        School::factory(10)->create();
        Serie::factory(10)->create();
        Classe::factory(10)->create();
        Subject::factory(10)->create();
        Teacher::factory(10)->create();
        Student::factory(10)->create();
        Grid::factory(10)->create();

        // Cria usuários com dados específicos
    
        if($data){
            $data['profile'] = User::P_ADMIN;
            $data['modules'] = User::list_modules();
            $data['status']  = true;
            User::factory()->create($data);
        }else{
            User::factory()->create([
                'name' => 'Octus',
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
