@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $practice->name }}</h2>
    <p>{!! nl2br(e($practice->description)) !!}</p>

    <div style="width: 100%; height: 600px;">
        <iframe src="/practices/{{ basename($practice->source_practice) }}/index.html"
                width="100%" height="100%" frameborder="0" allowfullscreen></iframe>
    </div>

    <hr>

    <form action="{{ route('practices.grade', $practice) }}" method="POST" class="mt-4">
        @csrf
        <div class="form-group">
            <label for="score">Calificación (0-100):</label>
            <input type="number" class="form-control" name="score" id="score" min="0" max="100" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Enviar calificación</button>
    </form>
</div>
@endsection
