<?php

use App\Http\Controllers\PodcastController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PodcastController::class, 'index'])->name('podcasts.index');
Route::post('/podcasts', [PodcastController::class, 'store'])->name('podcasts.store');
Route::get('/workflow/{workflowId}', [PodcastController::class, 'showWorkflow'])->name('workflow.show');