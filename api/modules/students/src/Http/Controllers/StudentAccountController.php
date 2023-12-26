<?php

namespace Tamani\Students\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Phone;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Tamani\Admin\Models\Admin;
use Tamani\Curriculum\Models\EducationLevel;
use Tamani\Curriculum\Models\Section;
use Tamani\Students\Helpers\CsvHelper;
use Tamani\Students\Http\Requests\CreateMultipleRequest;
use Tamani\Students\Http\Requests\CreateStudentRequest;
use Tamani\Students\Http\Requests\DeleteStudentsRequest;
use Tamani\Students\Http\Requests\ImportStudentFromCsv;
use Tamani\Students\Http\Requests\UndoFileImportRequest;
use Tamani\Students\Http\Resources\StudentInfoResource;
use Tamani\Students\Http\Resources\StudentResource;
use Tamani\Students\Models\Student;

/**
 * Class StudentAccountController
 * @package Tamani\Students\Http\Controllers
 */
class StudentAccountController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $pageLen = Request::input('per_page', 10);

        $qb = QueryBuilder::for(Student::class)
            ->allowedFilters([
                'section_id',
                'education_level_id',
                'school_year',
                AllowedFilter::callback('search', function(Builder $query, $value){
                    $query->orWhere('first_name', 'like', '%' . $value . '%');
                    $query->orWhere('last_name', 'like', '%' . $value . '%');
                    $query->orWhere('contact_number', 'like', '%' . $value . '%');
                    $query->orWhere('contact_person', 'like', '%' . $value . '%');
                    $query->orWhere('contact_address', 'like', '%' . $value . '%');
                })
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($pageLen);

        return StudentResource::collection($qb);
    }

    /**
     * @param string $uuid
     * @return StudentInfoResource|\Illuminate\Http\JsonResponse
     */
    public function show(string $uuid): StudentInfoResource|\Illuminate\Http\JsonResponse
    {
        $qb = Student::find($uuid);

        if(!$qb){
            return $this->respondWithError('student_not_found_error', 404, 'Student Not Found');
        }

        return new StudentInfoResource($qb);
    }

    /**
     * @param CreateStudentRequest $request
     * @return StudentResource
     */
    public function store(CreateStudentRequest $request): StudentResource
    {
        $data = $request->only(Student::FILLABLE);

        $sectionId = $request->validated('section.id');
        $levelId = $request->validated('section.id');

        $section = Section::find($sectionId);
        $level = EducationLevel::find($levelId);

        $data['contact_number'] = Phone::cleanPhoneNumber($data['contact_number']);

        if($section){
            $data['section_id'] = $section->id;
            $data['education_level_id'] = $section->educationLevel->id;
        }
        elseif($level){
            $data['education_level_id'] = $level->id;
        }

        $student = new Student($data);

        $student->save();

        return new StudentResource($student);
    }

    public function update(CreateStudentRequest $request, string $id): \Illuminate\Http\JsonResponse|StudentResource
    {
        $student = Student::find($id);

        if (!$student) {
            return $this->respondWithError('STUDENT_NOT_FOUND_ERROR', 404, 'Student Not Found');
        }

        $student->update($request->validated());

        return new StudentResource($student);
    }

    /**
     * @param ImportStudentFromCsv $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importStudentsFromCsv(ImportStudentFromCsv $request): \Illuminate\Http\JsonResponse
    {
        /** @var Admin $user */
        $user = auth()->user();
        $importedFilename = Carbon::now()->format('His') . '-' . strtoupper(Str::random(4)) . '-' . $user->id;
        $currentFolderPath = Carbon::now()->format('Y-m-d');
        $filePath = $request->file('file')->storeAs('private/student-imports/' . $currentFolderPath, $importedFilename);

        if($filePath){
            $this->saveCsvFile($filePath);
        }

        return $this->respondWithEmptyData(201);
    }

    /**
     * @param UndoFileImportRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function undoStudentCsvImport(UndoFileImportRequest $request): \Illuminate\Http\JsonResponse
    {
        $filepath = $request->validated('filepath');
        $filepath = 'private/student-imports/' . $filepath;
        $fileExists = Storage::exists($filepath);

        if(!$fileExists) return $this->respondWithError('file_not_found', 404, 'Csv file not found.');

        $students = $this->parseCsvFile($filepath);

        if(!empty($students)){
            foreach ($students as $student){
                if($student){
                    $student->delete();
                }
            }
        }

        return $this->respondWithEmptyData(410);
    }

    /**
     * @param DeleteStudentsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteStudents(DeleteStudentsRequest $request): \Illuminate\Http\JsonResponse
    {
        $students = Student::whereIn('id', $request->input('ids'));

        $students->delete();

        return $this->respondWithEmptyData(410);
    }

    public function listImports(string $dir)
    {
        $listing = Storage::files('private/student-imports/' . $dir);

        $finalList = [];

        foreach ($listing as $file) {
            $finalList[] = basename($file);
        }

        return response()->json($finalList);
    }

    public function saveMultiple(CreateMultipleRequest $request)
    {
        $students = $request->validated('data');
        $sectionId = $request->validated('section.id');
        $levelId = $request->validated('section.id');

        $section = Section::find($sectionId);
        $level = EducationLevel::find($levelId);

        foreach ($students as $student) {
            if ($section) {
                $student['section_id'] = $section->id;
                $student['education_level_id'] = $section->educationLevel->id;
            } elseif ($level) {
                $student['education_level_id'] = $level->id;
            }

            $savedStudent = new Student($student);
            $savedStudent->save();
        }

        return $this->respondWithEmptyData();
    }

    /**
     * @param string $filePath
     */
    protected function saveCsvFile(string $filePath): void
    {
        $file = Storage::get($filePath);

        $lines = explode(PHP_EOL, $file);

        foreach ($lines as $idx => $line) {
            if ($idx > 0 && !empty($line)) {
                $csrow = explode(',', $line);
                $studentData = CsvHelper::mapStudentDataCsv($csrow);

                $student = Student::where('student_id', $studentData['student_id'])->first();

                $studentData['contact_number'] = Phone::cleanPhoneNumber($studentData['contact_number']);

                if($student){
                    $student->update($studentData);
                    $student->save();
                }else{
                    $student = new Student($studentData);
                    $student->import_file = $filePath;
                    $student->save();
                }
            }
        }
    }

    /**
     * @param string $filePath
     * @return array
     */
    protected function parseCsvFile(string $filePath): array
    {
        $file = Storage::get($filePath);

        $lines = explode(PHP_EOL, $file);

        $students = [];

        foreach ($lines as $idx => $line) {
            if ($idx > 0 && !empty($line)) {
                $csrow = explode(',', $line);
                $studentData = CsvHelper::mapStudentDataCsv($csrow);

                $students[] = Student::where('student_id', $studentData['student_id'])
                ->where('import_file', $filePath)->first();
            }
        }

        return $students;
    }

}
