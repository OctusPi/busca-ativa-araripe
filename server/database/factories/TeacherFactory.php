<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\Organ;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

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
            'registration' => $this->faker->unique()->word,
            'cpf' => $this->generateCpf(),
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'degree' => $this->faker->word,
            'qualification' => $this->faker->numberBetween(1, 6),
        ];
    }
}
