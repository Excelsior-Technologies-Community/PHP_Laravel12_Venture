<?php

namespace App\Workflows;

use App\Jobs\CreateAudioTranscription;
use App\Jobs\NotifySubscribers;
use App\Jobs\OptimizePodcast;
use App\Jobs\ProcessPodcast;
use App\Models\Podcast;
use Sassnowski\Venture\AbstractWorkflow;
use Sassnowski\Venture\WorkflowDefinition;

class PublishPodcastWorkflow extends AbstractWorkflow
{
    public function __construct(
        private Podcast $podcast
    ) {}

    public function definition(): WorkflowDefinition
    {
        return $this->define('Publish Podcast: ' . $this->podcast->title)
            ->addJob(new ProcessPodcast($this->podcast))
            ->addJob(new OptimizePodcast($this->podcast))
            ->addJob(new CreateAudioTranscription($this->podcast), [
                ProcessPodcast::class, // Depends on ProcessPodcast
            ])
            ->addJob(new NotifySubscribers($this->podcast), [
                OptimizePodcast::class,
                CreateAudioTranscription::class, // Depends on both jobs
            ]);
    }
}