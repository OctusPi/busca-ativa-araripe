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
        Serie::factory(10)->create();
        Student::factory(10)->create();
        Organ::factory(10)->create();
        School::factory(10)->create();
        Teacher::factory(10)->create();
        Classe::factory(10)->create();
    }
}
