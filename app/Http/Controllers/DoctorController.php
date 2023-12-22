<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorRequest;
use App\Models\Doctor;
use App\Models\Patient;

class DoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read_doctors')->only('index', 'patients');
        $this->middleware('can:edit_doctors')->only(['edit', 'update']);
        $this->middleware('can:create_doctors')->only(['create', 'store']);
        $this->middleware('can:delete_doctors')->only(['destroy']);
    }

    public function index()
    {
        $doctors = Doctor::withCount('patients')->paginate(100);

        return inertia('Doctors/Index', compact('doctors'));
    }

    public function create()
    {
        return inertia('Doctors/Edit');
    }

    public function store(DoctorRequest $request)
    {
        Doctor::create($request->validated());

        return redirect()->route('doctors.index');
    }

    public function edit(Doctor $doctor)
    {
        return inertia('Doctors/Edit', compact('doctor'));
    }

    public function update(Doctor $doctor, DoctorRequest $request)
    {
        $doctor->update($request->validated());

        return redirect()->route('doctors.index');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->loadCount('patients');

        if ($doctor->patients_count) {
            return redirect()->back()->with('message', 'Невозможно удалить, переместите пациентов врача на другого специалиста!');
        }

        $doctor->delete();

        return redirect()->route('doctors.index');
    }

    public function patients(Doctor $doctor)
    {
        $patients = Patient::whereDoctorId($doctor->id)
            ->orderBy('created_at', 'DESC')
            ->paginate(100)
            ->through(fn ($patient) => [
                'id' => $patient->id,
                'name' => $patient->name,
                'status' => $patient->status,
                'case_numbers' => implode(', ', $patient->case_numbers),
            ]);

        return inertia('Doctors/Patients', compact('doctor', 'patients'));
    }
}
