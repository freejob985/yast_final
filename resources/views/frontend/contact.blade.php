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
                            <h1>{{ __('frontend.contact.title') }}</h1>
                            <p class="mb-0">(تعاقد على مشروعك مع ياسطا.نت مباشرة)</p>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="site-section bg-light">
        <div class="container">

            @include('backend.admin.partials.alert')

            <div class="row">
                <div class="col-md-7 mb-5"  data-aos="fade">



                    <form action="{{ route('page.contact.do') }}" class="p-5 bg-white" method="POST">
                        @csrf

                        <div class="row form-group">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="text-black" for="first_name">{{ __('frontend.contact.first-name') }}</label>
                                <input name="first_name" type="text" id="first_name" class="form-control @error('first_name') is-invalid @enderror">
                                @error('first_name')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="text-black" for="last_name">{{ __('frontend.contact.last-name') }}</label>
                                <input name="last_name" type="text" id="last_name" class="form-control @error('last_name') is-invalid @enderror">
                                @error('last_name')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="email">{{ __('frontend.contact.email') }}</label>
                                <input name="email" type="email" id="email" class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="number">رقم الموبيل</label>
                                <input name="number" type="number" id="email" class="form-control @error('number') is-invalid @enderror">
                                @error('number')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="subject">{{ __('frontend.contact.subject') }}</label>
                                <input name="subject" type="subject" id="subject" class="form-control @error('subject') is-invalid @enderror">
                                @error('subject')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="message">{{ __('frontend.contact.message') }}</label>
                                <textarea name="message" id="message" cols="30" rows="7" class="form-control @error('message') is-invalid @enderror" placeholder=""></textarea>
                                @error('message')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <input type="submit" value="Send Message" class="btn btn-primary py-2 px-4 text-white">
                            </div>
                        </div>


                    </form>
                </div>
                <div class="col-md-5"  data-aos="fade" data-aos-delay="100">

                    <div class="p-4 mb-3 bg-white">
                        <p class="mb-0 font-weight-bold">{{ __('frontend.contact.address') }}</p>
                        <p class="mb-4">
                            {{ $all_settings->setting_site_address }}<br>
                            {{ $all_settings->setting_site_city . ', ' . $all_settings->setting_site_state . ', ' . ' ' . $all_settings->setting_site_postal_code }}<br>
                            {{ $all_settings->setting_site_country }}
                        </p>

                        <p class="mb-0 font-weight-bold">{{ __('frontend.contact.phone') }}</p>
                        <p class="mb-4"><a href="#">{{ $all_settings->setting_site_phone }}</a></p>

                        <p class="mb-0 font-weight-bold">{{ __('frontend.contact.email-address') }}</p>
                        <p class="mb-0"><a href="#">{{ $all_settings->setting_site_email }}</a></p>

                    </div>

                    <div class="p-4 mb-3 bg-white">
                        <h3 class="h5 text-black mb-3">{{ __('frontend.contact.more-info') }}</h3>
                        <p>{{ $all_settings->setting_site_about }}</p>
                        @if($all_settings->setting_page_about_enable == \App\Setting::ABOUT_PAGE_ENABLED)
                        <p><a href="{{ route('page.about') }}" class="btn btn-primary px-4 py-2 text-white">{{ __('frontend.contact.learn-more') }}</a></p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    @if(count($all_faq))
    <div class="site-section">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-7 text-center border-primary">
                    <h2 class="font-weight-light text-primary">{{ __('frontend.contact.faq') }}</h2>
                    <p class="color-black-opacity-5"></p>
                </div>
            </div>


            <div class="row justify-content-center">
                <div class="col-8">

                    @foreach($all_faq as $key => $faq)
                        <div class="border p-3 rounded mb-2">
                            <a data-toggle="collapse" href="#collapse-{{ $faq->id }}" role="button" aria-expanded="false" aria-controls="collapse-{{ $faq->id }}" class="accordion-item h5 d-block mb-0">{{ $faq->faqs_question }}</a>

                            <div class="collapse" id="collapse-{{ $faq->id }}">
                                <div class="pt-2">
                                    <p class="mb-0">{{ $faq->faqs_answer }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>
    </div>
    @endif

@endsection

@section('scripts')
@endsection
