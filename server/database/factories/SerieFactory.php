<?php

namespace Database\Factories;

use App\Models\Serie;
use App\Models\Organ;
use Illuminate\Database\Eloquent\Factories\Factory;

class SerieFactory extends Factory
{
    protected $model = Serie::class;

    public function definition()
    {
        return [
            'organ' => Organ::inRandomOrder()->first()->id, // Assumindo que você tem registros na tabela organs
            'code' => $this->faker->unique()->numerify('S###'),
            'name' => $this->faker->word,
            'course' => $this->faker->numberBetween(1, 10), // Ajuste conforme necessário
            'level' => $this->faker->numberBetween(1, 10), // Ajuste conforme necessário
            'status' => $this->faker->boolean,
        ];
    }
}
