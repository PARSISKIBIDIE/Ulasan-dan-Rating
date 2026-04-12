<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Survey;
use Illuminate\Support\Facades\Log;

class ResetSurveys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'surveys:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all surveys so students can submit again';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count = Survey::count();

        if ($count === 0) {
            $this->info('No surveys to reset.');
            Log::info('surveys:reset - no surveys to reset.');
            return 0;
        }

        // Use query delete so database-level cascades run efficiently
        Survey::query()->delete();

        // Persist last reset info so admin can see it in the dashboard
        $payload = [
            'count' => $count,
            'timestamp' => now()->toDateTimeString(),
        ];
        @file_put_contents(storage_path('app/last_survey_reset.json'), json_encode($payload));

        $this->info("Reset {$count} surveys.");
        Log::info("surveys:reset - reset {$count} surveys.");

        return 0;
    }
}
