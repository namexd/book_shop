@extends('master')
@section('title', "$product->name")
@section('content')
    <div class="bk_content">
    <!-- Slider main container -->
    <div class="swiper-container">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            @foreach($pdt_images as $pdt_image)
                <div class="swiper-slide" ><img src="{{ $pdt_image->image_path }}" alt=""></div>
            @endforeach
        </div>
        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>

    </div>
    <div class="weui_cells_title">
        <span class="bk_title">{{ $product->name }}</span>
        <span class="bk_price">￥{{ $product->price }}</span>
    </div>
        <div class="weui_cells">
            <div class="weui_cell">
                <p class="bk_summary">
                    {{ $product->summary }}
                </p>
            </div>
        </div>
    <div class="weui_cells_title">详细介绍</div>
    <div class="weui_cells">
        <div class="weui_cell">
            <p>
                {!!  $pdt_content->content  !!}
            </p>
        </div>
    </div>
    </div>

    <div class="bk_fix_bottom">
        <div class="bk_half_area">
            <button class="weui_btn weui_btn_primary">加入购物车</button>
        </div>
        <div class="bk_half_area">
            <button class="weui_btn weui_btn_default">结算</button>
        </div>
    </div>
@endsection
@section('my-js')
    <script src="//cdn.bootcss.com/jquery-weui/1.0.1/js/swiper.min.js"></script>
    <script>
        var mySwiper = new Swiper('.swiper-container', {
            speed: 400,
            spaceBetween: 100,
            autoplay:2000,
            pagination:$(".swiper-pagination"),

        });
    </script>
@endsection