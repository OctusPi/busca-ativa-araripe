<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    const S_ACTIVE = 1;
    const S_INACTIVE = 0;

    protected $table = 'schools';

    protected $fillable = [
        'organ',
        'inep',
        'name',
        'address',
        'phone',
        'email',
        'status'
    ];

    public function rules(): array
    {
        return [
            'organ'      => 'required',
            'inep'       => ['required', Rule::unique('schools')->ignore($this->id)],
            'name'       => 'required',
            'status'     => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Campo obrigatório não informado...',
            'unique' => 'Escola já registrado no sistema...'
        ];
    }

    public function organ():HasOne
    {
        return $this->hasOne(Organ::class,'id','organ');
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function grid():BelongsTo
    {
        return $this->belongsTo(Grid::class);
    }

    public static function list_status():array
    {
        return [
            ['id' => self::S_ACTIVE, 'title' => 'Ativo'],
            ['id' => self::S_INACTIVE, 'title' => 'Inativo']
        ];
    }
}
