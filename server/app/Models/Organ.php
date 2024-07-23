<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

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

    public function rules(): array
    {
        return [
            'name' => 'required',
            'cnpj' => ['required', Rule::unique('organs', 'cnpj')->ignore($this->id)],
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
            'postalcity' => 'required',
            'postalcode' => 'required',
            'status' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Campo obrigatório não informado...',
            'unique' => 'Orgão já registrado no sistema...'
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function serie(): BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function grid():BelongsTo
    {
        return $this->belongsTo(Grid::class);
    }
}
