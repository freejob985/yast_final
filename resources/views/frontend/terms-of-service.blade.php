@extends('frontend.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url({{ asset('frontend/images/placeholder/header-inner.jpg') }});" data-aos="fade" data-stellar-background-ratio="0.5">
        <div class="container">
            <div class="row align-items-center justify-content-center text-center">

                <div class="col-md-10" data-aos="fade-up" data-aos-delay="400">


                    <div class="row justify-content-center mt-5">
                        <div class="col-md-10 text-center">
                            <h1>{{ __('frontend.terms-of-service.title') }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section"  data-aos="fade">
        <div class="container">

            <div class="row mb-5">
                <div class="col-12">
                    {!! clean($terms_of_service) !!}
                </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')
@endsection
