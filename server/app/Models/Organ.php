<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

class Organ extends Model
{
    use HasFactory;

    const S_INACTIVE = 0;
    const S_ACTIVE = 1;

    protected string $table = 'organs';

    protected $fillable = [
        'name',
        'cnpj',
        'phone',
        'email',
        'address',
        'postalcity',
        'postalcode',
        'status'
    ];

    public function school():BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function serie():BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }

    public function classe():BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function subject():BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function student():BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function registration():BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public static function validateFields(?int $id = null):array
    {
        return [
            'name'       => 'required',
            'cnpj'       => ['required', Rule::unique('organs')->ignore($id)],
            'phone'      => 'required',
            'email'      => 'required|email',
            'address'    => 'required',
            'postalcity' => 'required',
            'postalcode' => 'required',
            'status'     => 'required'
        ];
    }

    public static function validateMsg():array
    {
        return [
            'required' => 'Campo obrigatório não informado!',
            'email'    => 'Informe um email válido!',
            'unique'   => 'Orgão já registrado no sistema!'
        ];
    }

    public static function list_status():array
    {
        return [
            ['id' => self::S_INACTIVE, 'title' => 'Inativo'],
            ['id' => self::S_ACTIVE, 'title' => 'Ativo']
        ];
    }
}
