<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientCommentRequest;
use App\Http\Requests\PatientReportRequest;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\PrintDateRequest;
use App\Jobs\AddUniqCodeForPatient;
use App\Models\Doctor;
use App\Models\MedicalClinic;
use App\Models\Patient;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read_all_patients')->only(['all', 'fullRecords']);
        $this->middleware('can:create_patients')->only(['create', 'store']);
        $this->middleware('can:edit_patients,read_all_patients')->only(['edit', 'update']);
        $this->middleware('can:edit_patients')->only('deletePhoto');
        $this->middleware('can:add_report')->only(['saveReport', 'submit']);
        $this->middleware('can:add_comment')->only(['saveComment']);
    }

    public function all()
    {
        $patients = Patient::filter()
            ->orderBy('created_at', 'DESC')
            ->paginate(100)
            ->through(fn ($patient) => [
                'id' => $patient->id,
                'name' => $patient->name,
                'status' => $patient->status,
                'diagnosis' => $patient->diagnosis,
                'case_numbers' => implode(', ', $patient->case_numbers),
            ]);

        $firstItem = $patients->firstItem();

        return inertia('Patients/All', compact('patients', 'firstItem'));
    }

    public function fullRecords()
    {
        $patients = Patient::orderBy('created_at', 'DESC')
            ->get()
            ->transform(fn ($patient) => [
                'id' => $patient->id,
                'name' => $patient->name,
                'phone' => $patient->phone,
                'case_numbers' => implode(', ', $patient->case_numbers),
                'birthday' => $patient->birthday->format('d.m.Y'),
                'age' => now()->diff($patient->birthday)->format('%y'),
                'gender' => $patient->gender,
                'sampling_date' => $patient->sampling_date->format('d.m.Y H:i'),
                'sample_receipt_date' => $patient->sample_receipt_date->format('d.m.Y H:i'),
                'anamnes' => $patient->anamnes,
                'doctor' => $patient->doctor->name,
                'medical_clinic' => $patient->medicalClinic?->name,
                'categories' => implode(', ', $patient->categories_formatted),
                'microscopic_description' => $patient->microscopic_description,
                'diagnosis' => $patient->diagnosis,
                'note' => $patient->note,
                'comment' => $patient->comment,
                'status' => $patient->status,
                'photos' => $patient->photos->transform(fn ($photo) => [
                    'id' => $photo->id,
                    'url' => $photo->url,
                ]),
                'hashid' => $patient->hashid,
                'place_of_residence' => $patient->place_of_residence,
            ]);

        return inertia('Patients/FullRecords', compact('patients'));
    }

    public function dailyStatistics()
    {
        $statistics = Patient::select(
            DB::raw('CAST(sampling_date AS DATE) s_date'),
            DB::raw('COUNT(*) AS total')
        )
            ->orderBy('s_date', 'DESC')
            ->groupBy('s_date')
            ->get();

        return inertia('Patients/DailyStatistics', compact('statistics'));
    }

    public function index()
    {
        $patients = Patient::my('created_by')
            ->filter()
            ->orderBy('created_at', 'DESC')
            ->paginate(100)
            ->through(fn ($patient) => [
                'id' => $patient->id,
                'name' => $patient->name,
                'status' => $patient->status,
                'case_numbers' => implode(', ', $patient->case_numbers),
            ]);

        return inertia('Patients/Index', compact('patients'));
    }

    public function create()
    {
        $doctors = Doctor::orderBy('name', 'ASC')->get(['id', 'name']);
        $medicalClinics = MedicalClinic::get(['id', 'name']);

        return inertia('Patients/Edit', compact('doctors', 'medicalClinics'));
    }

    public function store(PatientRequest $request)
    {
        $patient = DB::transaction(function () use ($request) {
            $doctor = $request->getDoctor();

            $data = [
                'doctor_id' => $this->findDoctorOrCreateByName($doctor, $request->doctor_phone),
                'medical_clinic_id' => $this->findClinicOrCreateByName($request->medical_clinic),
            ];

            $patient = Patient::create($request->validated() + $data);

            $patient->generateCaseNumbers();

            $this->uploadPhotos($patient, $request);

            AddUniqCodeForPatient::dispatch($patient);

            return $patient;
        });

        return redirect()->route('patients.show', $patient->id)->with('isCreated', true);
    }

    public function show(int $id)
    {
        $patient = Patient::myByPermission()->findOrFail($id);

        return inertia('Patients/Show', [
            'qrCode' => (string) QrCode::size(100)->generate(route('patients.public_show', $patient->hashid)),
            'patient' => [
                'id' => $patient->id,
                'uniq_code' => $patient->uniq_code,
                'name' => $patient->name,
                'phone' => $patient->phone,
                'case_numbers' => $patient->case_numbers,
                'birthday' => $patient->birthday->format('d.m.Y'),
                'gender' => $patient->gender,
                'sampling_date' => $patient->sampling_date->format('d.m.Y H:i'),
                'sample_receipt_date' => $patient->sample_receipt_date->format('d.m.Y H:i'),
                'anamnes' => $patient->anamnes,
                'doctor' => $patient->doctor->name,
                'categories' => implode(', ', $patient->categories_formatted),
                'microscopic_description' => $patient->microscopic_description,
                'diagnosis' => $patient->diagnosis,
                'note' => $patient->note,
                'comment' => $patient->comment,
                'status' => $patient->status,
                'photos' => $patient->photos->transform(fn ($photo) => [
                    'id' => $photo->id,
                    'url' => $photo->url,
                ]),
                'hashid' => $patient->hashid,
                'place_of_residence' => $patient->place_of_residence,
                'medical_clinic' => $patient->medicalClinic?->name,
            ],
        ]);
    }

    public function edit(int $id)
    {
        $patient = Patient::myByPermission()->findOrFail($id);

        $doctors = Doctor::orderBy('name', 'DESC')->get(['id', 'name']);

        $medicalClinics = MedicalClinic::get(['id', 'name']);

        return inertia('Patients/Edit', [
            'id' => $patient->id,
            'doctors' => $doctors,
            'medicalClinics' => $medicalClinics,
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->name,
                'phone' => $patient->phone,
                'birthday' => $patient->birthday->format('d.m.Y'),
                'gender' => $patient->gender,
                'sampling_date' => $patient->sampling_date->format('d.m.Y H:i'),
                'sample_receipt_date' => $patient->sample_receipt_date->format('d.m.Y H:i'),
                'anamnes' => $patient->anamnes,
                'doctor' => $patient->doctor_id,
                'medical_clinic' => $patient->medical_clinic_id,
                'categories' => $patient->categories,
                'place_of_residence' => $patient->place_of_residence,
            ]]);
    }

    public function update(int $id, PatientRequest $request)
    {
        $patient = DB::transaction(function () use ($id, $request) {
            $patient = Patient::myByPermission()->findOrFail($id);

            $doctor = $request->getDoctor();

            $data = [
                'doctor_id' => $this->findDoctorOrCreateByName($doctor, $request->doctor_phone),
                'medical_clinic_id' => $this->findClinicOrCreateByName($request->medical_clinic),
            ];

            $patient->update($request->validated() + $data);

            $patient->generateCaseNumbers();

            $this->uploadPhotos($patient, $request);

            return $patient;
        });

        return redirect()->route('patients.show', $patient->id);
    }

    public function saveReport(Patient $patient, PatientReportRequest $request)
    {
        $patient->update($request->validated());

        return redirect()->route('patients.show', $patient->id);
    }

    public function saveComment(Patient $patient, PatientCommentRequest $request)
    {
        $patient->update($request->validated());

        return redirect()->route('patients.show', $patient->id);
    }

    public function submit(Patient $patient)
    {
        $patient->update(['status' => Patient::STATUS_CHECKED]);

        return redirect()->route('patients.show', $patient->id);
    }

    public function print(Patient $patient)
    {
        $patient->load('doctor');

        return inertia('Patients/Print', [
            'currentDate' => now()->format('d.m.Y'),
            'patient' => [
                'id' => $patient->id,
                'hashid' => $patient->hashid,
                'name' => $patient->name,
                'birthday' => $patient->birthday->format('d.m.Y'),
                'gender' => $patient->gender,
                'sampling_date' => $patient->sampling_date->format('d.m.Y H:i'),
                'sample_receipt_date' => $patient->sample_receipt_date->format('d.m.Y H:i'),
                'anamnes' => $patient->anamnes,
                'doctor' => $patient->doctor->name,
                'case_numbers' => $patient->case_numbers,
                'categories' => implode(', ', $patient->categories_formatted),
                'microscopic_description' => $patient->microscopic_description,
                'diagnosis' => $patient->diagnosis,
                'note' => $patient->note,
                'note_text_count' => strlen(strip_tags($patient->note ?? '')),
                'status' => $patient->status,
                'place_of_residence' => $patient->place_of_residence,
                'print_date' => $patient->print_date?->format('d.m.Y'),
                'created_at' => $patient->created_at->format('d.m.Y'),
            ],
        ]);
    }

    private function findDoctorOrCreateByName(int|string $nameOrId, ?string $phone = null)
    {
        if (is_numeric($nameOrId)) {
            return Doctor::findOrFail($nameOrId)->id;
        }

        if ($doctor = Doctor::where('name', 'LIKE', '%'.$nameOrId.'%')->first()) {
            return $doctor->id;
        }

        return Doctor::create(['name' => $nameOrId, 'phone' => $phone])->id;
    }

    private function findClinicOrCreateByName(int|string $nameOrId)
    {
        if (is_numeric($nameOrId)) {
            return MedicalClinic::findOrFail($nameOrId)->id;
        }

        if ($clinic = MedicalClinic::where('name', 'LIKE', '%'.$nameOrId.'%')->first()) {
            return $clinic->id;
        }

        return MedicalClinic::create(['name' => $nameOrId])->id;
    }

    public function publicShow(string $hash)
    {
        $patient = Patient::where('hashid', $hash)->firstOrFail();

        return Pdf::loadView(
            'pdf.patient', compact('patient')
        )->stream();
    }

    public function editPrintDate(Patient $patient, PrintDateRequest $request)
    {
        $patient->update($request->validated());

        return back();
    }

    public function deletePhoto(Patient $patient, int $photo)
    {
        $photo = $patient->photos()->whereId($photo)->firstOrFail();

        $photo->delete();

        Storage::disk('public')->delete($photo->url ?? '');

        return back();
    }

    public function checkResult()
    {
        $patient = Patient::where('uniq_code', request('code'))->first();

        if (is_null($patient)) {
            throw ValidationException::withMessages([
                'code' => 'Введите корректный код.',
            ]);
        }

        if ($patient->status !== Patient::STATUS_CHECKED) {
            throw ValidationException::withMessages([
                'code' => 'Ваше заключение еще не готово.',
            ]);
        }

        abort(409, '', ['X-Inertia-Location' => url()->route('patients.public_show', $patient->hashid)]);
    }

    private function uploadPhotos($patient, $request)
    {
        if ($photos = $request->file('photos')) {
            $uploadedPhotos = [];

            foreach ($photos as $photo) {
                $photo = $patient->photos()->create(['url' => $photo?->store('photos', 'public')]);

                $uploadedPhotos[] = $photo;
            }

            //  ResizePatientPhotos::dispatch($uploadedPhotos);
        }
    }
}
