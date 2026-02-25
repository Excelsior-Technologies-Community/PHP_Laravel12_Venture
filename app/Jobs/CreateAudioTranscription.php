<?php

namespace App\Jobs;

use App\Models\Podcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sassnowski\Venture\WorkflowStep; // Add this import

class CreateAudioTranscription implements ShouldQueue
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
        sleep(3);
        \Log::info('Audio transcription created for: ' . $this->podcast->title);
    }
}