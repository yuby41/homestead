<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predicción de Partidos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center">Predicción de Partidos</h2>

        <div class="card p-4 shadow">
            <form action="{{ route('predict.process') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="home_odds" class="form-label">Cuota Casa</label>
                    <input type="number" step="0.01" name="home_odds" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="away_odds" class="form-label">Cuota Visitante</label>
                    <input type="number" step="0.01" name="away_odds" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="draw_odds" class="form-label">Cuota Empate</label>
                    <input type="number" step="0.01" name="draw_odds" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Predecir Resultado</button>
            </form>
        </div>

        @if(isset($prediction))
            <div class="alert alert-info mt-4">
                <h4>Resultado de la Predicción:</h4>
                <p>{{ $prediction['prediction'] }}</p>
            </div>
        @endif
    </div>
</body>
</html>
