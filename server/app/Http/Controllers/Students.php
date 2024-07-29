<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use App\Utils\Dates;
use App\Models\Organ;
use App\Utils\Notify;
use App\Models\Classe;
use App\Models\School;
use App\Models\Student;
use App\Providers\Modules;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Http\Middlewares\Data;

class Students extends Controller
{
    public function __construct()
    {
        parent::__construct(Student::class, Modules::M_STUDENTS);
    }

    public function list(Request $request)
    {
        if ($request->birth) {
            $request->birth = Dates::convert($request->birth, Dates::PTBR, Dates::UTC);
        }

        return $this->base_list($request, ['name', 'sige', 'cpf', 'mother', 'birth', 'status'], ['name']);
    }

    public function selects(Request $request)
    {
        $selects = [
            'organs'  => Data::find(Organ::class, order: ['name']),
            'series'  => Data::find(Serie::class, order: ['name']),
            'races'   => Student::list_races(),
            'sexs'    => Student::list_sexs(),
            'status'  => Student::list_status(),
        ];

        if($request->key == 'organ'){
            $selects['schools'] = Data::find(School::class, ['organ' => $request->search], ['name']);
        }

        if($request->key == 'school'){
            $search = explode(',', $request->search);
            $selects['classes'] = Data::find(Classe::class, [
                ['column' => 'school', 'operator' => '=', 'value' => $search[0]],
                ['column' => 'serie', 'operator' => '=', 'value' => $search[1]],
            ], ['name']);
        }

        return response()->json($selects);
    }

    public function import(Request $request)
    {
        if (is_null($request->organ)) {
            return response()->json(Notify::warning('Selecione o orgão de vinculo dos alunos...'), 404);
        }

        if (is_null($request->import)) {
            return response()->json(Notify::warning('Não existem dados a serem importados...'), 404);
        }

        $fails = 0;
        $data  = $this->normalize(json_decode($request->import, true));
        foreach ($data as $val) {
            $student = Student::firstWhere('id_sige', $val['id_sige']) ?? new Student();
            $student->fill(array_merge($val, ['organ' => $request->organ, 'status' => Student::S_ACTIVE]));
            $save = $student->save();

            $data_reg = [
                'organ'   => $request->organ,
                'school'  => $request->school,
                'serie'   => $request->serie,
                'classe'  => $request->classe,
                'student' => $student->id,
                'year'    => $request->year,
                'status'  => Registration::S_ACTIVE
            ];

            if((new Registrations())->validate_reg($data_reg) && $save) {
                $registration = Registration::firstWhere([
                    ['student', '=', $student->id],
                    ['year', '=', $request->year]
                ]) ?? new Registration();
                $registration->fill($data_reg);
                $registration->save();
            }

            if(!$save){
                $fails++;
            }
        }

        return response()->json($fails > 0 
        ? Notify::warning("Falha ao importar $fails alunos")
        : Notify::success("Registros importados com sucesso"), $fails > 0 ? 500 : 200);
    }

    private function normalize(?array $data): array
    {
        $normalize = [];

        if ($data) {
            foreach ($data as $val) {
                $val['sex']  = substr($val['sex'] ?? '', 0, 1) == 'F' ? 1 : 2;
                $normalize[] = $val;
            }
        }

        return $normalize;
    }
}
