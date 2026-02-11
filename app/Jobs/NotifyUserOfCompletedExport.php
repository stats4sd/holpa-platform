<?php

namespace App\Jobs;

use App\Models\Team;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class NotifyUserOfCompletedExport implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(Team $team, public string $exportPath)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $exportUrl = Storage::url($this->exportPath);

        Notification::make()
            ->title('Data Export Ready')
            ->body("The data export you requested is ready. You can download it from <a href='{$exportUrl}' class='underline'>here</a>.")
            ->success()
            ->broadcast($this->team->users)
            ->send();
    }
}
