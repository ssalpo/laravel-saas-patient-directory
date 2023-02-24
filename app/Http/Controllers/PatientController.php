<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Http\Requests\PatientReportRequest;
use App\Models\Doctor;
use App\Models\Patient;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read_all_patients')->only('all');
        $this->middleware('can:create_patients')->only(['create', 'store']);
        $this->middleware('can:edit_patients,read_all_patients')->only(['edit', 'update']);
        $this->middleware('can:add_report')->only(['saveReport', 'submit']);
    }

    public function all()
    {
        $patients = Patient::filter()
            ->orderBy('created_at', 'DESC')
            ->paginate(100)
            ->through(fn($patient) => [
                'id' => $patient->id,
                'name' => $patient->name,
                'status' => $patient->status,
                'case_numbers' => implode(', ', $patient->case_numbers)
            ]);

        $firstItem = $patients->firstItem();

        return inertia('Patients/All', compact('patients', 'firstItem'));
    }

    public function index()
    {
        $patients = Patient::my('created_by')
            ->filter()
            ->orderBy('created_at', 'DESC')
            ->paginate(100)
            ->through(fn($patient) => [
                'id' => $patient->id,
                'name' => $patient->name,
                'status' => $patient->status,
                'case_numbers' => implode(', ', $patient->case_numbers)
            ]);

        return inertia('Patients/Index', compact('patients'));
    }

    public function create()
    {
        $doctors = Doctor::pluck('name', 'id');

        return inertia('Patients/Edit', compact('doctors'));
    }

    public function store(PatientRequest $request)
    {
        $patient = DB::transaction(function () use ($request) {
            $doctor = $request->getDoctor();

            $patient = Patient::create($request->validated() + ['doctor_id' => $this->findDoctorOrCreateByName($doctor, $request->doctor_phone)]);

            $patient->generateCaseNumbers();

            $this->uploadPhotos($patient, $request);

            return $patient;
        });

        return redirect()->route('patients.show', $patient->id)->with('isCreated', true);
    }

    public function show(int $id)
    {
        $patient = Patient::myByPermission()->findOrFail($id);

        return inertia('Patients/Show', [
            'patient' => [
                'id' => $patient->id,
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
                'status' => $patient->status,
                'photos' => $patient->photos->map(
                    fn($photo) => $photo->has_thumb ? 'thumb/' . $photo->url : $photo->url
                )
            ]
        ]);
    }

    public function edit(int $id)
    {
        $patient = Patient::myByPermission()->findOrFail($id);

        $doctors = Doctor::pluck('name', 'id');

        return inertia('Patients/Edit', [
            'id' => $patient->id,
            'doctors' => $doctors,
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
                'categories' => $patient->categories
            ]]);
    }

    public function update(int $id, PatientRequest $request)
    {
        $patient = DB::transaction(function () use ($id, $request) {
            $patient = Patient::myByPermission()->findOrFail($id);

            $doctor = $request->getDoctor();

            $patient->update($request->validated() + ['doctor_id' => $this->findDoctorOrCreateByName($doctor, $request->doctor_phone)]);

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

    public function submit(Patient $patient)
    {
        $patient->update(['status' => Patient::STATUS_CHECKED]);

        return redirect()->route('patients.show', $patient->id);
    }

    public function print(Patient $patient)
    {
        $patient->load('doctor');

        return inertia('Patients/Print', [
            'currentDate' => $patient->created_at->format('d.m.Y'),
            'qrCode' => (string) QrCode::size(100)->generate(route('patients.public_show', $patient->hashid)),
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
                'status' => $patient->status
            ]
        ]);
    }

    private function findDoctorOrCreateByName(int|string $nameOrId, ?string $phone = null)
    {
        if (is_numeric($nameOrId)) {
            return Doctor::findOrFail($nameOrId)->id;
        }

        if ($doctor = Doctor::where('name', 'LIKE', '%' . $nameOrId . '%')->first()) {
            return $doctor->id;
        }

        return Doctor::create(['name' => $nameOrId, 'phone' => $phone])->id;
    }

    public function publicShow(string $hash)
    {
        $patient = Patient::where('hashid', $hash)->firstOrFail();

        return Pdf::loadView(
            'pdf.patient', compact('patient')
        )->stream();
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
