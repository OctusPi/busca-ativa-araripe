<?php

namespace App\Models;

use App\Utils\Dates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Frequencie extends Model
{
    use HasFactory;

    protected $table = 'frequencies';

    protected $fillable = [
        'id',
        'registration',
        'subject',
        'teacher',
        'date_miss'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function rules(): array
    {
        return [
            'registration' => 'required',
            'subject'   => 'required',
            'date_miss' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Campo obrigatório não informado...'
        ];
    }

    public function dateMiss():Attribute
    {
        return Attribute::make(
            get: fn(?string $v) => Dates::convert($v, Dates::UTC, Dates::PTBR)
        );
    }
}
