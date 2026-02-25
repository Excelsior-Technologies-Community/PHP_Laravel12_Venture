# PHP_Laravel12_Venture
# Laravel Venture Clean Demo

Project Name: venture-clean-demo

This project demonstrates workflow orchestration in Laravel using the Venture package. It processes podcast publishing tasks using a structured workflow with queued jobs.

---

STEP 1: Create New Laravel Project

```bash
composer create-project laravel/laravel venture-clean-demo
cd venture-clean-demo
```

---

STEP 2: Install Venture Package

```bash
composer require sassnowski/venture
```

---

STEP 3: Publish and Run Migrations

```bash
php artisan vendor:publish --provider="Sassnowski\Venture\VentureServiceProvider"
php artisan queue:table
php artisan migrate
```

---

STEP 4: Configure Environment

Update .env file:

```env
QUEUE_CONNECTION=database
```

---

STEP 5: Create Podcast Model and Migration

```bash
php artisan make:model Podcast -m
```

Update migration file:

```php
public function up(): void
{
    Schema::create('podcasts', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('file_path')->nullable();
        $table->boolean('is_processed')->default(false);
        $table->unsignedBigInteger('workflow_id')->nullable();
        $table->timestamps();
    });
}
```

Run migration:

```bash
php artisan migrate
```

---

STEP 6: Create Jobs

```bash
php artisan make:job ProcessPodcast
php artisan make:job OptimizePodcast
php artisan make:job CreateAudioTranscription
php artisan make:job NotifySubscribers
```

Each job must:

* Implement ShouldQueue
* Use Dispatchable, InteractsWithQueue, Queueable, SerializesModels
* Use WorkflowStep trait

Example structure:

```php
use Sassnowski\Venture\WorkflowStep;

class ProcessPodcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, WorkflowStep;

    public function handle(): void
    {
        sleep(2);
        \Log::info('Podcast processed');
    }
}
```

Repeat similar structure for remaining jobs.

---

STEP 7: Create Workflow Class

Create file:

app/Workflows/PublishPodcastWorkflow.php

```php
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
        public Podcast $podcast
    ) {}

    public function definition(): WorkflowDefinition
    {
        return $this->define('Publish Podcast: ' . $this->podcast->title)
            ->addJob(new ProcessPodcast($this->podcast))
            ->addJob(new OptimizePodcast($this->podcast))
            ->addJob(new CreateAudioTranscription($this->podcast), [
                ProcessPodcast::class,
            ])
            ->addJob(new NotifySubscribers($this->podcast), [
                OptimizePodcast::class,
                CreateAudioTranscription::class,
            ]);
    }
}
```

---

STEP 8: Create Controller

```bash
php artisan make:controller PodcastController
```

Controller responsibilities:

* List podcasts
* Create podcast
* Start workflow
* Show workflow status

Workflow is started using:

```php
$workflow = PublishPodcastWorkflow::start($podcast);
$podcast->update(['workflow_id' => $workflow->id]);
```

---

STEP 9: Create Views

Create directory:

resources/views/podcasts

Create:

* index.blade.php
* workflow.blade.php

Views should:

* Allow podcast creation
* Display workflow status
* Show job progress
* Auto refresh until workflow finishes

---

STEP 10: Define Routes

Update routes/web.php:

```php
use App\Http\Controllers\PodcastController;

Route::get('/', [PodcastController::class, 'index'])->name('podcasts.index');
Route::post('/podcasts', [PodcastController::class, 'store'])->name('podcasts.store');
Route::get('/workflow/{workflow}', [PodcastController::class, 'showWorkflow'])->name('podcasts.workflow');
```

---

STEP 11: Run the Application

Terminal 1:

```bash
php artisan queue:work
```

Terminal 2:

```bash
php artisan serve
```
<img width="1726" height="705" alt="image" src="https://github.com/user-attachments/assets/a7dd41f0-67da-418f-91e5-8265990cbb8c" />

---

STEP 12: Test the Application

1. Visit [http://localhost:8000](http://localhost:8000)
2. Add a podcast title
3. Observe queue worker processing jobs
4. View workflow progress
5. Monitor logs:

```bash
tail -f storage/logs/laravel.log
```

---

PROJECT FEATURES

* Structured job workflow orchestration
* Parallel and dependent job execution
* Database queue integration
* Workflow progress tracking
* Clean route model binding
* Eager loading of workflow jobs
* Clear job dependency definition

---

LEARNING OUTCOMES

* Implement workflow systems in Laravel
* Manage complex job dependencies
* Use database queues effectively
* Track asynchronous job execution
* Build production ready workflow architecture

