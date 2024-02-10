<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientShareRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Services\PatientService;
use Inertia\Response;

class PatientShareController extends Controller
{
    public function __construct(
        protected readonly PatientService $patientService
    ) {
    }

    public function index(): Response
    {
        $patients = Patient::sharedToMe(auth()->id(), 'created_by')
            ->filter(request()?->all())
            ->withCount('currentUserConsultations')
            ->orderByDesc('created_at')
            ->get();

        $this->patientService->markSharedAsViewed($patients->modelKeys());

        return inertia('PatientShares/Index', [
            'filterParams' => request()?->all(),
            'patients' => PatientResource::collection($patients),
        ]);
    }

    public function store(PatientShareRequest $request): \Illuminate\Http\Response
    {
        $this->patientService->share($request->validated());

        return response()->noContent();
    }
}
