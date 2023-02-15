<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorRequest;
use App\Models\Doctor;

class DoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read_doctors')->only('index');
        $this->middleware('can:edit_doctors')->only(['edit', 'update']);
        $this->middleware('can:create_doctors')->only(['create', 'store']);
    }

    public function index()
    {
        $doctors = Doctor::paginate(100);

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
}
