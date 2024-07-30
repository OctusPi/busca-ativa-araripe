<?php

namespace Database\Factories;

use App\Models\Grid;
use App\Models\Organ;
use App\Models\School;
use App\Models\Serie;
use App\Models\Classe; // Ajuste o nome do modelo conforme necessário
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class GridFactory extends Factory
{
    protected $model = Grid::class;

    public function definition()
    {
        return [
            'organ' => Organ::inRandomOrder()->first()->id, // Assumindo que você tem registros na tabela organs
            'school' => School::inRandomOrder()->first()->id, // Assumindo que você tem registros na tabela schools
            'serie' => Serie::inRandomOrder()->first()->id, // Assumindo que você tem registros na tabela series
            'classe' => Classe::inRandomOrder()->first()->id, // Assumindo que você tem registros na tabela classes
            'subject' => Subject::inRandomOrder()->first()->id, // Assumindo que você tem registros na tabela subjects
            'teacher' => Teacher::inRandomOrder()->first()->id, // Assumindo que você tem registros na tabela teachers
        ];
    }
}
