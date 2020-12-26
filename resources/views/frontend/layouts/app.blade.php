<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- google analytics --}}
    @include('frontend.partials.tracking')

    {!! SEO::generate() !!}

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- favicon -->
    @if($site_global_settings->setting_site_favicon)
        <link rel="icon" type="icon" href="{{ Storage::disk('public')->url('setting/'. $site_global_settings->setting_site_favicon) }}" sizes="96x96"/>
    @else
        <link rel="icon" type="icon" href="{{ asset('favicon-16x16.ico') }}" sizes="16x16"/>
        <link rel="icon" type="icon" href="{{ asset('favicon-32x32.ico') }}" sizes="32x32"/>
        <link rel="icon" type="icon" href="{{ asset('favicon-96x96.ico') }}" sizes="96x96"/>
    @endif

    <!-- font awesome free icons -->
    <link href="{{ asset('backend/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Nanum+Gothic:400,700,800" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('frontend/fonts/icomoon/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/fonts/flaticon/font/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/rangeslider.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/vendor/rateyo/jquery.rateyo.min.css') }}" type="text/css">
    <!-- Custom Style File -->
    <link rel="stylesheet" href="{{ asset('frontend/css/my-style.css') }}">
    @if($site_global_settings->setting_site_header_enabled == \App\Setting::SITE_HEADER_ENABLED)
        {!! $site_global_settings->setting_site_header !!}
    @endif

    @yield('styles')

    @if(is_demo_mode())
        <style>
            #demo-purchase-box {
                width: 75%;
                position: fixed;
                bottom: 25px;
                left: 40px;
                z-index: 5000;
            }
            #demo-purchase-content-box
            {
                background: #009688;
            }
        </style>
    @endif

</head>
<body>

@if(is_demo_mode())
    <div id="demo-purchase-box">
        <div class="row mb-1">
            <div class="col-12 pl-0">
                <a id="demo-purchase-close" class="btn btn-warning text-white rounded" data-toggle="collapse" href="#demo-purchase-content" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <i class="fas fa-chevron-down"></i>
                </a>
            </div>
        </div>
        <div class="row collapse" id="demo-purchase-content">
            <div class="col-sm-10 col-md-3 pt-3 pb-3 rounded" id="demo-purchase-content-box">
                <p class="text-white">
                    {{ __('demo.purchase-desc') }}
                </p>
                <a class="btn btn-warning btn-sm text-white rounded" href="https://codecanyon.net/item/directory-hub-listing-classified-platform/26890239" target="_blank">
                    <i class="fas fa-shopping-cart"></i>
                    {{ __('demo.purchase-button') }}
                </a>
            </div>
        </div>
    </div>
@endif

<div class="site-wrap">

    {{-- nav bar --}}
    @include('frontend.partials.nav')

    {{-- main content --}}
    @yield('content')

    {{-- footer --}}
    @include('frontend.partials.footer')
</div>

<script src="{{ asset('frontend/vendor/pace/pace.min.js') }}"></script>

<script src="{{ asset('frontend/js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery-migrate-3.0.1.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery-ui.js') }}"></script>
<script src="{{ asset('frontend/js/popper.min.js') }}"></script>
<script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.stellar.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('frontend/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('frontend/js/aos.js') }}"></script>
<script src="{{ asset('frontend/js/rangeslider.min.js') }}"></script>
<script src="{{ asset('frontend/js/main.js') }}"></script>
<script src="{{ asset('frontend/js/lib.js') }}"></script>
<script src="{{ asset('frontend/vendor/rateyo/jquery.rateyo.min.js') }}"></script>

@if(is_demo_mode())
<script src="{{ asset('frontend/vendor/js-cookie/js.cookie.js') }}"></script>
@endif

<script>
    $(document).ready(function(){
        @if(is_demo_mode())
        if(Cookies.get('demo_box_show') == undefined)
        {
            console.log("initial set true");
            Cookies.set('demo_box_show', true);
        }
        if(Cookies.get('demo_box_show') == "true")
        {
            console.log(Cookies.get('demo_box_show'));
            console.log("show box");
            $("#demo-purchase-content").collapse('show');
        }
        $('#demo-purchase-close').on('click', function(){
            if($("#demo-purchase-content").is(":visible"))
            {
                console.log("set to false");
                Cookies.set('demo_box_show', false);
            }
            if($("#demo-purchase-content").is(":hidden"))
            {
                console.log("set to true");
                Cookies.set('demo_box_show', true);
                console.log(Cookies.get('demo_box_show'));
            }
        });
        @endif
        /**
         * The front-end form disable submit button UI
         */
        $("form").on("submit", function () {
            $("form :submit").attr("disabled", true);
            return true;
        });
        /**
         * Start initial rating stars for listing box elements
         */
        /*
         * NOTE: You should listen for the event before calling `rateYo` on the element
         *       or use `onInit` option to achieve the same thing
         */
        $(".rating_stars").on("rateyo.init", function (e, data) {
            console.log(e.target.getAttribute('data-id'));
            console.log(e.target.getAttribute('data-rating'));
            console.log("RateYo initialized! with " + data.rating);
            var $rateYo = $("." + e.target.getAttribute('data-id')).rateYo();
            $rateYo.rateYo("rating", e.target.getAttribute('data-rating'));
            /* set the option `multiColor` to show Multi Color Rating */
            $rateYo.rateYo("option", "spacing", "2px");
            $rateYo.rateYo("option", "starWidth", "15px");
            $rateYo.rateYo("option", "readOnly", true);
        });
        $(".rating_stars").rateYo({
            spacing: "2px",
            starWidth: "15px",
            readOnly: true,
            rating: 0
        });
        /**
         * End initial rating stars for listing box elements
         */
        /**
         * Start event for footer language selector modal
         */
        $('#btn-language-selector').on('click', function(){
            $('#language-selector-modal').modal('show');
        });
        /**
         * End event for footer language selector modal
         */
    });
   
    
            $(function () {
            //   alert(1);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
               $('.ras').on('change', function (e) {
                 //  var optionSelected = $("option:selected", this);
                   var optionSelected = $(this);
                   var valueSelected  = optionSelected.val();
                 
                   var path ='{{ route('sub_ajax.home') }}';
                   var datas={valueSelected:valueSelected};
     
                  
                               ajax_show_disp(path,datas,'.osd');
                   
                   
                   });
       
       
       
               });
               
                           /**
             * Start state, city selector
             */
            $('#select_state_id').on('change', function() {
                $('#select_city_id').html('<option selected>Loading, please wait...</option>');
                if(this.value > 0)
                {
                    var ajax_url = '/ajax/cities/' + this.value;
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    jQuery.ajax({
                        url: ajax_url,
                        method: 'get',
                        data: {
                        },
                        success: function(result){
                          
                            $('#select_city_id').html('<option selected value="0"> جميع المدن</option>');
                            $.each(JSON.parse(result), function(key, value) {
                                var city_id = value.id;
                                var city_name = value.city_name;
                                
                                $('#select_city_id').append('<option value="'+ city_id +'">' + city_name + '</option>');
                            });
                    }});
                }
            });
            
</script>

@yield('scripts')



@if($site_global_settings->setting_site_footer_enabled == \App\Setting::SITE_FOOTER_ENABLED)
    {!! $site_global_settings->setting_site_footer !!}
@endif

<script>
    
</script>

</body>
</html>