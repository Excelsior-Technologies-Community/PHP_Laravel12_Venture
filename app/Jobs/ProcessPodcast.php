<?php

namespace App\Jobs;

use App\Models\Podcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sassnowski\Venture\WorkflowStep; // Add this import

class ProcessPodcast implements ShouldQueue
{
    use Dispatchable, 
        InteractsWithQueue, 
        Queueable, 
        SerializesModels,
        WorkflowStep; // Add this trait

    public function __construct(
        public Podcast $podcast
    ) {}

    public function handle(): void
    {
        // Simulate processing
        sleep(2);
        $this->podcast->update(['is_processed' => true]);
        \Log::info('Podcast processed: ' . $this->podcast->title);
    }
}