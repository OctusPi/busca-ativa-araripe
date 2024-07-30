<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Organ;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public static function generateCpf()
    {
        // Gera os 9 primeiros dígitos do CPF
        $cpf = [];
        for ($i = 0; $i < 9; $i++) {
            $cpf[] = rand(0, 9);
        }
        
        // Calcula o primeiro dígito verificador
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $cpf[$i] * (10 - $i);
        }
        $firstDigit = ($sum % 11) < 2 ? 0 : 11 - ($sum % 11);
        $cpf[] = $firstDigit; // Adiciona o primeiro dígito verificador
        
        // Calcula o segundo dígito verificador
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $cpf[$i] * (11 - $i);
        }
        $secondDigit = ($sum % 11) < 2 ? 0 : 11 - ($sum % 11);
        $cpf[] = $secondDigit; // Adiciona o segundo dígito verificador
        
        // Formata o CPF
        return sprintf('%d%d%d.%d%d%d.%d%d%d-%d%d', $cpf[0], $cpf[1], $cpf[2], $cpf[3], $cpf[4], $cpf[5], $cpf[6], $cpf[7], $cpf[8], $cpf[9], $cpf[10]);
    }

    public function definition()
    {
        return [
            'organ' => Organ::inRandomOrder()->first()->id, // Assumindo que você tem registros na tabela organs
            'name' => $this->faker->name,
            'birth' => $this->faker->date('d/m/Y'),
            'cpf' => $this->generateCpf(),
            'nis' => $this->faker->numerify('##########'),
            'id_sige' => $this->faker->unique()->numerify('##########'),
            'id_censo' => $this->faker->numerify('##########'),
            'race' => $this->faker->numberBetween(1, 10), // Ajuste conforme necessário
            'sex' => $this->faker->numberBetween(1, 2), // Ajuste conforme necessário (1 para masculino, 2 para feminino)
            'father' => $this->faker->name,
            'mother' => $this->faker->name,
            'street' => $this->faker->streetAddress,
            'neighborhood' => $this->faker->word,
            'city' => $this->faker->city,
            'cep' => $this->faker->postcode,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->safeEmail,
            'status' => $this->faker->numberBetween(0, 1), // Ajuste conforme o significado do campo status
        ];
    }
}
