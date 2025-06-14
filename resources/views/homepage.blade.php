<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hydroponic Grow</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <script src="{{ asset('js/home.js') }}"></script>

</head>
<body class="page-home">
<!-- Garis hijau TETAP di "Home" -->
<body>

<div class="page-transition"></div>



    <div class="hero-container">
        <section class="hero">
            <div class="hero-content">
                <h1>Selamat Datang di <br> Hydroponic Grow</h1>
                <p>Website panduan budidaya hidroponik dan sharing komunitas</p>
                <p>Baca artikel dan tonton video tentang cara budidaya hidroponik untuk menambah wawasan. Bagikan pengalaman bertanam Anda dengan komunitas, update perkembangan tanaman Anda melalui postingan. Lakukan sharing dan konsultasi langsung dengan para ahli hidroponik hanya di website ini.</p>
                <button class="hero-button">HydroponicGrow</button>
            </div>
        </section>
    </div>

    <!-- Main Content -->
    <div class="main-container">
    <main class="content">
        <p class="intro-text">
            Website panduan budidaya Hidroponik <br> dan sharing komunitas
        </p>
        <h2>What In HydroponicGrow</h2>
        <div class="card-container">
            <div class="card">
                <img src="/image/article.png" alt="Ikon Artikel" class="icon-card">
                <h3>Article</h3>
                <p class="text-card">
                    Baca Artikel tentang penanaman hidroponik
                </p>
                 <a href="#" id="articleLink"class="button-card">Scroll Down</a>
            </div>

            <div class="card">
                <img src="/image/videoUser.png" alt="Ikon Video" class="icon-card">
                <h3>Video</h3>
                <p class="text-card">
                    Tonton video youtube seputar metode hidroponik
                </p>
                <a href="#" id="videoLink"class="button-card">Scroll Down</a>
              </div>  
            <div class="card">
                <img src="/image/totalPost.png" alt="Ikon Sharing" class="icon-card">
                <h3>Sharing</h3>
                <p class="text-card">
                    Sharing pengalaman sesama komunitas hidroponik
                </p>
                <a href="{{ url('/sharing') }}" class="button-card">Let's Share</a>
            </div>

            <div class="card">
                <img src="/image/options.png" alt="Ikon Options" class="icon-card">
                <h3>Options</h3>
                <p class="text-card">Pilih tanaman yang ingin dipelajari</p>
                <a href="{{ url('/tanaman') }}" class="button-card">Explore Now</a>
            </div>            
        </div>
    </main>
    </div>


  
</body>

</html>