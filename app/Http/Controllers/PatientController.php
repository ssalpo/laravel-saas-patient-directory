<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Http\Requests\PatientResultRequest;
use App\Models\Category;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
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
        $categories = Category::all();

        return inertia('Patients/Edit', compact('doctors', 'categories'));
    }

    public function store(PatientRequest $request)
    {
        $patient = DB::transaction(function () use ($request) {
            $patient = Patient::create($request->validated());

            $patient->categories()->sync($request->categories);

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
                'categories' => $patient->categories->implode('name', ', '),
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
        $categories = Category::all()->transform(fn($category) => ['id' => $category->id, 'name' => $category->name]);

        return inertia('Patients/Edit', [
            'id' => $patient->id,
            'doctors' => $doctors,
            'categories' => $categories,
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->name,
                'birthday' => $patient->birthday->format('d.m.Y'),
                'gender' => $patient->gender,
                'sampling_date' => $patient->sampling_date->format('d.m.Y H:i'),
                'sample_receipt_date' => $patient->sample_receipt_date->format('d.m.Y H:i'),
                'anamnes' => $patient->anamnes,
                'doctor_id' => $patient->doctor_id,
                'categories' => $patient->categories->pluck('id')
            ]]);
    }

    public function update(Patient $patient, PatientRequest $request)
    {
        $patient = DB::transaction(function () use ($patient, $request) {
            $patient->update($request->validated());

            $patient->categories()->sync($request->categories);

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
        return inertia('Patients/Print', compact('patient'));
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
