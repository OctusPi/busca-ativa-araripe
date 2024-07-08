<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class School extends BaseModel
{
    use HasFactory;

    protected $table = 'schools';

    protected $fillable = [
        'organ',
        'inep',
        'name',
        'address',
        'status'
    ];

    public function organ():HasOne{
        return $this->hasOne(Organ::class, 'id', 'organ');
    }

    public function classe():BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function registration():BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function grid():BelongsTo
    {
        return $this->belongsTo(Grid::class);
    }

    public static function validateFields(?int $id = null):array
    {
        return [
            'organ'      => 'required',
            'inep'       => ['required', Rule::unique('schools')->ignore($id)],
            'name'       => 'required',
            'address'    => 'required',
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

    public function list_status():array
    {
        return [
            ['id' => 1, 'title' => 'Ativo'],
            ['id' => 0, 'title' => 'Inativo']
        ];
    }
}
