<?php

namespace App\Models;

use App\Security\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Organ extends Model
{
    use HasFactory;

    protected $table = 'organs';

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

    public function teacher():BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function grid():BelongsTo
    {
        return $this->belongsTo(Grid::class);
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
            ['id' => Common::S_INACTIVE, 'title' => 'Inativo'],
            ['id' => Common::S_ACTIVE, 'title' => 'Ativo']
        ];
    }
}
