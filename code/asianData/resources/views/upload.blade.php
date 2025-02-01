<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Upload an Image for Goal Prediction</h1>
        <form action="{{ route('upload.image') }}" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf
            <div class="mb-3">
                <label for="image" class="form-label">Select Image</label>
                <input type="file" name="image" id="image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload and Predict</button>
        </form>
        @if (session('predictions'))
            <div class="mt-4">
                <h3>Predictions</h3>
                <ul>
                    @foreach (session('predictions') as $prediction)
                        <li>{{ $prediction['prediction'] }} with confidence: {{ $prediction['confidence'] }}%</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</body>
</html>
