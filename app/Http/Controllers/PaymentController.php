<?php

namespace App\Http\Controllers;

use App\Http\Resources\DoctorResource;
use App\Http\Resources\PaymentResource;
use App\Models\Doctor;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class PaymentController extends Controller
{
    public function __construct(
        protected readonly PaymentService $paymentService
    ) {
        $this->middleware('can:payments_manage');
    }

    /**
     * Возвращает список докторов
     */
    public function index(): Response
    {
        $doctors = DoctorResource::collection(
            Doctor::query()
                ->withCount('notPaidPatients')
                ->withCount('paidPatients')
                ->orderByDesc('not_paid_patients_count')
                ->get()
        );

        return inertia('Payments/Index', compact('doctors'));
    }

    /**
     * Создает платеж для всех неоплаченных пациентов доктора
     */
    public function store(Request $request): RedirectResponse
    {
        $this->paymentService->store($request->doctor_id);

        return back();
    }

    /**
     * Возвращает историю выплат доктору
     */
    public function show(Doctor $doctor): Response
    {
        $payments = Payment::whereDoctorId($doctor->id)
            ->orderByDesc('created_at')
            ->with('patient')
            ->paginate(50);

        return inertia('Payments/Show', [
            'payments' => PaymentResource::collection($payments),
            'doctor' => DoctorResource::make($doctor),
        ]);
    }
}
