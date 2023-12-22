<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:payments_manage');
    }

    public function index()
    {
        $doctors = Doctor::withCount('notPaidPatients')->get();

        return inertia('Payments/Index', compact('doctors'));
    }

    public function store(Request $request)
    {
        $doctor = Doctor::with('notPaidPatients')->findOrFail($request->doctor_id);

        DB::transaction(
            static fn () => $doctor->notPaidPatients->each(
                fn ($patient) => $patient->payment()
                    ->create(['amount' => Payment::DEFAULT_AMOUNT, 'doctor_id' => $doctor->id])
            )
        );

        return back();
    }

    public function show(Doctor $doctor)
    {
        $payments = Payment::whereDoctorId($doctor->id)
            ->orderBy('created_at', 'DESC')
            ->with('patient')
            ->paginate(20)
            ->through(fn ($payment) => [
                'patient' => $payment->patient->name,
                'amount' => $payment->amount,
                'created_at' => $payment->created_at->format('d.m.Y'),
            ]);

        $doctor = $doctor->only('id', 'name');

        return inertia('Payments/Show', compact('payments', 'doctor'));
    }
}
