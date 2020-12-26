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
                            <h1>{{ __('auth.verify-email') }}</h1>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="site-section bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 mb-5"  data-aos="fade">

                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('auth.verify-email-sent') }}
                        </div>
                    @endif



                    <form method="POST" action="{{ route('verification.resend') }}" class="p-5 bg-white">
                        @csrf
                        <div class="row form-group">
                            <div class="col-md-12">
                                {{ __('auth.verify-email-check') }}
                                {{ __('auth.verify-email-not-receive') }},
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary py-2 px-4 text-white">{{ __('auth.verify-email-request') }}</button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
@endsection
