<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Noticias MA</title>
    <link rel="icon" href="{{ asset('images/FES.png') }}" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/uni.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <!-- Link Swiper's CSS -->

</head>
<body>
    <div class="init"></div>

    <div class="container-fluid mt-3 mb-3">
        <h3 class="text-center font-weight-bold text-white">Dashboard em Breve Aqui</h3>
        <img class="img-fluid mx-auto d-block" style="width:40%;height:40%;" src="{{ asset('images/FES.png') }}" alt="FES">
    </div>

    <div class="footer">

        <div class="container-fluid text-center">

            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators text-dark">
                </ol>
                <div class="carousel-inner mb-3" id="carousel_slide_all">

                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Anterior</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true">
                    <!--<img class="flex-right ml-10 mb-5" width="50" height="50" src="{{ asset('images/FES.png') }}" alt="Fes">-->
                  </span>
                  <span class="sr-only">Próximo</span>
                </a>
              </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <!-- NOTE: prior to v2.2.1 tiny-slider.js need to be in <body> -->

    <script src="{{ asset('js/uni.min.js') }}"></script>
</body>
</html>
