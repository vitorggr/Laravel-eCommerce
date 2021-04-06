
<div class="home">

    <!-- Home Slider -->

    <div class="home_slider_container">
        <div class="owl-carousel owl-theme home_slider">

            <!-- Home Slider Item -->
            @foreach($carrossel as $item)
            <div class="owl-item">
                <a href="{{$item->link}}">
                <div class="home_slider_background" style="background-image:url({{$item->imagem}})"></div>
                <div class="home_slider_content">
                    <div class="home_slider_content_inner">
                        <div class="home_slider_subtitle">{{$item->subtitulo}}</div>
                        <div class="home_slider_title">
                        {{$item->titulo}}
                        </div>
                    </div>
                </div>
                </a>
            </div>
            @endforeach

      

        </div>

        <!-- Home Slider Nav -->

        <div class="home_slider_next d-flex flex-column align-items-center justify-content-center"><img src="images/arrow_r.png" alt=""></div>

        <!-- Home Slider Dots -->

        <div class="home_slider_dots_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="home_slider_dots">
                            <ul id="home_slider_custom_dots" class="home_slider_custom_dots">
                                @foreach($carrossel as $item)
                                <li class="home_slider_custom_dot active">{{$item->cont}}<div></div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    