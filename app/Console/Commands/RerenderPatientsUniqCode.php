<?php

namespace App\Console\Commands;

use App\Models\Patient;
use Illuminate\Console\Command;

class RerenderPatientsUniqCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rerender:patients:uniqcode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rerender patients uniq code number';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $patients = Patient::whereNull('uniq_code')->get();

        /** @var Patient $patient */
        foreach ($patients as $patient) {
            $patient->generateUniqCode();
        }

        return Command::SUCCESS;
    }
}
