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
                            <h1>{{ __('auth.login') }}</h1>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="site-section bg-light">
        <div class="container">

            @include('backend.admin.partials.alert')

            <div class="row justify-content-center">
                <div class="col-md-7 mb-5"  data-aos="fade">

                    <form method="POST" action="{{ route('login') }}" class="p-5 bg-white">
                        @csrf
                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="email">التسجيل عن طريق رقم الموبيل او البريد الألكتروني</label>
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="subject">{{ __('auth.password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('auth.remember-me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12">
                                <p>{{ __('auth.no-account-yet') }}؟ <a href="{{ route('register') }}">{{ __('auth.register') }}</a></p>
                            </div>
                        </div>


                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary py-2 px-4 text-white rounded">
                                    {{ __('auth.login') }}
                                </button>
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('auth.forgot-password') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if($social_login_facebook || $social_login_google || $social_login_twitter || $social_login_linkedin || $social_login_github)
                            <div class="row mt-4 align-items-center">
                                <div class="col-md-5">
                                    <hr>
                                </div>
                                <div class="col-md-2 pl-0 pr-0 text-center">
                                    <span>{{ __('social_login.frontend.or') }}</span>
                                </div>
                                <div class="col-md-5">
                                    <hr>
                                </div>
                            </div>
                            <div class="row align-items-center mb-4 mt-2">
                                <div class="col-md-12 text-center">
                                    <span>{{ __('social_login.frontend.sign-in-with') }}</span>
                                </div>
                            </div>
                        @endif

                        @if($social_login_facebook)
                        <div class="row form-group">
                            <div class="col-md-12">
                                <a class="btn btn-facebook btn-block text-white rounded" href="{{ route('auth.login.facebook') }}">
                                    <i class="fab fa-facebook-f pr-2"></i>
                                    {{ __('social_login.frontend.sign-in-facebook') }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($social_login_google)
                        <div class="row form-group">
                            <div class="col-md-12">
                                <a class="btn btn-google btn-block text-white rounded" href="{{ route('auth.login.google') }}">
                                    <i class="fab fa-google pr-2"></i>
                                    {{ __('social_login.frontend.sign-in-google') }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($social_login_twitter)
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <a class="btn btn-twitter btn-block text-white rounded" href="{{ route('auth.login.twitter') }}">
                                        <i class="fab fa-twitter pr-2"></i>
                                        {{ __('social_login.frontend.sign-in-twitter') }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($social_login_linkedin)
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <a class="btn btn-linkedin btn-block text-white rounded" href="{{ route('auth.login.linkedin') }}">
                                        <i class="fab fa-linkedin-in pr-2"></i>
                                        {{ __('social_login.frontend.sign-in-linkedin') }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($social_login_github)
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <a class="btn btn-github btn-block text-white rounded" href="{{ route('auth.login.github') }}">
                                        <i class="fab fa-github pr-2"></i>
                                        {{ __('social_login.frontend.sign-in-github') }}
                                    </a>
                                </div>
                            </div>
                        @endif

                    </form>
                </div>

            </div>


        </div>
    </div>

@endsection

@section('scripts')
@endsection
