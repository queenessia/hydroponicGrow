<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scrollable Pages</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
    <section class="page-section">
            @include('navbar') <!-- Bagian pertama -->
        </section>
        <section class="page-section">
            @include('homepage') <!-- Bagian pertama -->
        </section>

        <section class="page-section">
            @include('article') <!-- Bagian kedua -->
        </section>

        <section class="page-section">
            @include('video') <!-- Bagian ketiga -->
        </section>

        
    </div>
</body>
</html>
