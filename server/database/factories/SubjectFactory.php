<?php

namespace Database\Factories;

use App\Models\Subject; // Ajuste o namespace conforme o nome do seu modelo
use App\Models\Organ;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    protected $model = Subject::class; // Ajuste o nome do modelo conforme necessário

    public function definition()
    {
        return [
            'organ' => Organ::inRandomOrder()->first()->id, // Assumindo que você tem registros na tabela organs
            'name' => $this->faker->unique()->word,
            'area' => $this->faker->numberBetween(1, 10), // Ajuste conforme necessário para representar áreas de estudo
            'description' => $this->faker->sentence, // Use sentence para uma descrição simples
        ];
    }
}
