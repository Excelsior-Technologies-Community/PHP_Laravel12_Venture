<!DOCTYPE html>
<html>
<head>
    <title>Venture Demo - Podcast Workflow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Podcast Publishing Workflow</h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                @if(preg_match('/Workflow ID: (\d+)/', session('success'), $matches))
                    <br>
                    <a href="{{ route('workflow.show', $matches[1]) }}" class="alert-link">View Workflow Status</a>
                @endif
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Add New Podcast</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('podcasts.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Podcast Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload and Process</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Recent Podcasts</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($podcasts as $podcast)
                                <tr>
                                    <td>{{ $podcast->title }}</td>
                                    <td>
                                        @if($podcast->is_processed)
                                            <span class="badge bg-success">Processed</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $podcast->created_at->diffForHumans() }}</td>
                                    <td>
                                        <!-- You might want to store workflow_id in podcasts table to link -->
                                        <!-- For now, this is just a placeholder -->
                                        <span class="text-muted">N/A</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Workflow Information</div>
                    <div class="card-body">
                        <p>When you add a podcast, Venture will automatically:</p>
                        <ol>
                            <li><strong>Process the podcast</strong> (Job 1 - takes 2 seconds)</li>
                            <li><strong>Optimize the podcast</strong> (Job 2 - runs in parallel with Job 1)</li>
                            <li><strong>Create audio transcription</strong> (depends on Job 1 - takes 3 seconds)</li>
                            <li><strong>Notify subscribers</strong> (depends on Jobs 2 and 3 - takes 1 second)</li>
                        </ol>
                        <p class="text-muted">Check the Laravel log (<code>storage/logs/laravel.log</code>) to see the job execution order!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>