<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="{{ asset('css/owl.carousel.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/owl.theme.default.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custStyle.css') }}" rel="stylesheet">

        <!-- Styles -->
        <style>
            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                margin: 20px;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
        <div class="content">
            <h1>Image Gallery with Laravel and Localstorage</h1>
            <div class="owl-carousel owl-theme dumpImg"></div>
        </div>
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
        <script>
            let owlVar = $('.owl-carousel');
            const owlConfig = {
                items: 1,
                nav: false,
                dots: true,
                margin: 10,
                loop: true,
                autoplay: true,
                autoplayHoverPause: true,
                autoplaySpeed: 200,
                touchDrag: true,
                mouseDrag: true,
            };
            let img = [];

            // -Adding the images to the DOM
            // ------------------------------
            const addImageToDOM = () => {
                const recentImgData = JSON.parse(localStorage.getItem('imageArr'));
                let html = '';

                // -Some Validations to check if the data is present
                // -------------------------------------------------
                if (!recentImgData) return false;
                if (recentImgData.length == 0) {
                    $('.item').remove();
                    localStorage.removeItem('imageArr');
                    img = [];
                    return false;
                }

                // -Appending the images to the Carousel
                // -------------------------------------
                recentImgData.map(({imgName}, idx) => {
                    html += `<div class="item">`;
                    html += `<img src="${imgName}" alt="image_${idx}" data-idx="${idx}" class="imageItem" style="height: 80vh;">`;
                    html += `</div>`;
                    $('.dumpImg').html(html);
                });

                // -Re-INITing the carousel
                // -------------------------
                owlVar.owlCarousel('destroy');
                owlVar.owlCarousel(owlConfig);
            }

            window.onload = () => addImageToDOM();
        </script>
    </body>
</html>
