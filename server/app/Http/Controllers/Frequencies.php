<?php

namespace App\Http\Controllers;

use App\Models\Grid;
use App\Models\Serie;
use App\Utils\Notify;
use App\Models\Classe;
use App\Models\School;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Frequencie;
use App\Providers\Modules;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Http\Middlewares\Data;

class Frequencies extends Controller
{
    public function __construct(){
        parent::__construct(Frequencie::class, Modules::M_FREQ);
    }

    public function save(Request $request)
    {
        if(is_null($request->subject) || is_null($request->teacher) || is_null($request->date_miss)){
            return response()->json(Notify::warning('Campos obrigatórios não informados...'), 401);
        }

        Frequencie::where([
            ['subject', '=', $request->subject],
            ['teacher', '=', $request->teacher],
            ['date_miss', '=', $request->date_miss]
        ])->delete();

        $students = json_decode($request->students, true);

        if(is_array($students)){
            foreach ($students as $student) {
                Frequencie::create([
                    'registration' => $student['registration'],
                    'subject' => $request->subject,
                    'teacher' => $request->teacher,
                    'date_miss' => $request->date_miss
                ]);
            }
        }

        return response()->json(Notify::success('Frequencia salva com sucesso...'));

    }

    public function list(Request $request)
    {
        $mandatory = ['school', 'serie', 'classe', 'subject', 'teacher', 'year', 'date_miss'];
        $filter = $request->all();

        foreach ($mandatory as $value) {
            if(!in_array($value, array_keys($filter))){
                return response()->json(Notify::warning('Aplique o filtro para listar os alunos...'), 403);
            }
        }

        $list = [
            'fouls' => [],
            'students' => []
        ];

        $registrations = Registration::where([
            ['school', '=', $request->school],
            ['serie', '=', $request->serie],
            ['classe', '=', $request->classe],
            ['year', '=', $request->year]
        ])
        ->with('student')
        ->join('students', 'students_registrations.student', '=', 'students.id')
        ->orderBy('students.name', 'asc')->select('students_registrations.*')->get();

        foreach ($registrations as $regs) {
            $regs = $regs->toArray();

            $regs['student']['registration'] = $regs['id'];

            $list['students'][] = $regs['student'];

            $frequencie = Frequencie::firstWhere([
                ['registration', $regs['id']],
                ['subject', $request->subject],
                ['date_miss', $request->date_miss],
            ]);

            if(!is_null($frequencie)){
                $list['fouls'][] = $regs['student'];
            }
        }


        return response()->json($list);
    }

    public function selects(Request $request)
    {
        $selects = [
            'schools' => Data::find(School::class, order:['name']),
            'status'  => Student::list_status()
        ];

        if($request->key == 'schools'){
            $school = School::find($request->search);
            if(!is_null($school)){
                $selects['series'] = Data::find(Serie::class, ['organ' => $school->organ], ['name']);
                $selects['subjects'] = Data::find(Subject::class, ['organ' => $school->organ], ['name']);
            }
        }

        if($request->key == 'classes'){
            $search = explode(',', $request->search);
            $school = School::find($search[0]);

            if(!is_null($school)){
                $selects['series'] = Data::find(Serie::class, ['organ' => $school->organ], ['name']);
                $selects['subjects'] = Data::find(Subject::class, ['organ' => $school->organ], ['name']);
                $selects['classes'] = Data::find(Classe::class, [
                    ['column' => 'school', 'operator' => '=', 'value' => $school->id],
                    ['column' => 'serie', 'operator' => '=', 'value' => $search[1]]
                ], ['name']);
            }
        }

        if($request->key == 'teachers'){
            $search = explode(',', $request->search);
            $school = School::find($search[0]??null);

            if(!is_null($school)){
                $selects['series'] = Data::find(Serie::class, ['organ' => $school->organ], ['name']);
                $selects['subjects'] = Data::find(Subject::class, ['organ' => $school->organ], ['name']);
                $selects['classes'] = Data::find(Classe::class, [
                    ['column' => 'school', 'operator' => '=', 'value' => $school->id],
                    ['column' => 'serie', 'operator' => '=', 'value' => $search[1]??null]
                ], ['name']);

                $selects['teachers'] = Data::find(Grid::class, [
                    ['column'=>'classe', 'operator'=>'=', 'value'=>$search[2] ?? null],
                    ['column'=>'subject', 'operator'=>'=', 'value'=>$search[3] ?? null]
                ], with:['teacher']);
            }
        }

        return response()->json($selects);
    }
}
