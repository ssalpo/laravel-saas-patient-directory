<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordFirstlyRequest;
use App\Services\ResetPasswordService;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class ResetPasswordFirstlyController extends Controller
{
    public function __construct(
        protected readonly ResetPasswordService $resetPasswordService
    ) {
        $this->middleware(['auth:sanctum', 'user.activity.check', 'check.password.already.reset']);
    }

    public function create(): Response
    {
        return inertia('Auth/ResetPasswordFirstly');
    }

    public function store(ResetPasswordFirstlyRequest $request): RedirectResponse
    {
        $this->resetPasswordService->firstly(
            auth()->id(),
            $request->post('password')
        );

        return to_route('patients.index');
    }
}
