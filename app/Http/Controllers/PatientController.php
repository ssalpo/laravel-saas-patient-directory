<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Http\Requests\PatientResultRequest;
use App\Jobs\ResizePatientPhotos;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read_all_patients')->only('all');
        $this->middleware('can:create_patients')->only(['create', 'store']);
        $this->middleware('can:edit_patients,read_all_patients')->only(['edit', 'update']);
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

            $patient = Patient::create($request->validated() + ['doctor_id' => $this->findDoctorOrCreateByName($doctor)]);

            $patient->generateCaseNumbers();

            $this->uploadPhotos($patient, $request);

            return $patient;
        });

        return redirect()->route('patients.show', $patient->id);
    }

    public function show(int $id)
    {
        $patient = Patient::myByPermission()->findOrFail($id);

        return inertia('Patients/Show', [
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->name,
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

            $patient->update($request->validated() + ['doctor_id' => $this->findDoctorOrCreateByName($doctor)]);

            $patient->generateCaseNumbers();

            $this->uploadPhotos($patient, $request);

            return $patient;
        });

        return redirect()->route('patients.show', $patient->id);
    }

    public function editReport(Patient $patient)
    {
        $patientData = [
            'id' => $patient->id,
            'microscopic_description' => $patient->microscopic_description,
            'diagnosis' => $patient->diagnosis,
            'note' => $patient->note,
        ];

        return inertia('Patients/Report', [
            'patient' => $patientData
        ]);
    }

    public function updateReport(Patient $patient, PatientResultRequest $request)
    {
        $patient->update($request->validated() + ['status' => Patient::STATUS_CHECKED]);

        return redirect()->route('patients.show', $patient->id);
    }

    public function print(Patient $patient)
    {
        $patient->load('doctor');

        return inertia('Patients/Print', [
            'currentDate' => date('d.m.Y'),
            'patient' => [
                'id' => $patient->id,
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
            ]
        ]);
    }

    private function findDoctorOrCreateByName(int|string $doctor)
    {
        if (is_numeric($doctor)) {
            return Doctor::findOrFail($doctor)->id;
        }

        return Doctor::where('name', 'LIKE', '%' . $doctor . '%')->firstOrCreate(['name' => $doctor])->id;
    }

    private function uploadPhotos($patient, $request)
    {
        if ($photos = $request->file('photos')) {
            $uploadedPhotos = [];

            foreach ($photos as $photo) {
                $photo = $patient->photos()->create(['url' => $photo?->store('photos', 'public')]);

                $uploadedPhotos[] = $photo;
            }

            ResizePatientPhotos::dispatch($uploadedPhotos);
        }
    }
}
