<?php

namespace App\Jobs;

use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddUniqCodeForPatient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public Patient $patient
    ) {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PatientService $patientService)
    {
        $patientService->generateUniqAccessCode($this->patient->id);
    }
}
