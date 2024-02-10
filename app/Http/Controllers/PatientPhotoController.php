<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientPhotoLabelUpdateRequest;
use App\Http\Requests\PatientPhotoRequest;
use App\Services\PatientPhotoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class PatientPhotoController extends Controller
{
    public function __construct(
        protected readonly PatientPhotoService $patientPhotoService
    ) {
    }

    public function store(int $id, PatientPhotoRequest $request): RedirectResponse
    {
        $this->patientPhotoService->store($id, $request->validated('photos'));

        return back();
    }

    public function destroy(int $id, int $photoId): RedirectResponse
    {
        $this->patientPhotoService->destroy(
            $id, $photoId
        );

        return back();
    }

    public function updateLabel(int $patientId, int $photoId, PatientPhotoLabelUpdateRequest $request): Response
    {
        $this->patientPhotoService->updateLabel(
            $patientId,
            $photoId,
            $request->post('label')
        );

        return response()->noContent();
    }
}
