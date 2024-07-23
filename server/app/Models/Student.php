<?php

namespace App\Models;

use App\Utils\Dates;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'organ',
        'name',
        'birth',
        'race',
        'sex',
        'cpf',
        'nis',
        'sige',
        'father',
        'mother',
        'address',
        'locality',
        'phone',
        'email',
        'status'
    ];

    public function birth():Attribute
    {
        return Attribute::make(
            get: fn(?string $v) => Dates::convert($v, Dates::UTC, Dates::PTBR),
            set: fn(?string $v) => Dates::convert($v, Dates::PTBR, Dates::UTC)
        );
    }

    public function rules(): array
    {
        return [
            'organ' => 'required',
            'name'  => 'required',
            'birth' => 'required',
            'mother'=> 'required',
            'sige'  => ['required', Rule::unique('students', 'sige')->ignore($this->id)],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Campo obrigatório não informado...',
            'unique' => 'Aluno já registrado no sistema...',
        ];
    }

    public function organ():HasOne
    {
        return $this->hasOne(Organ::class,'id','organ');
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public static function list_status():array
    {
        return [
            ['id' => 1, 'title' => 'Ativo'],
            ['id' => 2, 'title' => 'Não Residente'],
            ['id' => 3, 'title' => 'Transferido']
        ];
    }
}
