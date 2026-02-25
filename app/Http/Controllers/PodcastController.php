<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use App\Workflows\PublishPodcastWorkflow;
use Illuminate\Http\Request;
use Sassnowski\Venture\Models\Workflow; // Add this import for the status method

class PodcastController extends Controller
{
    public function index()
    {
        $podcasts = Podcast::all();
        return view('podcasts', compact('podcasts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $podcast = Podcast::create([
            'title' => $request->title,
            'file_path' => 'podcasts/sample.mp3',
            'is_processed' => false,
        ]);

        // Start the workflow
        $workflow = PublishPodcastWorkflow::start($podcast);

        // Fix: Use $workflow->id instead of $workflow->getId()
        return redirect()->route('podcasts.index')
            ->with('success', 'Podcast uploaded and workflow started! Workflow ID: ' . $workflow->id);
    }

    // Add this method to show workflow status
    public function showWorkflow($workflowId)
    {
        $workflow = Workflow::with('jobs')->findOrFail($workflowId);
        
        return view('workflow-status', [
            'workflow' => $workflow,
            'jobs' => $workflow->jobs,
        ]);
    }
}