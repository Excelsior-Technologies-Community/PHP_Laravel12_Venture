<!DOCTYPE html>
<html>
<head>
    <title>Workflow Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Workflow: {{ $workflow->name }}</h1>
        
        <div class="card mt-4">
            <div class="card-header">Status</div>
            <div class="card-body">
                <!-- Fix: Use $workflow->id instead of $workflow->getId() -->
                <p><strong>ID:</strong> {{ $workflow->id }}</p>
                <p><strong>Status:</strong> 
                    @if($workflow->isFinished())
                        <span class="badge bg-success">Completed</span>
                    @else
                        <span class="badge bg-warning">Processing</span>
                    @endif
                </p>
                <p><strong>Created:</strong> {{ $workflow->created_at->format('Y-m-d H:i:s') }}</p>
                <p><strong>Last Updated:</strong> {{ $workflow->updated_at->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">Jobs ({{ $jobs->count() }})</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Job</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Created</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobs as $job)
                        <tr>
                            <td>{{ class_basename($job->job) }}</td>
                            <td>
                                @if($job->isFinished())
                                    <span class="badge bg-success">Completed</span>
                                @elseif($job->hasFailed())
                                    <span class="badge bg-danger">Failed</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($job->isFinished())
                                    100%
                                @elseif($job->hasFailed())
                                    Failed
                                @else
                                    Waiting...
                                @endif
                            </td>
                            <td>{{ $job->created_at->diffForHumans() }}</td>
                            <td>{{ $job->updated_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('podcasts.index') }}" class="btn btn-primary">Back to Podcasts</a>
        </div>
    </div>
</body>
</html>