<?php

namespace App\Http\Controllers;

use App\Utils\Utils;
use App\Models\Organ;
use App\Models\Serie;
use App\Utils\Notify;
use App\Models\Classe;
use App\Models\School;
use App\Models\Student;
use App\Providers\Modules;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Http\Middlewares\Data;

class Registrations extends Controller
{
    public function __construct(){
        parent::__construct(Registration::class, Modules::M_REGS);
    }

    public function validate_reg(?array $data):bool
    {
        return $this->validate($data) == null;
    }

    public function save(Request $request)
    {
        if(is_null($request->students)){
            return response()->json(Notify::warning('Selecione pelo menos 01 aluno para matricular'));
        }

        $fails = 0;
        $students = json_decode($request->students, true);
        foreach ($students as $student) {
            $registration = $this->model->firstWhere([
                ['student', '=', $student['id']],
                ['year', '=', $request->year]
            ]) ?? new Registration();

            $registration->fill(array_merge($request->all(), ['student' => $student['id']]));
            $fails += $registration->save() ? 0 : 1;
        }

        return response()->json($fails > 0 ? Notify::warning("Falha ao registrar $fails matriículas") : Notify::success(), $fails > 0 ? 500 : 200);
    }

    public function details(Request $request)
    {
        return $this->base_details($request, ['student']);
    }

    public function list(Request $request)
    {

        if($request->modality === 'student'){
            $search = Utils::map_search(['name', 'id_sige', 'cpf'], $request->all());
            $students = Data::find(Student::class, $search, ['name']);

            $registrations = $this->model->query();
            $registrations->where(function ($query) use ($students) {
                foreach ($students as $student) {
                    $query->orWhere('student', $student->id);
                }
            });

            return response()->json($registrations->with(['school', 'serie', 'classe', 'student'])->get());
        }

        return $this->base_list($request, ['school', 'serie', 'classe', 'year'], with:['school', 'serie', 'classe', 'student']);

    }

    public function selects(Request $request)
    {
        $selects = [
            'organs'   => Data::find(Organ::class, order:['name']),
            'schools'  => Data::find(School::class, order: ['name']),
            'status'  => Registration::list_status(),
            'students_status' => Student::list_status(),
        ];

        if($request->key == 'schools'){
            $selects['schools'] = Data::find(School::class, ['organ' => $request->search], order: ['name']);
            $selects['series'] = Data::find(Serie::class, ['organ' => $request->search], order: ['name']);
        }

        if($request->key == 'classes'){
            $search = explode(',', $request->search);
            $selects['schools'] = Data::find(School::class, ['organ' => $search[0] ?? null], order: ['name']);
            $selects['series'] = Data::find(Serie::class, ['organ' => $search[0] ?? null], order: ['name']);
            $selects['classes'] = Data::find(Classe::class, [
                ['column' => 'school', 'operator' => '=', 'value' => $search[1] ?? null],
                ['column' => 'serie', 'operator' => '=', 'value' => $search[2] ?? null],
            ], ['name']);
        }

        return response()->json($selects);
    }
}
