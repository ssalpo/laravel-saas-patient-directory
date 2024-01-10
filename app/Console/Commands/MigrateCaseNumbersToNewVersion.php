<?php

namespace App\Console\Commands;

use App\Models\Patient;
use App\Services\PatientCaseNumberService;
use Illuminate\Console\Command;

class MigrateCaseNumbersToNewVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-case-numbers-to-new-version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(PatientCaseNumberService $service)
    {
        $patients = Patient::all();

        /** @var Patient $patient */
        foreach ($patients as $patient) {
            $service->generate(
                $patient->id,
                $patient->created_at->format('Y'),
                array_column($patient->categories, 'code')
            );
        }
    }
}
