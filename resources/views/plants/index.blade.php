<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tanaman Hidroponik</title>
    <link rel="stylesheet" href="{{ asset('css/tanaman.css') }}" />
</head>
<body>
<div class="container">
    <a href="{{ url('/') }}" class="back-button">←</a>

    <h1 class="title">What's You Want To Know</h1>
    <div class="grid-container">

        {{-- Loop semua tanaman --}}
        @foreach ($plants as $plant)
    <a href="{{ route('plants.show', $plant->id) }}" class="grid-link">
        <div class="grid-item landscape">
            <img src="{{ asset('image/' . $plant->image) }}" alt="{{ $plant->name }}" />
            <div class="text">
                <h3>{{ $plant->name }}</h3>
                <p>{{ $plant->description }}</p>
                <p><strong>Suhu Ideal:</strong> {{ $plant->suhu }}°C</p>
                <div class="click-indicator">
                    <span>Klik untuk detail →</span>
                </div>
            </div>
        </div>
    </a>
@endforeach

    </div>
</div>

<script src="{{ asset('tanaman.js') }}"></script>
</body>
</html>
