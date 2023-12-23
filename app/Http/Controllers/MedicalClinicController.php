<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicalClinicRequest;
use App\Http\Resources\MedicalClinicResource;
use App\Models\MedicalClinic;
use App\Services\MedicalClinicService;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class MedicalClinicController extends Controller
{
    public function __construct(
        protected readonly MedicalClinicService $medicalClinicService
    ) {
        $this->middleware('can:read_medical_clinics')->only('index');
        $this->middleware('can:edit_medical_clinics')->only(['edit', 'update']);
        $this->middleware('can:create_medical_clinics')->only(['create', 'store']);
    }

    /**
     * Возвращает список медицинских учреждений
     */
    public function index(): Response
    {
        $medicalClinics = MedicalClinicResource::collection(
            MedicalClinic::paginate(100)
        );

        return inertia('MedicalClinics/Index', compact('medicalClinics'));
    }

    /**
     * Возвращает форму создания медицинского учреждения
     */
    public function create(): Response
    {
        return inertia('MedicalClinics/Edit');
    }

    /**
     * Добавляет новое медицинское учреждение
     */
    public function store(MedicalClinicRequest $request): RedirectResponse
    {
        $this->medicalClinicService->store($request->validated());

        return redirect()->route('medical-clinics.index');
    }

    /**
     * Возвращает форму редактирования медицинского учреждения
     */
    public function edit(MedicalClinic $medicalClinic): Response
    {
        return inertia('MedicalClinics/Edit', [
            'medicalClinic' => MedicalClinicResource::make($medicalClinic),
        ]);
    }

    /**
     * Обновляет данные медицинского учреждения
     */
    public function update(MedicalClinic $medicalClinic, MedicalClinicRequest $request): RedirectResponse
    {
        $medicalClinic->update($request->validated());

        return redirect()->route('medical-clinics.index');
    }
}
