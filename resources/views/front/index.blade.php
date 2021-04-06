@extends('layouts.front.app')

@section('og')
<meta property="og:type" content="home" />
<meta property="og:title" content="Loja Virtual" />
<meta property="og:description" content="Loja Virtual" />
<link rel="canonical" href="http://www.easyshop.com.br/" />
@endsection

@section('css')
<?php $directory = null ?>
<link href="{{ asset('css/owl.theme.default.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/animate.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/colorbox.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/main_styles.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/responsive.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/style.min.css') }}" rel="stylesheet" type="text/css">
@endsection
<link href="{{ asset('css/'.$directory.'owl.carousel.css') }}" rel="stylesheet" type="text/css">
@section('content')
@include('layouts.front.home-slider',['carrossel'=>$sliders])
<!-- Promo -->
@include('layouts.errors-and-messages')



@if(sizeof($cards) > 1 )
<div class="promo">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="section_title_container text-center">
                    <div class="section_subtitle">promo</div><br>
                    <div class="section_title">preços promocionais</div>
                </div>
            </div>
        </div>
        <div class="row promo_container">
            @foreach($cards as $card)
            <!-- Promo Item -->

            @if(sizeof($cards) == 2 )
            <div class="col-lg-6 promo_col">
                <div class="promo_item">
                    <div class="promo_image">
                        <img src="{{$card->bannercartao}}" alt="">
                        <div class="promo_content promo_content_1">
                            <div class="promo_title">{{$card->titulo}}</div>
                            <div class="promo_subtitle">{{$card->subtitulo}}</div>
                        </div>
                    </div>
                    <div class="promo_link"><a href="promoção/{{str_slug($card->descricao,'-')}}">Comprar Agora</a></div>
                </div>
            </div>
            @elseif(sizeof($cards) == 3)
            <div class="col-lg-4 promo_col">
                <div class="promo_item">
                    <div class="promo_image">
                        <img src="{{$card->bannercartao}}" alt="">
                        <div class="promo_content promo_content_1">
                            <div class="promo_title">{{$card->titulo}}</div>
                            <div class="promo_subtitle">{{$card->subtitulo}}</div>
                        </div>
                    </div>
                    <div class="promo_link"><a href="promoção/{{str_slug($card->descricao,'-')}}">Comprar Agora</a></div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Arrivals -->
<div class="arrivals">
    <div class="container">
        <div class="col">
            @include('layouts.errors-and-messages')
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <div class="section_title_container text-center">
                    <div class="section_subtitle">Destaques</div>
                    <br>
                    <div class="section_title">Destaques</div>
                </div>
            </div>
        </div>
        <div class="row products_container">

            @include('front.products.product-list', ['products' => $destaques, 'slide'=>1])

            <!-- <div id="browse-all-btn" class="form-group col-md-12"> <button class="btn btn-default browse-all-btn " role="button">Ver todos</button></div> -->

        </div>
        <hr>
        <div class="row">
            <div class="col">
                <div class="section_title_container text-center">
                    <div class="section_subtitle">{{$colecaoThema->descricao}}</div>
                    <br>
                    <div class="section_title">{{$colecaoThema->nome}}</div>
                </div>
            </div>
        </div>
        <div class="row products_container">
            <div class="owl-carousel owl-theme" id="colecao-thema">
                @include('front.products.product-carrosel', ['products' => $colecaoThema->products,'slide'=>2])
            </div>
            <!-- <div id="browse-all-btn" class="form-group col-md-12"> <a class="btn btn-default browse-all-btn " role="button">Ver todos</a></div> -->
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <div class="section_title_container text-center">
                    <div class="section_subtitle">Ofertas</div>
                    <br>
                    <div class="section_title">Ofertas</div>
                </div>
            </div>
        </div>
        <div class="row products_container">
            @include('front.products.product-list', ['products' => $promocoes,'slide'=>2])
            <!-- <div id="browse-all-btn" class="form-group col-md-12"> <a class="btn btn-default browse-all-btn " role="button">Ver todos</a></div> -->
        </div>
    </div>
</div>

<!-- Banner Promo -->

<!-- <div class="extra clearfix">
    <div class="extra_promo extra_promo_1">
        <div class="extra_promo_image" style="background-image:url(images/extra_1.jpg)"></div>
        <div class="extra_1_content d-flex flex-column align-items-center justify-content-center text-center">
            <div class="extra_1_price">30%<span>off</span></div>
            <div class="extra_1_title">On all shoes</div>
            <div class="extra_1_text">*Integer ut imperdiet erat. Quisque ultricies lectus tellus, eu tristique magna pharetra.</div>
            <div class="button extra_1_button"><a href="checkout.html">check out</a></div>
        </div>
    </div>
    <div class="extra_promo extra_promo_2">
        <div class="extra_promo_image" style="background-image:url(images/extra_2.jpg)"></div>
        <div class="extra_2_content d-flex flex-column align-items-center justify-content-center text-center">
            <div class="extra_2_title">
                <div class="extra_2_center">&</div>
                <div class="extra_2_top">Mix</div>
                <div class="extra_2_bottom">Match</div>
            </div>
            <div class="extra_2_text">*Integer ut imperdiet erat. Quisque ultricies lectus tellus, eu tristique magna pharetra.</div>
            <div class="button extra_2_button"><a href="checkout.html">check out</a></div>
        </div>
    </div>
</div> -->

