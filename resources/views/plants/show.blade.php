<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $plant->name }} - Tanaman Hidroponik</title>
    <link rel="stylesheet" href="{{ asset('css/plant-detail.css') }}" />
</head>
<body>
    <div class="container">
        <a href="{{ route('plants.index') }}" class="back-button">← Kembali</a>

        <div class="plant-detail">
            <div class="plant-header">
                <div class="plant-image">
                    <img src="{{ asset('image/' . $plant->image) }}" alt="{{ $plant->name }}" />
                </div>
                <div class="plant-info">
                    <h1>{{ $plant->name }}</h1>
                    <p class="description">{{ $plant->description }}</p>
                    <div class="temperature">
                        <span class="temp-label">Suhu Ideal:</span>
                        <span class="temp-value">{{ $plant->suhu }}°C</span>
                    </div>
                </div>
            </div>

            <div class="detail-content">
                {{-- Cara Menanam --}}
                <div class="section">
                    <h2>Cara Menanam {{ $plant->name }} Hidroponik</h2>
                    <ol>
                        @foreach(explode("\n", $plant->cara_menanam) as $step)
                            <li>{{ $step }}</li>
                        @endforeach
                    </ol>
                </div>

                {{-- Kebutuhan Lingkungan --}}
                <div class="section">
                    <h2>Kebutuhan Lingkungan</h2>
                    <div class="requirements">
                        @foreach(explode("\n", $plant->kebutuhan_lingkungan) as $req)
                            <div class="req-item">{!! $req !!}</div>
                        @endforeach
                    </div>
                </div>

                {{-- Waktu Panen --}}
                <div class="section">
                    <h2>Waktu Panen</h2>
                    <p>{{ $plant->waktu_panen }}</p>
                </div>

                {{-- Tips Perawatan --}}
                <div class="section">
                    <h2>Tips Perawatan</h2>
                    <ul>
                        @foreach(explode("\n", $plant->tips_perawatan) as $tip)
                            <li>{{ $tip }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
