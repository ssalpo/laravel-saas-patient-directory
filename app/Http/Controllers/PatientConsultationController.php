<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientConsultationRequest;
use App\Services\PatientConsultationService;
use Illuminate\Http\RedirectResponse;

class PatientConsultationController extends Controller
{
    public function __construct(
        protected readonly PatientConsultationService $patientConsultationService
    ) {
    }

    public function store(PatientConsultationRequest $request): RedirectResponse
    {
        $this->patientConsultationService->store($request->validated());

        return back();
    }
}