<!-- Instagram Gallery -->

<div class="gallery">
    <div class="gallery_image " style="background-image:url(images/gallery.jpg)"></div>
    <!-- <div class="container">
        <div class="row">
            <div class="col">
                <div class="gallery_title text-center">
                    <ul>
                        <li><a href="#">#wish</a></li>
                        <li><a href="#">#wishinstagram</a></li>
                        <li><a href="#">#wishgirl</a></li>
                    </ul>
                </div>
                <div class="gallery_text text-center">*Integer ut imperdiet erat. Quisque ultricies lectus tellus, eu tristique magna pharetra.</div>
                <div class="button gallery_button"><a href="#">submit</a></div>
            </div>
        </div>
    </div> -->
    <div class="gallery_slider_container">
        <h1 class="section_title title-insta">Instagram</h1>


        <div class="owl-carousel owl-theme" id="instagram-feed-demo">

        </div>
    </div>
</div>

<!-- Testimonials -->

<!-- <div class="testimonials">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="section_title_container text-center">
                    <div class="section_subtitle">only the best</div>
                    <div class="section_title">testimonials</div>
                </div>
            </div>
        </div>
        <div class="row test_slider_container">
            <div class="col">

                 Testimonials Slider 
                <div class="owl-carousel owl-theme test_slider text-center">

                    Testimonial Item 
                    <div class="owl-item">
                        <div class="test_text">“Integer ut imperdiet erat. Quisque ultricies lectus tellus, eu tristique magna pharetra nec. Fusce vel lorem libero. Integer ex mi, facilisis sed nisi ut, vestibulum ultrices nulla. Aliquam egestas tempor leo.”</div>
                        <div class="test_content">
                            <div class="test_image"><img src="images/testimonials.jpg" alt=""></div>
                            <div class="test_name">Christinne Smith</div>
                            <div class="test_title">client</div>
                        </div>
                    </div>

                    Testimonial Item 
                    <div class="owl-item">
                        <div class="test_text">“Integer ut imperdiet erat. Quisque ultricies lectus tellus, eu tristique magna pharetra nec. Fusce vel lorem libero. Integer ex mi, facilisis sed nisi ut, vestibulum ultrices nulla. Aliquam egestas tempor leo.”</div>
                        <div class="test_content">
                            <div class="test_image"><img src="images/testimonials.jpg" alt=""></div>
                            <div class="test_name">Christinne Smith</div>
                            <div class="test_title">client</div>
                        </div>
                    </div>

                    Testimonial Item
                    <div class="owl-item">
                        <div class="test_text">“Integer ut imperdiet erat. Quisque ultricies lectus tellus, eu tristique magna pharetra nec. Fusce vel lorem libero. Integer ex mi, facilisis sed nisi ut, vestibulum ultrices nulla. Aliquam egestas tempor leo.”</div>
                        <div class="test_content">
                            <div class="test_image"><img src="images/testimonials.jpg" alt=""></div>
                            <div class="test_name">Christinne Smith</div>
                            <div class="test_title">client</div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div> -->

<!-- Newsletter -->


<!-- @include('front.newsletter.index') -->
@endsection


@section('js')
<script src="{{ asset('js/jquery.star-rating-svg.js') }}"></script>
<script src="{{ asset('js/jquery.instagramFeed.js')}}"></script>
<script>
    $(window).on('load', function() {

        $('#colecao-thema').owlCarousel({
            loop: true,
            margin: 10,
            nav: false,
            items: 5,
            autoHeight: false,
            responsive: {
                // breakpoint from 0 up
                0: {
                    items: 1,
                },
                768: {
                    items: 3,
                },
                1200: {
                    items: 5,
                }
            },
            autoplay: true,
            autoplayTimeout: 1500,
        });

        $.instagramFeed({
            'username': 'flechedorbrasil',
            'callback': function(data) {

                var midias = data.edge_owner_to_timeline_media.edges

                // console.log(JSON.stringify(midias));

                midias.forEach(foto => {
                    var url_photo = foto.node.display_url
                    var post_photo = foto.node.shortcode
                    var type = foto.node.product_type

                    if (!type && !foto.node.is_video) {
                        $("#instagram-feed-demo").append(`<div class="owl-item"><a href="https://www.instagram.com/p/${post_photo}" target="_blank"><img src="${url_photo}" alt=""></a></div>`)
                    }
                });

                $('#instagram-feed-demo').owlCarousel({
                    loop: true,
                    margin: 10,
                    nav: false,
                    items: 5,
                    autoHeight: false,
                    responsive: {
                        // breakpoint from 0 up
                        0: {
                            items: 1,
                        },
                        // breakpoint from 768 up
                        768: {
                            items: 3,
                        },
                        // breakpoint from 768 up
                        1200: {
                            items: 5,
                        }
                    },
                    autoplay: true,
                    autoplayTimeout: 1500,
                    autoplayHoverPause: true,
                })
            }
        });

    });


    $(".product").each(function() {
        $(".my-rating-6", this).starRating({
            totalStars: 5,
            emptyColor: 'lightgray',
            activeColor: '#f5c06f',
            initialRating: $('.rating', this).data("rating"),
            readOnly: true,
            strokeWidth: 0,
            useGradient: false,
            minRating: 0,
        });
    });
</script>
@endsection
<style type="text/css">
    .instagram_username,
    .instagram_biography {
        color: whitesmoke;
    }
</style>