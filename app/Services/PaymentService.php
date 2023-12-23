<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function store(int $doctorId): void
    {
        $doctor = Doctor::with('notPaidPatients')->findOrFail($doctorId);

        DB::transaction(
            static fn () => $doctor->notPaidPatients->each(
                fn ($patient) => $patient->payment()
                    ->create(['amount' => Payment::DEFAULT_AMOUNT, 'doctor_id' => $doctorId])
            )
        );
    }
}
