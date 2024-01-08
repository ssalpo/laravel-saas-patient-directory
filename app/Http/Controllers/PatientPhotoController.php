<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientPhotoRequest;
use App\Services\PatientPhotoService;
use Illuminate\Http\RedirectResponse;

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
}
