<?php

namespace Database\Factories;

use App\Models\Classe; // Ajuste o namespace conforme o nome do seu modelo
use App\Models\Organ;
use App\Models\School;
use App\Models\Serie;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClasseFactory extends Factory
{
    protected $model = Classe::class; // Ajuste o nome do modelo conforme necessário

    public function definition()
    {
        return [
            'organ' => Organ::inRandomOrder()->first()->id, // Assumindo que você tem registros na tabela organs
            'school' => School::inRandomOrder()->first()->id, // Assumindo que você tem registros na tabela schools
            'serie' => Serie::inRandomOrder()->first()->id, // Assumindo que você tem registros na tabela series
            'name' => $this->faker->word,
            'turn' => $this->faker->numberBetween(1, 3), // Ajuste conforme o número de turnos possíveis
            'status' => $this->faker->boolean,
        ];
    }
}
