<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicalClinicRequest;
use App\Models\MedicalClinic;

class MedicalClinicController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read_medical_clinics')->only('index');
        $this->middleware('can:edit_medical_clinics')->only(['edit', 'update']);
        $this->middleware('can:create_medical_clinics')->only(['create', 'store']);
    }

    public function index()
    {
        $medicalClinics = MedicalClinic::paginate(100);

        return inertia('MedicalClinics/Index', compact('medicalClinics'));
    }

    public function create()
    {
        return inertia('MedicalClinics/Edit');
    }

    public function store(MedicalClinicRequest $request)
    {
        MedicalClinic::create($request->validated());

        return redirect()->route('medical-clinics.index');
    }

    public function edit(MedicalClinic $medicalClinic)
    {
        return inertia('MedicalClinics/Edit', compact('medicalClinic'));
    }

    public function update(MedicalClinic $medicalClinic, MedicalClinicRequest $request)
    {
        $medicalClinic->update($request->validated());

        return redirect()->route('medical-clinics.index');
    }
}
