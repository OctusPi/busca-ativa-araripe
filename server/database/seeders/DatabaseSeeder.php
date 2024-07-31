<?php

namespace Database\Seeders;

use App\Models\Serie;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\School;
use App\Models\Organ;
use App\Models\Classe;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(?array $data = null): void
    {
        Serie::factorty(10)->create();
        Student::factorty(10)->create();
        Organ::factorty(10)->create();
        School::factorty(10)->create();
        Teacher::factorty(10)->create();
        Classe::factorty(10)->create();
    }
}
