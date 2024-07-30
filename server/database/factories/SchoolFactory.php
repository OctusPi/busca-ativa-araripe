<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\Organ;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolFactory extends Factory
{
    protected $model = School::class;

    public function definition()
    {
        return [
            'organ' => Organ::inRandomOrder()->first()->id, // Assumindo que você tem registros na tabela organs
            'inep' => $this->faker->unique()->numerify('##########'),
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'status' => $this->faker->boolean,
        ];
    }
}
