<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Noticias SEPLAN - MA</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <!-- Link Swiper's CSS -->

</head>
<body>
    <div class="init"></div>
    <hr class="divider"/>

        <div class="container">

            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators text-dark">
                </ol>
                <div class="carousel-inner" id="carousel_slide">
        
                  <!--<div class="carousel-item active">
                    <img class="d-block w-100" src="https://c.pxhere.com/photos/27/7b/scotland_centralscotland_benmore_stobbinnein_crianlarich_scotspine_mountains_landscape-267332.jpg!d" alt="Primeiro Slide">
                  </div>-->

                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Anterior</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Próximo</span>
                </a>
              </div>
        </div>
        
    <hr class="divider"/>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <!-- NOTE: prior to v2.2.1 tiny-slider.js need to be in <body> -->

    <script src="{{ asset('js/style.min.js') }}"></script>
</body>
</html>