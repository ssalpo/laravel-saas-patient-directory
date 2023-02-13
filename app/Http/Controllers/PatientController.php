<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Http\Requests\PatientResultRequest;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::filter()
            ->orderBy('created_at', 'DESC')
            ->paginate(100)
            ->through(fn($patient) => [
                'id' => $patient->id,
                'name' => $patient->name,
                'case_numbers' => implode(', ', $patient->case_numbers)
            ]);

        return inertia('Patients/Index', compact('patients'));
    }

    public function create()
    {
        $doctors = Doctor::all()->transform(fn($doctor) => ['id' => $doctor->id, 'name' => $doctor->name]);

        return inertia('Patients/Edit', compact('doctors'));
    }

    public function store(PatientRequest $request)
    {
        $patient = DB::transaction(function () use ($request) {
            $patient = Patient::create($request->validated());

            $patient->generateCaseNumbers();

            $this->uploadPhotos($patient, $request);

            return $patient;
        });

        return redirect()->route('patients.show', $patient->id);
    }

    public function show(Patient $patient)
    {
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
                'photos' => $patient->photos->pluck('url')
            ]
        ]);
    }

    public function edit(Patient $patient)
    {
        $doctors = Doctor::all()->transform(fn($doctor) => ['id' => $doctor->id, 'name' => $doctor->name]);

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
                'doctor_id' => $patient->doctor_id,
                'categories' => $patient->categories
            ]]);
    }

    public function update(Patient $patient, PatientRequest $request)
    {
        $patient = DB::transaction(function () use ($patient, $request) {
            $patient->update($request->validated());

            $patient->generateCaseNumbers();

            $this->uploadPhotos($patient, $request);

            return $patient;
        });

        return redirect()->route('patients.show', $patient->id);
    }

    public function storeResults(Patient $patient, PatientResultRequest $request)
    {
        $patient->update($request->validated());
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

    private function uploadPhotos($patient, $request)
    {
        if ($photos = $request->file('photos')) {
            foreach ($photos as $photo) {
                $patient->photos()->create(['url' => $photo?->store('photos', 'public')]);
            }
        }
    }
}
