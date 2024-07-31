<?php

namespace Database\Factories;

use App\Models\Organ;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganFactory extends Factory
{
    protected $model = Organ::class;

    public function definition()
    {
        return [
            'logomarca' => $this->faker->company, // Pode gerar um texto aleatório para simular uma logomarca
            'name' => $this->faker->company,
            'cnpj' => $this->faker->unique()->numerify('##.###.###/####-##'),
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->address,
            'postalcity' => $this->faker->city,
            'postalcode' => $this->faker->postcode,
            'status' => $this->faker->numberBetween(0, 1), // Assumindo que o status é um valor binário (0 ou 1)
        ];
    }
}
