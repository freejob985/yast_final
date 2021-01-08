@extends('frontend.layouts.app')
@section('styles')
<link href="{{ asset('frontend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="{{ asset('frontend/vendor/justified-gallery/justifiedGallery.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('frontend/vendor/colorbox/colorbox.css') }}" type="text/css">
<link rel="stylesheet" target="_blank"
    href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ asset('backend/vendor/jquery-bar-rating/dist/themes/bootstrap-stars.css') }}" />
<style>
    .star-rating {
        line-height: 32px;
        font-size: 1.25em;
    }

    input[type=file] {
        cursor: pointer;
        width: 180px;
        height: 34px;
        overflow: hidden;
    }

    input[type=file]:before {
        width: 158px;
        height: 32px;
        font-size: 16px;
        line-height: 32px;
        content: 'Select your file';
        display: inline-block;
        background: white;
        border: 1px solid #000;
        padding: 0 10px;
        text-align: center;
        font-family: Helvetica, Arial, sans-serif;
    }

    input[type=file]::-webkit-file-upload-button {
        visibility: hidden;
    }

    .star-rating .fa-star {
        color: yellow;
    }
</style>
@endsection
@section('content')
<!-- Display on xl -->
<div class="site-blocks-cover inner-page-cover overlay d-none d-xl-flex" style="background-image: url({{ asset('frontend/images/placeholder/header-item-0.jpg') }});" data-aos="fade" data-stellar-background-ratio="0.5">
    <div class="container">
        @auth
        @if (DB::table('Orders')->where('id_u', Auth::user()->id)->where('st', 1)->exists() or Auth::user()->role_id==="1" or DB::table('items')->where('user_id', Auth::user()->id)->exists())
        <div class="row align-items-center item-blocks-cover">
            <div class="col-lg-2 col-md-2" data-aos="fade-up" data-aos-delay="400">
                @if(!empty($item->item_image_tiny))
                <img src="{{ Storage::disk('public')->url('item/' . $item->item_image_tiny) }}" alt="Image"
                    class="img-fluid rounded">
                @elseif(!empty($item->item_image))
                <img src="{{ Storage::disk('public')->url('item/' . $item->item_image) }}" alt="Image"
                    class="img-fluid rounded">
                @else
                <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.jpg') }}" alt="Image"
                    class="img-fluid rounded">
                @endif
            </div>
            <div class="col-lg-7 col-md-5" data-aos="fade-up" data-aos-delay="400">
                <h1 class="item-cover-title-section">{{ $item->item_title }}</h1>
                @if($item->getCountRating() > 0)
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="rating_stars_header"></div>
                    </div>
                    <div class="col-md-9 pl-0">
                        <span class="item-cover-address-section">
                            @if($item->getCountRating() == 1)
                            {{ '(' . $item->getCountRating() . ' ' . __('review.frontend.review') . ')' }}
                            @else
                            {{ '(' . $item->getCountRating() . ' ' . __('review.frontend.reviews') . ')' }}
                            @endif
                        </span>
                    </div>
                </div>
                @endif
                <a class="btn btn-sm btn-outline-primary rounded mb-2"
                    href="{{ route('page.category', $item->category->category_slug) }}">
                    <span class="category">{{ $item->category->category_name }}</span>
                </a>
                <p class="item-cover-address-section">
                    @if($item->item_address_hide == 0)
                    {{ $item->item_address }} <br>
                    @endif
                    {{ $item->city->city_name }}, {{ $item->state->state_name }} {{ $item->item_postal_code }}
                </p>
                @guest
                <a class="btn btn-primary rounded text-white"
                    href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i
                        class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                @else
                @if($item->user_id != Auth::user()->id)
                @if(Auth::user()->isAdmin())
                @if($item->reviewedByUser(Auth::user()->id))
                <a class="btn btn-primary rounded text-white"
                    href="{{ route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}"
                    target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                @else
                <a class="btn btn-primary rounded text-white"
                    href="{{ route('admin.items.reviews.create', $item->item_slug) }}" target="_blank"><i
                        class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                @endif
                @else
                @if($item->reviewedByUser(Auth::user()->id))
                <a class="btn btn-primary rounded text-white"
                    href="{{ route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}"
                    target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                @else
                <a class="btn btn-primary rounded text-white"
                    href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i
                        class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                @endif
                @endif
                @endif
                @endguest
                <a class="btn btn-primary rounded text-white item-share-button"><i class="fas fa-share-alt"></i>
                    {{ __('frontend.item.share') }}</a>
                @guest
                <a class="btn btn-primary rounded text-white" id="item-save-button"><i class="far fa-bookmark"></i>
                    {{ __('frontend.item.save') }}</a>
                <form id="item-save-form" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}"
                    method="POST" hidden="true">
                    @csrf
                </form>
                @else
                @if(Auth::user()->hasSavedItem($item->id))
                <a class="btn btn-warning rounded text-white" id="item-saved-button"><i class="fas fa-check"></i>
                    {{ __('frontend.item.saved') }}</a>
                <form id="item-unsave-form" action="{{ route('page.item.unsave', ['item_slug' => $item->item_slug]) }}"
                    method="POST" hidden="true">
                    @csrf
                </form>
                @else
                <a class="btn btn-primary rounded text-white" id="item-save-button"><i class="far fa-bookmark"></i>
                    {{ __('frontend.item.save') }}</a>
                <form id="item-save-form" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}"
                    method="POST" hidden="true">
                    @csrf
                </form>
                @endif
                @endguest
                <a class="btn btn-primary rounded text-white" href="tel:{{ $item->item_phone }}"><i
                        class="fas fa-phone-alt"></i> {{ __('frontend.item.call') }}</a>
                <a target="_blank" class="btn btn-primary rounded text-white"
                    href="https://api.whatsapp.com/send?phone={{ $item->item_phone }}&text=التواصل من موقع yasta.net"
                    class="float" target="_blank">
                    <i class="fa fa-whatsapp my-float"></i>
                </a>
                
            </div>
            <div class="col-lg-3 col-md-5 pl-0 pr-0 item-cover-contact-section" data-aos="fade-up" data-aos-delay="400">
                @guest
                  <div class="row mb-4 align-items-center" style="display: initial;color: white;font-weight: 700;" >
                        <div class="col-4">
                            @if(empty($item->user->user_image))
                            <img style="margin-left: 163%;" src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.jpg') }}"
                                alt="Image" class="img-fluid rounded-circle">
                            @else
                            <img style="margin-left: 163%;" src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}"
                                alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                            @endif
                        </div>
                        <div class="col-8 pl-0">
                            <span class="font-size-13">{{ $item->user->name }}</span><br />
                            <span
                                class="font-size-13">{{ __('frontend.item.posted') . ' ' . $item->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @endguest
                    
                    
                    @auth
                  <div class="row mb-4 align-items-center" style="display: initial;color: white;font-weight: 700;" >
                        <div class="col-4">
                            @if(empty($item->user->user_image))
                            <img style="margin-left: 163%;" src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.jpg') }}"
                                alt="Image" class="img-fluid rounded-circle">
                            @else
                            <img style="margin-left: 163%;" src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}"
                                alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                            @endif
                        </div>
                        <div class="col-8 pl-0">
                            <span class="font-size-13">{{ $item->user->name }}</span><br />
                            <span
                                class="font-size-13">{{ __('frontend.item.posted') . ' ' . $item->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                   @endauth
                @if($item->item_phone_hide == 0)
                @if(!empty($item->item_phone))
                <h3><i class="fas fa-phone-alt"></i> {{ $item->item_phone }}</h3>
                @endif
                @endif
                <p>
                    @if(!empty($item->item_website))
                    <a class="mr-1" href="{{ $item->item_website }}" target="_blank" rel="nofollow"><i
                            class="fas fa-globe"></i></a>
                    @endif
                    @if(!empty($item->item_social_facebook))
                    <a class="mr-1" href="{{ $item->item_social_facebook }}" target="_blank" rel="nofollow"><i
                            class="fab fa-facebook-square"></i></a>
                    @endif
                    @if(!empty($item->item_social_twitter))
                    <a class="mr-1" href="{{ $item->item_social_twitter }}" target="_blank" rel="nofollow"><i
                            class="fab fa-twitter-square"></i></a>
                    @endif
                    @if(!empty($item->item_social_linkedin))
                    <a class="mr-1" href="{{ $item->item_social_linkedin }}" target="_blank" rel="nofollow"><i
                            class="fab fa-linkedin"></i></a>
                    @endif
                </p>
            </div>
        </div>
        @else
        <div class="row align-items-center item-blocks-cover">
            <div class="col-lg-2 col-md-2" data-aos="fade-up" data-aos-delay="400">
            </div>
            <div class="col-lg-7 col-md-5" data-aos="fade-up" data-aos-delay="400">
                <h1 class="item-cover-title-section">{{ $item->item_title }}</h1>
                <a class="btn btn-sm btn-outline-primary rounded mb-2"
                    href="{{ route('page.category', $item->category->category_slug) }}">
                    <span class="category">{{ $item->category->category_name }}</span>
                </a>
                    @if (Auth::check())
                <a href="{{ route('user.subscriptions.edit', $subscription->id) }}" class="btn btn-sm btn-outline-primary rounded mb-2" href="#" style="color: #ffffff;background-color: #007bff;background-image: none;border-color: #007bff;">
  @else
                  <a href="/login" class="btn btn-sm btn-outline-primary rounded mb-2" href="#" style="color: #ffffff;background-color: #007bff;background-image: none;border-color: #007bff;">

                    @endif
                    برجاء التسجيل والاشتراك في الموقع حتي يمكنك التواصل مع صاحب الخدمة
                </a>
                @guest
                @else
                @if($item->user_id != Auth::user()->id)
                @if(Auth::user()->isAdmin())
                @else
                @endif
                @endif
                @endguest
                @guest
                @else
                @endguest
            </div>
        </div>
        @endif
        @endauth
        @guest
        <div class="row align-items-center item-blocks-cover">
            <div class="col-lg-2 col-md-2" data-aos="fade-up" data-aos-delay="400">
            </div>
            <div class="col-lg-7 col-md-5" data-aos="fade-up" data-aos-delay="400">
                <h1 class="item-cover-title-section">{{ $item->item_title }}</h1>
                <a class="btn btn-sm btn-outline-primary rounded mb-2"
                    href="/login">
                    <span class="category">{{ $item->category->category_name }}</span>
                </a>
                <a class="btn btn-sm btn-outline-primary rounded mb-2" href="/login" style="
    color: #ffffff;
    background-color: #007bff;
    background-image: none;
    border-color: #007bff;
">
                    برجاء التسجيل والاشتراك في الموقع حتي يمكنك التواصل مع صاحب الخدمة
                </a>
                @guest
                @else
                @if($item->user_id != Auth::user()->id)
                @if(Auth::user()->isAdmin())
                @else
                @endif
                @endif
                @endguest
                @guest
                @else
                @endguest
            </div>
                              <div class="row mb-4 align-items-center" style="display: initial;color: white;font-weight: 700;" >
                        <div class="col-4">
                            @if(empty($item->user->user_image))
                            <img style="margin-left: 163%;" src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.jpg') }}"
                                alt="Image" class="img-fluid rounded-circle">
                            @else
                            <img style="margin-left: 163%;" src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}"
                                alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                            @endif
                        </div>
                        <div class="col-8 pl-0">
                            <span class="font-size-13">{{ $item->user->name }}</span><br />
                            <span
                                class="font-size-13">{{ __('frontend.item.posted') . ' ' . $item->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

        </div>
        @endguest
    </div>
</div>
    <!-- Display on lg, md -->
    @if(!empty($item->item_image) && !empty($item->item_image_blur))
<div class="site-blocks-cover inner-page-cover overlay d-none d-xl-flex" style="background-image: url({{ asset('frontend/images/placeholder/header-item-0.jpg') }});" data-aos="fade" data-stellar-background-ratio="0.5">
    @else
        <div class="site-blocks-cover inner-page-cover overlay d-none d-md-flex d-lg-flex d-xl-none" style="background-image: url({{ asset('frontend/images/placeholder/header-item-0.jpg') }});" data-aos="fade" data-stellar-background-ratio="0.5">
    @endif
          <div class="container">
        @auth
        @if (DB::table('Orders')->where('id_u', Auth::user()->id)->where('st', 1)->exists() or Auth::user()->role_id==="1" or DB::table('items')->where('user_id', Auth::user()->id)->exists())
        <div class="row align-items-center item-blocks-cover">
            <div class="col-lg-2 col-md-2" data-aos="fade-up" data-aos-delay="400">
                @if(!empty($item->item_image_tiny))
                <img src="{{ Storage::disk('public')->url('item/' . $item->item_image_tiny) }}" alt="Image"
                    class="img-fluid rounded">
                @elseif(!empty($item->item_image))
                <img src="{{ Storage::disk('public')->url('item/' . $item->item_image) }}" alt="Image"
                    class="img-fluid rounded">
                @else
                <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.jpg') }}" alt="Image"
                    class="img-fluid rounded">
                @endif
            </div>
            <div class="col-lg-7 col-md-5" data-aos="fade-up" data-aos-delay="400">
                <h1 class="item-cover-title-section">{{ $item->item_title }}</h1>
                @if($item->getCountRating() > 0)
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="rating_stars_header"></div>
                    </div>
                    <div class="col-md-9 pl-0">
                        <span class="item-cover-address-section">
                            @if($item->getCountRating() == 1)
                            {{ '(' . $item->getCountRating() . ' ' . __('review.frontend.review') . ')' }}
                            @else
                            {{ '(' . $item->getCountRating() . ' ' . __('review.frontend.reviews') . ')' }}
                            @endif
                        </span>
                    </div>
                </div>
                @endif
                <a class="btn btn-sm btn-outline-primary rounded mb-2"
                    href="{{ route('page.category', $item->category->category_slug) }}">
                    <span class="category">{{ $item->category->category_name }}</span>
                </a>
                <p class="item-cover-address-section">
                    @if($item->item_address_hide == 0)
                    {{ $item->item_address }} <br>
                    @endif
                    {{ $item->city->city_name }}, {{ $item->state->state_name }} {{ $item->item_postal_code }}
                </p>
                @guest
                <a class="btn btn-primary rounded text-white"
                    href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i
                        class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                @else
                @if($item->user_id != Auth::user()->id)
                @if(Auth::user()->isAdmin())
                @if($item->reviewedByUser(Auth::user()->id))
                <a class="btn btn-primary rounded text-white"
                    href="{{ route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}"
                    target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                @else
                <a class="btn btn-primary rounded text-white"
                    href="{{ route('admin.items.reviews.create', $item->item_slug) }}" target="_blank"><i
                        class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                @endif
                @else
                @if($item->reviewedByUser(Auth::user()->id))
                <a class="btn btn-primary rounded text-white"
                    href="{{ route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}"
                    target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                @else
                <a class="btn btn-primary rounded text-white"
                    href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i
                        class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                @endif
                @endif
                @endif
                @endguest
                <a class="btn btn-primary rounded text-white item-share-button"><i class="fas fa-share-alt"></i>
                    {{ __('frontend.item.share') }}</a>
                @guest
                <a class="btn btn-primary rounded text-white" id="item-save-button"><i class="far fa-bookmark"></i>
                    {{ __('frontend.item.save') }}</a>
                <form id="item-save-form" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}"
                    method="POST" hidden="true">
                    @csrf
                </form>
                @else
                @if(Auth::user()->hasSavedItem($item->id))
                <a class="btn btn-warning rounded text-white" id="item-saved-button"><i class="fas fa-check"></i>
                    {{ __('frontend.item.saved') }}</a>
                <form id="item-unsave-form" action="{{ route('page.item.unsave', ['item_slug' => $item->item_slug]) }}"
                    method="POST" hidden="true">
                    @csrf
                </form>
                @else
                <a class="btn btn-primary rounded text-white" id="item-save-button"><i class="far fa-bookmark"></i>
                    {{ __('frontend.item.save') }}</a>
                <form id="item-save-form" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}"
                    method="POST" hidden="true">
                    @csrf
                </form>
                @endif
                @endguest
                <a class="btn btn-primary rounded text-white" href="tel:{{ $item->item_phone }}"><i
                        class="fas fa-phone-alt"></i> {{ __('frontend.item.call') }}</a>
                <a target="_blank" class="btn btn-primary rounded text-white"
                    href="https://api.whatsapp.com/send?phone={{ $item->item_phone }}&text=التواصل من موقع yasta.net"
                    class="float" target="_blank">
                    <i class="fa fa-whatsapp my-float"></i>
                </a>
                
            </div>
            <div class="col-lg-3 col-md-5 pl-0 pr-0 item-cover-contact-section" data-aos="fade-up" data-aos-delay="400">
                @guest
                  <div class="row mb-4 align-items-center" style="display: initial;color: white;font-weight: 700;" >
                        <div class="col-4">
                            @if(empty($item->user->user_image))
                            <img style="margin-left: 163%;" src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.jpg') }}"
                                alt="Image" class="img-fluid rounded-circle">
                            @else
                            <img style="margin-left: 163%;" src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}"
                                alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                            @endif
                        </div>
                        <div class="col-8 pl-0">
                            <span class="font-size-13">{{ $item->user->name }}</span><br />
                            <span
                                class="font-size-13">{{ __('frontend.item.posted') . ' ' . $item->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @endguest
                    
                    
                    @auth
                  <div class="row mb-4 align-items-center" style="display: initial;color: white;font-weight: 700;" >
                        <div class="col-4">
                            @if(empty($item->user->user_image))
                            <img style="margin-left: 163%;" src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.jpg') }}"
                                alt="Image" class="img-fluid rounded-circle">
                            @else
                            <img style="margin-left: 163%;" src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}"
                                alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                            @endif
                        </div>
                        <div class="col-8 pl-0">
                            <span class="font-size-13">{{ $item->user->name }}</span><br />
                            <span
                                class="font-size-13">{{ __('frontend.item.posted') . ' ' . $item->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                   @endauth
                @if($item->item_phone_hide == 0)
                @if(!empty($item->item_phone))
                <h3><i class="fas fa-phone-alt"></i> {{ $item->item_phone }}</h3>
                @endif
                @endif
                <p>
                    @if(!empty($item->item_website))
                    <a class="mr-1" href="{{ $item->item_website }}" target="_blank" rel="nofollow"><i
                            class="fas fa-globe"></i></a>
                    @endif
                    @if(!empty($item->item_social_facebook))
                    <a class="mr-1" href="{{ $item->item_social_facebook }}" target="_blank" rel="nofollow"><i
                            class="fab fa-facebook-square"></i></a>
                    @endif
                    @if(!empty($item->item_social_twitter))
                    <a class="mr-1" href="{{ $item->item_social_twitter }}" target="_blank" rel="nofollow"><i
                            class="fab fa-twitter-square"></i></a>
                    @endif
                    @if(!empty($item->item_social_linkedin))
                    <a class="mr-1" href="{{ $item->item_social_linkedin }}" target="_blank" rel="nofollow"><i
                            class="fab fa-linkedin"></i></a>
                    @endif
                </p>
            </div>
        </div>
        @else
        <div class="row align-items-center item-blocks-cover">
            <div class="col-lg-2 col-md-2" data-aos="fade-up" data-aos-delay="400">
            </div>
            <div class="col-lg-7 col-md-5" data-aos="fade-up" data-aos-delay="400">
                <h1 class="item-cover-title-section">{{ $item->item_title }}</h1>
                <a class="btn btn-sm btn-outline-primary rounded mb-2"
                    href="{{ route('page.category', $item->category->category_slug) }}">
                    <span class="category">{{ $item->category->category_name }}</span>
                </a>
                    @if (Auth::check())
                <a href="{{ route('user.subscriptions.edit', $subscription->id) }}" class="btn btn-sm btn-outline-primary rounded mb-2" href="#" style="color: #ffffff;background-color: #007bff;background-image: none;border-color: #007bff;">
  @else
                  <a href="/login" class="btn btn-sm btn-outline-primary rounded mb-2" href="#" style="color: #ffffff;background-color: #007bff;background-image: none;border-color: #007bff;">

                    @endif
                    برجاء التسجيل والاشتراك في الموقع حتي يمكنك التواصل مع صاحب الخدمة
                </a>
                @guest
                @else
                @if($item->user_id != Auth::user()->id)
                @if(Auth::user()->isAdmin())
                @else
                @endif
                @endif
                @endguest
                @guest
                @else
                @endguest
            </div>
        </div>
        @endif
        @endauth
        @guest
        <div class="row align-items-center item-blocks-cover">
            <div class="col-lg-2 col-md-2" data-aos="fade-up" data-aos-delay="400">
            </div>
            <div class="col-lg-7 col-md-5" data-aos="fade-up" data-aos-delay="400">
                <h1 class="item-cover-title-section">{{ $item->item_title }}</h1>
                <a class="btn btn-sm btn-outline-primary rounded mb-2"
                    href="/login">
                    <span class="category">{{ $item->category->category_name }}</span>
                </a>
                <a class="btn btn-sm btn-outline-primary rounded mb-2" href="/login" style="
    color: #ffffff;
    background-color: #007bff;
    background-image: none;
    border-color: #007bff;
">
                    برجاء التسجيل والاشتراك في الموقع حتي يمكنك التواصل مع صاحب الخدمة
                </a>
                @guest
                @else
                @if($item->user_id != Auth::user()->id)
                @if(Auth::user()->isAdmin())
                @else
                @endif
                @endif
                @endguest
                @guest
                @else
                @endguest
            </div>
                                <div class="row mb-4 align-items-center" style="display: initial;color: white;font-weight: 700;" >
                        <div class="col-4">
                            @if(empty($item->user->user_image))
                            <img style="margin-left: 163%;" src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.jpg') }}"
                                alt="Image" class="img-fluid rounded-circle">
                            @else
                            <img style="margin-left: 163%;" src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}"
                                alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                            @endif
                        </div>
                        <div class="col-8 pl-0">
                            <span class="font-size-13">{{ $item->user->name }}</span><br />
                            <span
                                class="font-size-13">{{ __('frontend.item.posted') . ' ' . $item->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

        </div>

        @endguest
    </div>
    </div>

    <!-- Display on sm and xs -->
    @if(!empty($item->item_image) && !empty($item->item_image_blur))
        <div class="site-blocks-cover site-blocks-cover-sm inner-page-cover overlay d-md-none" 
       style="background-image: url({{ asset('frontend/images/placeholder/header-item-0.jpg') }});"data-aos="fade" data-stellar-background-ratio="0.5">
    @else
        <div class="site-blocks-cover site-blocks-cover-sm inner-page-cover overlay d-md-none" 
       style="background-image: url({{ asset('frontend/images/placeholder/header-item-0.jpg') }});"
        data-aos="fade" data-stellar-background-ratio="0.5">
    @endif
           <div class="container">
        @auth
        @if (DB::table('Orders')->where('id_u', Auth::user()->id)->where('st', 1)->exists() or Auth::user()->role_id==="1" or DB::table('items')->where('user_id', Auth::user()->id)->exists())
        <div class="row align-items-center item-blocks-cover">
            <div class="col-lg-2 col-md-2" data-aos="fade-up" data-aos-delay="400">
                @if(!empty($item->item_image_tiny))
                <img src="{{ Storage::disk('public')->url('item/' . $item->item_image_tiny) }}" alt="Image"
                    class="img-fluid rounded">
                @elseif(!empty($item->item_image))
                <img src="{{ Storage::disk('public')->url('item/' . $item->item_image) }}" alt="Image"
                    class="img-fluid rounded">
                @else
                <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.jpg') }}" alt="Image"
                    class="img-fluid rounded">
                @endif
            </div>
            <div class="col-lg-7 col-md-5" data-aos="fade-up" data-aos-delay="400">
                <h1 class="item-cover-title-section">{{ $item->item_title }}</h1>
                @if($item->getCountRating() > 0)
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="rating_stars_header"></div>
                    </div>
                    <div class="col-md-9 pl-0">
                        <span class="item-cover-address-section">
                            @if($item->getCountRating() == 1)
                            {{ '(' . $item->getCountRating() . ' ' . __('review.frontend.review') . ')' }}
                            @else
                            {{ '(' . $item->getCountRating() . ' ' . __('review.frontend.reviews') . ')' }}
                            @endif
                        </span>
                    </div>
                </div>
                @endif
                <a class="btn btn-sm btn-outline-primary rounded mb-2"
                    href="{{ route('page.category', $item->category->category_slug) }}">
                    <span class="category">{{ $item->category->category_name }}</span>
                </a>
                <p class="item-cover-address-section">
                    @if($item->item_address_hide == 0)
                    {{ $item->item_address }} <br>
                    @endif
                    {{ $item->city->city_name }}, {{ $item->state->state_name }} {{ $item->item_postal_code }}
                </p>
                @guest
                <a class="btn btn-primary rounded text-white"
                    href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i
                        class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                @else
                @if($item->user_id != Auth::user()->id)
                @if(Auth::user()->isAdmin())
                @if($item->reviewedByUser(Auth::user()->id))
                <a class="btn btn-primary rounded text-white"
                    href="{{ route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}"
                    target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                @else
                <a class="btn btn-primary rounded text-white"
                    href="{{ route('admin.items.reviews.create', $item->item_slug) }}" target="_blank"><i
                        class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                @endif
                @else
                @if($item->reviewedByUser(Auth::user()->id))
                <a class="btn btn-primary rounded text-white"
                    href="{{ route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}"
                    target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                @else
                <a class="btn btn-primary rounded text-white"
                    href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i
                        class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                @endif
                @endif
                @endif
                @endguest
                <a class="btn btn-primary rounded text-white item-share-button"><i class="fas fa-share-alt"></i>
                    {{ __('frontend.item.share') }}</a>
                @guest
                <a class="btn btn-primary rounded text-white" id="item-save-button"><i class="far fa-bookmark"></i>
                    {{ __('frontend.item.save') }}</a>
                <form id="item-save-form" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}"
                    method="POST" hidden="true">
                    @csrf
                </form>
                @else
                @if(Auth::user()->hasSavedItem($item->id))
                <a class="btn btn-warning rounded text-white" id="item-saved-button"><i class="fas fa-check"></i>
                    {{ __('frontend.item.saved') }}</a>
                <form id="item-unsave-form" action="{{ route('page.item.unsave', ['item_slug' => $item->item_slug]) }}"
                    method="POST" hidden="true">
                    @csrf
                </form>
                @else
                <a class="btn btn-primary rounded text-white" id="item-save-button"><i class="far fa-bookmark"></i>
                    {{ __('frontend.item.save') }}</a>
                <form id="item-save-form" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}"
                    method="POST" hidden="true">
                    @csrf
                </form>
                @endif
                @endguest
                <a class="btn btn-primary rounded text-white" href="tel:{{ $item->item_phone }}"><i
                        class="fas fa-phone-alt"></i> {{ __('frontend.item.call') }}</a>
                <a target="_blank" class="btn btn-primary rounded text-white"
                    href="https://api.whatsapp.com/send?phone={{ $item->item_phone }}&text=التواصل من موقع yasta.net"
                    class="float" target="_blank">
                    <i class="fa fa-whatsapp my-float"></i>
                </a>
                
            </div>
            <div class="col-lg-3 col-md-5 pl-0 pr-0 item-cover-contact-section" data-aos="fade-up" data-aos-delay="400">
                @guest
                  <div class="row mb-4 align-items-center" style="display: initial;color: white;font-weight: 700;" >
                        <div class="col-4">
                            @if(empty($item->user->user_image))
                            <img style="margin-left: 163%;" src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.jpg') }}"
                                alt="Image" class="img-fluid rounded-circle">
                            @else
                            <img style="margin-left: 163%;" src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}"
                                alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                            @endif
                        </div>
                        <div class="col-8 pl-0">
                            <span class="font-size-13">{{ $item->user->name }}</span><br />
                            <span
                                class="font-size-13">{{ __('frontend.item.posted') . ' ' . $item->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @endguest
                    
                    
                    @auth
                  <div class="row mb-4 align-items-center" style="display: initial;color: white;font-weight: 700;" >
                        <div class="col-4">
                            @if(empty($item->user->user_image))
                            <img style="margin-left: 163%;" src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.jpg') }}"
                                alt="Image" class="img-fluid rounded-circle">
                            @else
                            <img style="margin-left: 163%;" src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}"
                                alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                            @endif
                        </div>
                        <div class="col-8 pl-0">
                            <span class="font-size-13">{{ $item->user->name }}</span><br />
                            <span
                                class="font-size-13">{{ __('frontend.item.posted') . ' ' . $item->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                   @endauth
                @if($item->item_phone_hide == 0)
                @if(!empty($item->item_phone))
                <h3><i class="fas fa-phone-alt"></i> {{ $item->item_phone }}</h3>
                @endif
                @endif
                <p>
                    @if(!empty($item->item_website))
                    <a class="mr-1" href="{{ $item->item_website }}" target="_blank" rel="nofollow"><i
                            class="fas fa-globe"></i></a>
                    @endif
                    @if(!empty($item->item_social_facebook))
                    <a class="mr-1" href="{{ $item->item_social_facebook }}" target="_blank" rel="nofollow"><i
                            class="fab fa-facebook-square"></i></a>
                    @endif
                    @if(!empty($item->item_social_twitter))
                    <a class="mr-1" href="{{ $item->item_social_twitter }}" target="_blank" rel="nofollow"><i
                            class="fab fa-twitter-square"></i></a>
                    @endif
                    @if(!empty($item->item_social_linkedin))
                    <a class="mr-1" href="{{ $item->item_social_linkedin }}" target="_blank" rel="nofollow"><i
                            class="fab fa-linkedin"></i></a>
                    @endif
                </p>
            </div>
        </div>
        @else
        <div class="row align-items-center item-blocks-cover">
            <div class="col-lg-2 col-md-2" data-aos="fade-up" data-aos-delay="400">
            </div>
            <div class="col-lg-7 col-md-5" data-aos="fade-up" data-aos-delay="400">
                <h1 class="item-cover-title-section">{{ $item->item_title }}</h1>
                <a class="btn btn-sm btn-outline-primary rounded mb-2"
                    href="{{ route('page.category', $item->category->category_slug) }}">
                    <span class="category">{{ $item->category->category_name }}</span>
                </a>
                    @if (Auth::check())
                <a href="{{ route('user.subscriptions.edit', $subscription->id) }}" class="btn btn-sm btn-outline-primary rounded mb-2" href="#" style="color: #ffffff;background-color: #007bff;background-image: none;border-color: #007bff;">
  @else
                  <a href="/login" class="btn btn-sm btn-outline-primary rounded mb-2" href="#" style="color: #ffffff;background-color: #007bff;background-image: none;border-color: #007bff;">

                    @endif
                    برجاء التسجيل والاشتراك في الموقع حتي يمكنك التواصل مع صاحب الخدمة
                </a>
                @guest
                @else
                @if($item->user_id != Auth::user()->id)
                @if(Auth::user()->isAdmin())
                @else
                @endif
                @endif
                @endguest
                @guest
                @else
                @endguest
            </div>
        </div>
        @endif
        @endauth
        @guest
        <div class="row align-items-center item-blocks-cover">
            
            <div class="col-lg-2 col-md-2" data-aos="fade-up" data-aos-delay="400">
            </div>
            <div class="col-lg-7 col-md-5" data-aos="fade-up" data-aos-delay="400">
                            <div class="row mb-4 align-items-center" style="display: initial;color: white;font-weight: 700;" >
                        <div class="col-4">
                            @if(empty($item->user->user_image))
                            <img style="margin-left: 101%;width: 77%;" src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.jpg') }}"
                                alt="Image" class="img-fluid rounded-circle">
                            @else
                            <img style="margin-left: 101%;width: 77%;" src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}"
                                alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                            @endif
                        </div>
                        <div class="col-8 pl-0">
                            <span class="font-size-13">{{ $item->user->name }}</span><br />
                            <span
                                class="font-size-13">{{ __('frontend.item.posted') . ' ' . $item->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                <h1 class="item-cover-title-section">{{ $item->item_title }}</h1>
                <a class="btn btn-sm btn-outline-primary rounded mb-2"
                    href="{{ route('page.category', $item->category->category_slug) }}">
                    <span class="category">{{ $item->category->category_name }}</span>
                </a>
                <a class="btn btn-sm btn-outline-primary rounded mb-2" href="/login" style="color: #ffffff;background-color: #007bff;background-image: none;border-color: #007bff;">
                    برجاء التسجيل والاشتراك في الموقع حتي يمكنك التواصل مع صاحب الخدمة
                </a>
                @guest
                @else
                @if($item->user_id != Auth::user()->id)
                @if(Auth::user()->isAdmin())
                @else
                @endif
                @endif
                @endguest
                @guest
                @else
                @endguest
            </div>
        </div>
        @endguest
    </div>
    </div>



<div class="site-section">
    <div class="container">
        @include('backend.admin.partials.alert')
        @if($ads_before_breadcrumb->count() > 0)
        @foreach($ads_before_breadcrumb as $ads_before_breadcrumb_key => $ad_before_breadcrumb)
        <div class="row mb-5">
            @if($ad_before_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
            <div class="col-12 text-left">
                <div>
                    {!! $ad_before_breadcrumb->advertisement_code !!}
                </div>
            </div>
            @elseif($ad_before_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
            <div class="col-12 text-center">
                <div>
                    {!! $ad_before_breadcrumb->advertisement_code !!}
                </div>
            </div>
            @elseif($ad_before_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
            <div class="col-12 text-right">
                <div>
                    {!! $ad_before_breadcrumb->advertisement_code !!}
                </div>
            </div>
            @endif
        </div>
        @endforeach
        @endif
        <div class="row mb-3">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a
                                href="{{ route('page.home') }}">{{ __('frontend.header.home') }}</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('page.categories') }}">{{ __('frontend.item.all-categories') }}</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('page.category', $item->category->category_slug) }}">{{ $item->category->category_name }}</a>
                        </li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('page.category.state', ['category_slug'=>$item->category->category_slug, 'state_slug'=>$item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                        </li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('page.category.state.city', ['category_slug'=>$item->category->category_slug, 'state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug]) }}">{{ $item->city->city_name }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $item->item_title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        @if($ads_after_breadcrumb->count() > 0)
        @foreach($ads_after_breadcrumb as $ads_after_breadcrumb_key => $ad_after_breadcrumb)
        <div class="row mb-5">
            @if($ad_after_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
            <div class="col-12 text-left">
                <div>
                    {!! $ad_after_breadcrumb->advertisement_code !!}
                </div>
            </div>
            @elseif($ad_after_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
            <div class="col-12 text-center">
                <div>
                    {!! $ad_after_breadcrumb->advertisement_code !!}
                </div>
            </div>
            @elseif($ad_after_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
            <div class="col-12 text-right">
                <div>
                    {!! $ad_after_breadcrumb->advertisement_code !!}
                </div>
            </div>
            @endif
        </div>
        @endforeach
        @endif
        <div class="row">
            <div class="col-lg-8">
                @if($ads_before_gallery->count() > 0)
                @foreach($ads_before_gallery as $ads_before_gallery_key => $ad_before_gallery)
                <div class="row mb-5">
                    @if($ad_before_gallery->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                    <div class="col-12 text-left">
                        <div>
                            {!! $ad_before_gallery->advertisement_code !!}
                        </div>
                    </div>
                    @elseif($ad_before_gallery->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                    <div class="col-12 text-center">
                        <div>
                            {!! $ad_before_gallery->advertisement_code !!}
                        </div>
                    </div>
                    @elseif($ad_before_gallery->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                    <div class="col-12 text-right">
                        <div>
                            {!! $ad_before_gallery->advertisement_code !!}
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
                @endif
                <div class="mb-4">
                    @if(count($item->galleries) > 0)
                    <div id="item-image-gallery">
                        @foreach($item->galleries as $key => $gallery)
                        <a href="{{ Storage::disk('public')->url('item/gallery/' . $gallery->item_image_gallery_name) }}"
                            rel="item-image-gallery-thumb">
                            <img alt="Image"
                                src="{{ empty($gallery->item_image_gallery_thumb_name) ? Storage::disk('public')->url('item/gallery/' . $gallery->item_image_gallery_name) : Storage::disk('public')->url('item/gallery/' . $gallery->item_image_gallery_thumb_name) }}" />
                        </a>
                        @endforeach
                    </div>
                    @else
                    @if(empty($item->item_image))
                    <img src="{{ asset('frontend/images/placeholder/full_item_feature_image.jpg') }}" alt="Image"
                        class="img-fluid rounded">
                    @else
                    <img src="{{ Storage::disk('public')->url('item/' . $item->item_image) }}" alt="Image"
                        class="img-fluid rounded">
                    @endif
                    @endif
                </div>
                @if($ads_before_description->count() > 0)
                @foreach($ads_before_description as $ads_before_description_key => $ad_before_description)
                <div class="row mb-5">
                    @if($ad_before_description->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                    <div class="col-12 text-left">
                        <div>
                            {!! $ad_before_description->advertisement_code !!}
                        </div>
                    </div>
                    @elseif($ad_before_description->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                    <div class="col-12 text-center">
                        <div>
                            {!! $ad_before_description->advertisement_code !!}
                        </div>
                    </div>
                    @elseif($ad_before_description->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                    <div class="col-12 text-right">
                        <div>
                            {!! $ad_before_description->advertisement_code !!}
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
                @endif
                <h4 class="h5 mb-4 text-black">{{ __('frontend.item.description') }}</h4>
                <p>{!! clean(nl2br($item->item_description), array('HTML.Allowed' => 'b,strong,i,em,u,ul,ol,li,p,br'))
                    !!}</p>
                @if($ads_before_location->count() > 0)
                @foreach($ads_before_location as $ads_before_location_key => $ad_before_location)
                <div class="row mb-5">
                    @if($ad_before_location->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                    <div class="col-12 text-left">
                        <div>
                            {!! $ad_before_location->advertisement_code !!}
                        </div>
                    </div>
                    @elseif($ad_before_location->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                    <div class="col-12 text-center">
                        <div>
                            {!! $ad_before_location->advertisement_code !!}
                        </div>
                    </div>
                    @elseif($ad_before_location->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                    <div class="col-12 text-right">
                        <div>
                            {!! $ad_before_location->advertisement_code !!}
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
                @endif
                <h4 class="h5 mb-4 mt-4 text-black">{{ __('frontend.item.location') }}</h4>
                <div class="row pt-2 pb-2">
                    <div class="col-12">
                        <div id="mapid-item" style="
    display: none;"></div>
                        <small>
                            @if($item->item_address_hide == 0)
                            {{ $item->item_address }}
                            @endif
                            {{ $item->city->city_name }}, {{ $item->state->state_name }} {{ $item->item_postal_code }}
                        </small>
                    </div>
                </div>
                <h4 class="h5 mb-4 mt-4 text-black">محافظات العمل</h4>
                <div class="row pt-2 pb-2">
                    <div class="col-12">
                        <div id="mapid-item" style="display: none;"></div>
                        <small>
                            @foreach( explode(",", $item->state_id_m) as $item_subscribe)
                         <button type="button" class="btn btn-primary btn-xs">{{DB::table('states')->find($item_subscribe)->state_name}}</button>

                            @endforeach
                        </small>
                    </div>
                </div>
                <h4 class="h5 mb-4 mt-4 text-black">مناطق العمل</h4>
                <div class="row pt-2 pb-2">
                    <div class="col-12">
                        

                        <div id="mapid-item" style="display: none;"></div>
                        <small>
                            @foreach(explode(",", $item->city_id_m) as $item_subscribe)
                            <button type="button" class="btn btn-primary btn-xs">{{DB::table('cities')->find($item_subscribe)->city_name}}</button>
                            @endforeach
                        </small>
                    </div>
                </div>
                @if($ads_before_features->count() > 0)
                @foreach($ads_before_features as $ads_before_features_key => $ad_before_features)
                <div class="row mb-5">
                    @if($ad_before_features->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                    <div class="col-12 text-left">
                        <div>
                            {!! $ad_before_features->advertisement_code !!}
                        </div>
                    </div>
                    @elseif($ad_before_features->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                    <div class="col-12 text-center">
                        <div>
                            {!! $ad_before_features->advertisement_code !!}
                        </div>
                    </div>
                    @elseif($ad_before_features->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                    <div class="col-12 text-right">
                        <div>
                            {!! $ad_before_features->advertisement_code !!}
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
                @endif
                <h4 class="h5 mb-4 mt-4 text-black" style="
                display: none;
            ">{{ __('frontend.item.features') }}</h4>
                @foreach($item->features as $key => $feature)
                <div class="row pt-2 pb-2 {{ $key%2 == 0 ? 'bg-light' : '' }}" style="
                display: none;
            ">
                    <div class="col-3">
                        {{ $feature->customField->custom_field_name }}
                    </div>
                    <div class="col-9">
                        @if($feature->item_feature_value)
                        @if($feature->customField->custom_field_type == \App\CustomField::TYPE_LINK)
                        <a target="_blank" rel=”nofollow”
                            href="{{ $feature->item_feature_value }}">{{ parse_url($feature->item_feature_value)['host'] }}</a>
                        @elseif($feature->customField->custom_field_type == \App\CustomField::TYPE_MULTI_SELECT)
                        @if(count(explode(',', $feature->item_feature_value)))
                        @foreach(explode(',', $feature->item_feature_value) as $key => $value)
                        <span class="review">{{ $value }}</span>
                        @endforeach
                        @else
                        {{ $feature->item_feature_value }}
                        @endif
                        @elseif($feature->customField->custom_field_type == \App\CustomField::TYPE_SELECT)
                        {{ $feature->item_feature_value }}
                        @elseif($feature->customField->custom_field_type == \App\CustomField::TYPE_TEXT)
                        {!! clean(nl2br($feature->item_feature_value), array('HTML.Allowed' =>
                        'b,strong,i,em,u,ul,ol,li,p,br')) !!}
                        @endif
                        @endif
                    </div>
                </div>
                @endforeach
                @if($ads_before_reviews->count() > 0)
                @foreach($ads_before_reviews as $ads_before_reviews_key => $ad_before_reviews)
                <div class="row mb-5">
                    @if($ad_before_reviews->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                    <div class="col-12 text-left">
                        <div>
                            {!! $ad_before_reviews->advertisement_code !!}
                        </div>
                    </div>
                    @elseif($ad_before_reviews->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                    <div class="col-12 text-center">
                        <div>
                            {!! $ad_before_reviews->advertisement_code !!}
                        </div>
                    </div>
                    @elseif($ad_before_reviews->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                    <div class="col-12 text-right">
                        <div>
                            {!! $ad_before_reviews->advertisement_code !!}
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
                @endif
                @if ($item->category_id==7)
                <h4 class="h5 mb-4 mt-4 text-black"><a style="
                            color: white;
                            text-align: center;
                            text-decoration: dashed; 
                            {{Request::root()}}
                        " type="button" class="btn btn-success"
                        href="{{Request::root()}}/laravel_project/public/files/{{ $item->file}}">تحميل الملف</a></h4>
                @endif
                <h4 class="h5 mb-4 mt-4 text-black">{{ __('review.frontend.reviews-cap') }}</h4>
                @if($reviews->count() == 0)
                @guest
                <div class="row mb-3 pt-3 pb-3 bg-light">
                    <div class="col-md-12 text-center">
                        <p class="mb-0">
                            <span class="icon-star text-warning"></span>
                            <span class="icon-star text-warning"></span>
                            <span class="icon-star text-warning"></span>
                            <span class="icon-star text-warning"></span>
                            <span class="icon-star text-warning"></span>
                        </p>
                        <span>{{ __('review.frontend.start-a-review', ['item_name' => $item->item_title]) }}</span>
                        <div class="row mt-2">
                            <div class="col-md-12 text-center">
                                <a class="btn btn-primary rounded text-white"
                                    href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i
                                        class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                @if($item->user_id != Auth::user()->id)
                @if($item->reviewedByUser(Auth::user()->id))
                <div class="row mb-3 pt-3 pb-3 bg-light">
                    <div class="col-md-9">
                        {{ __('review.frontend.posted-a-review', ['item_name' => $item->item_title]) }}
                    </div>
                    <div class="col-md-3 text-right">
                        @if(Auth::user()->isAdmin())
                        <a class="btn btn-primary rounded text-white"
                            href="{{ route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}"
                            target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                        @else
                        <a class="btn btn-primary rounded text-white"
                            href="{{ route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}"
                            target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                        @endif
                    </div>
                </div>
                @else
                <div class="row mb-3 pt-3 pb-3 bg-light">
                    <div class="col-md-12 text-center">
                        <p class="mb-0">
                            <span class="icon-star text-warning"></span>
                            <span class="icon-star text-warning"></span>
                            <span class="icon-star text-warning"></span>
                            <span class="icon-star text-warning"></span>
                            <span class="icon-star text-warning"></span>
                        </p>
                        <span>{{ __('review.frontend.start-a-review', ['item_name' => $item->item_title]) }}</span>
                        <div class="row mt-2">
                            <div class="col-md-12 text-center">
                                @if(Auth::user()->isAdmin())
                                <a class="btn btn-primary rounded text-white"
                                    href="{{ route('admin.items.reviews.create', $item->item_slug) }}"
                                    target="_blank"><i class="fas fa-star"></i>
                                    {{ __('review.backend.write-a-review') }}</a>
                                @else
                                <a class="btn btn-primary rounded text-white"
                                    href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i
                                        class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @else
                <div class="row mb-3 pt-3 pb-3 bg-light">
                    <div class="col-md-12 text-center">
                        <span>{{ __('review.frontend.no-review', ['item_name' => $item->item_title]) }}</span>
                    </div>
                </div>
                @endif
                @endguest
                @else
                <div class="row mb-3 pt-3 pb-3 bg-light">
                    <div class="col-md-9">
                        @guest
                        {{ __('review.frontend.start-a-review', ['item_name' => $item->item_title]) }}
                        @else
                        @if($item->user_id != Auth::user()->id)
                        @if(Auth::user()->isAdmin())
                        @if($item->reviewedByUser(Auth::user()->id))
                        {{ __('review.frontend.posted-a-review', ['item_name' => $item->item_title]) }}
                        @else
                        {{ __('review.frontend.start-a-review', ['item_name' => $item->item_title]) }}
                        @endif
                        @else
                        @if($item->reviewedByUser(Auth::user()->id))
                        {{ __('review.frontend.posted-a-review', ['item_name' => $item->item_title]) }}
                        @else
                        {{ __('review.frontend.start-a-review', ['item_name' => $item->item_title]) }}
                        @endif
                        @endif
                        @else
                        {{ __('review.frontend.my-reviews') }}
                        @endif
                        @endguest
                    </div>
                    <div class="col-md-3 text-right">
                        @guest
                        <a class="btn btn-primary rounded text-white"
                            href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i
                                class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                        @else
                        @if($item->user_id != Auth::user()->id)
                        @if(Auth::user()->isAdmin())
                        @if($item->reviewedByUser(Auth::user()->id))
                        <a class="btn btn-primary rounded text-white"
                            href="{{ route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}"
                            target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                        @else
                        <a class="btn btn-primary rounded text-white"
                            href="{{ route('admin.items.reviews.create', $item->item_slug) }}" target="_blank"><i
                                class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                        @endif
                        @else
                        @if($item->reviewedByUser(Auth::user()->id))
                        <a class="btn btn-primary rounded text-white"
                            href="{{ route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}"
                            target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                        @else
                        <a class="btn btn-primary rounded text-white"
                            href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i
                                class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                        @endif
                        @endif
                        @endif
                        @endguest
                    </div>
                </div>
                @foreach($reviews as $key => $review)
                <div class="row pt-4 mb-3">
                    <div class="col-md-4">
                        <div class="row align-items-center mb-3">
                            <div class="col-4">
                                @if(empty(\App\User::find($review->author_id)->user_image))
                                <img src="{{ asset('frontend/images/placeholder/profile-'. intval($review->author_id % 10) . '.jpg') }}"
                                    alt="Image" class="img-fluid rounded-circle">
                                @else
                                <img src="{{ Storage::disk('public')->url('user/' . \App\User::find($review->author_id)->user_image) }}"
                                    alt="{{ \App\User::find($review->author_id)->name }}"
                                    class="img-fluid rounded-circle">
                                @endif
                            </div>
                            <div class="col-8 pl-0">
                                <span>{{ \App\User::find($review->author_id)->name }}</span><br />
                                {{--                                            <span class="font-size-13">Posted {{ $item->created_at->diffForHumans() }}</span>--}}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <span>{{ __('review.backend.overall-rating') }}</span>
                                <select class="review_rating_stars">
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}"
                                        {{ $review->rating == \App\Item::ITEM_REVIEW_RATING_ONE ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_ONE  }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}"
                                        {{ $review->rating == \App\Item::ITEM_REVIEW_RATING_TWO ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_TWO }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}"
                                        {{ $review->rating == \App\Item::ITEM_REVIEW_RATING_THREE ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_THREE }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}"
                                        {{ $review->rating == \App\Item::ITEM_REVIEW_RATING_FOUR ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_FOUR }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}"
                                        {{ $review->rating == \App\Item::ITEM_REVIEW_RATING_FIVE ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_FIVE }}</option>
                                </select>
                            </div>
                        </div>
                        @if($review->recommend == \App\Item::ITEM_REVIEW_RECOMMEND_YES)
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <span class="bg-success text-white pl-2 pr-2 pt-2 pb-2 rounded">
                                    <i class="fas fa-check"></i>
                                    {{ __('review.backend.recommend') }}
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="row mb-0">
                            <div class="col-md-6">
                                <span class="font-size-13">{{ __('review.backend.customer-service') }}</span>
                                <select class="review_rating_stars">
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}"
                                        {{ $review->customer_service_rating == \App\Item::ITEM_REVIEW_RATING_ONE ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_ONE  }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}"
                                        {{ $review->customer_service_rating == \App\Item::ITEM_REVIEW_RATING_TWO ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_TWO }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}"
                                        {{ $review->customer_service_rating == \App\Item::ITEM_REVIEW_RATING_THREE ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_THREE }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}"
                                        {{ $review->customer_service_rating == \App\Item::ITEM_REVIEW_RATING_FOUR ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_FOUR }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}"
                                        {{ $review->customer_service_rating == \App\Item::ITEM_REVIEW_RATING_FIVE ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_FIVE }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <span class="font-size-13">{{ __('review.backend.quality') }}</span>
                                <select class="review_rating_stars">
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}"
                                        {{ $review->quality_rating == \App\Item::ITEM_REVIEW_RATING_ONE ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_ONE  }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}"
                                        {{ $review->quality_rating == \App\Item::ITEM_REVIEW_RATING_TWO ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_TWO }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}"
                                        {{ $review->quality_rating == \App\Item::ITEM_REVIEW_RATING_THREE ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_THREE }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}"
                                        {{ $review->quality_rating == \App\Item::ITEM_REVIEW_RATING_FOUR ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_FOUR }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}"
                                        {{ $review->quality_rating == \App\Item::ITEM_REVIEW_RATING_FIVE ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_FIVE }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <span class="font-size-13">{{ __('review.backend.friendly') }}</span>
                                <select class="review_rating_stars">
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}"
                                        {{ $review->friendly_rating == \App\Item::ITEM_REVIEW_RATING_ONE ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_ONE  }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}"
                                        {{ $review->friendly_rating == \App\Item::ITEM_REVIEW_RATING_TWO ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_TWO }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}"
                                        {{ $review->friendly_rating == \App\Item::ITEM_REVIEW_RATING_THREE ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_THREE }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}"
                                        {{ $review->friendly_rating == \App\Item::ITEM_REVIEW_RATING_FOUR ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_FOUR }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}"
                                        {{ $review->friendly_rating == \App\Item::ITEM_REVIEW_RATING_FIVE ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_FIVE }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <span class="font-size-13">{{ __('review.backend.pricing') }}</span>
                                <select class="review_rating_stars">
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}"
                                        {{ $review->pricing_rating == \App\Item::ITEM_REVIEW_RATING_ONE ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_ONE  }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}"
                                        {{ $review->pricing_rating == \App\Item::ITEM_REVIEW_RATING_TWO ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_TWO }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}"
                                        {{ $review->pricing_rating == \App\Item::ITEM_REVIEW_RATING_THREE ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_THREE }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}"
                                        {{ $review->pricing_rating == \App\Item::ITEM_REVIEW_RATING_FOUR ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_FOUR }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}"
                                        {{ $review->pricing_rating == \App\Item::ITEM_REVIEW_RATING_FIVE ? 'selected' : '' }}>
                                        {{ \App\Item::ITEM_REVIEW_RATING_FIVE }}</option>
                                </select>
                            </div>
                        </div>
                        @if(!empty($review->title))
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <span class="text-black">{{ $review->title }}</span>
                            </div>
                        </div>
                        @endif
                        <div class="row mb-1">
                            <div class="col-md-12">
                                <p>{!! clean(nl2br($review->body), array('HTML.Allowed' =>
                                    'b,strong,i,em,u,ul,ol,li,p,br')) !!}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <span
                                    class="review font-size-13">{{ __('review.backend.posted-at') . ' ' . \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                                @if($review->created_at != $review->updated_at)
                                <span
                                    class="review font-size-13">{{ __('review.backend.updated-at') . ' ' . \Carbon\Carbon::parse($review->updated_at)->diffForHumans() }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                @endforeach
                @endif
                @if($ads_before_comments->count() > 0)
                @foreach($ads_before_comments as $ads_before_comments_key => $ad_before_comments)
                <div class="row mb-5">
                    @if($ad_before_comments->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                    <div class="col-12 text-left">
                        <div>
                            {!! $ad_before_comments->advertisement_code !!}
                        </div>
                    </div>
                    @elseif($ad_before_comments->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                    <div class="col-12 text-center">
                        <div>
                            {!! $ad_before_comments->advertisement_code !!}
                        </div>
                    </div>
                    @elseif($ad_before_comments->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                    <div class="col-12 text-right">
                        <div>
                            {!! $ad_before_comments->advertisement_code !!}
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
                @endif
                <h4 class="h5 mb-4 mt-4 text-black">{{ __('frontend.item.comments') }}</h4>
                @comments([
                'model' => $item,
                'approved' => true,
                'perPage' => 10
                ])
                @if($ads_before_share->count() > 0)
                @foreach($ads_before_share as $ads_before_share_key => $ad_before_share)
                <div class="row mb-5">
                    @if($ad_before_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                    <div class="col-12 text-left">
                        <div>
                            {!! $ad_before_share->advertisement_code !!}
                        </div>
                    </div>
                    @elseif($ad_before_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                    <div class="col-12 text-center">
                        <div>
                            {!! $ad_before_share->advertisement_code !!}
                        </div>
                    </div>
                    @elseif($ad_before_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                    <div class="col-12 text-right">
                        <div>
                            {!! $ad_before_share->advertisement_code !!}
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
                @endif
                <h4 class="h5 mb-4 mt-4 text-black">{{ __('frontend.item.share') }}</h4>
                <div class="row">
                    <div class="col-12">
                        <!-- Create link with share to Facebook -->
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-facebook" href=""
                            data-social="facebook">
                            <i class="fab fa-facebook-f"></i>
                            {{ __('social_share.facebook') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-twitter" href=""
                            data-social="twitter">
                            <i class="fab fa-twitter"></i>
                            {{ __('social_share.twitter') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-linkedin" href=""
                            data-social="linkedin">
                            <i class="fab fa-linkedin-in"></i>
                            {{ __('social_share.linkedin') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-blogger" href=""
                            data-social="blogger">
                            <i class="fab fa-blogger-b"></i>
                            {{ __('social_share.blogger') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-pinterest" href=""
                            data-social="pinterest">
                            <i class="fab fa-pinterest-p"></i>
                            {{ __('social_share.pinterest') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-evernote" href=""
                            data-social="evernote">
                            <i class="fab fa-evernote"></i>
                            {{ __('social_share.evernote') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-reddit" href=""
                            data-social="reddit">
                            <i class="fab fa-reddit-alien"></i>
                            {{ __('social_share.reddit') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-buffer" href=""
                            data-social="buffer">
                            <i class="fab fa-buffer"></i>
                            {{ __('social_share.buffer') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wordpress" href=""
                            data-social="wordpress">
                            <i class="fab fa-wordpress-simple"></i>
                            {{ __('social_share.wordpress') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-weibo" href="" data-social="weibo">
                            <i class="fab fa-weibo"></i>
                            {{ __('social_share.weibo') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-skype" href="" data-social="skype">
                            <i class="fab fa-skype"></i>
                            {{ __('social_share.skype') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-telegram" href=""
                            data-social="telegram">
                            <i class="fab fa-telegram-plane"></i>
                            {{ __('social_share.telegram') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-viber" href="" data-social="viber">
                            <i class="fab fa-viber"></i>
                            {{ __('social_share.viber') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-whatsapp" href=""
                            data-social="whatsapp">
                            <i class="fab fa-whatsapp"></i>
                            {{ __('social_share.whatsapp') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wechat" href=""
                            data-social="wechat">
                            <i class="fab fa-weixin"></i>
                            {{ __('social_share.wechat') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-line" href="" data-social="line">
                            <i class="fab fa-line"></i>
                            {{ __('social_share.line') }}
                        </a>
                    </div>
                </div>
                @if($ads_after_share->count() > 0)
                @foreach($ads_after_share as $ads_after_share_key => $ad_after_share)
                <div class="row mt-5">
                    @if($ad_after_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                    <div class="col-12 text-left">
                        <div>
                            {!! $ad_after_share->advertisement_code !!}
                        </div>
                    </div>
                    @elseif($ad_after_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                    <div class="col-12 text-center">
                        <div>
                            {!! $ad_after_share->advertisement_code !!}
                        </div>
                    </div>
                    @elseif($ad_after_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                    <div class="col-12 text-right">
                        <div>
                            {!! $ad_after_share->advertisement_code !!}
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
                @endif
            </div>
            <div class="col-lg-3 ml-auto">
                <div class="pt-3">
                    @if($ads_before_sidebar_content->count() > 0)
                    @foreach($ads_before_sidebar_content as $ads_before_sidebar_content_key =>
                    $ad_before_sidebar_content)
                    <div class="row mb-5">
                        @if($ad_before_sidebar_content->advertisement_alignment ==
                        \App\Advertisement::AD_ALIGNMENT_LEFT)
                        <div class="col-12 text-left">
                            <div>
                                {!! $ad_before_sidebar_content->advertisement_code !!}
                            </div>
                        </div>
                        @elseif($ad_before_sidebar_content->advertisement_alignment ==
                        \App\Advertisement::AD_ALIGNMENT_CENTER)
                        <div class="col-12 text-center">
                            <div>
                                {!! $ad_before_sidebar_content->advertisement_code !!}
                            </div>
                        </div>
                        @elseif($ad_before_sidebar_content->advertisement_alignment ==
                        \App\Advertisement::AD_ALIGNMENT_RIGHT)
                        <div class="col-12 text-right">
                            <div>
                                {!! $ad_before_sidebar_content->advertisement_code !!}
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                    @endif
                  
                    <div class="row mb-4 align-items-center">
                        <div class="col-12">
                            @if(Auth::check())
                            @if(Auth::user()->id != $item->user_id)
                            @if(Auth::user()->isAdmin())
                            <a target="_blank" href="{{ route('admin.messages.create', ['item'=>$item->id]) }}"
                                class="btn btn-outline-primary btn-block rounded">{{ __('frontend.item.send-message') }}</a>
                            @else
                            <a target="_blank" href="{{ route('user.messages.create', ['item'=>$item->id]) }}"
                                class="btn btn-outline-primary btn-block rounded">{{ __('frontend.item.send-message') }}</a>
                            @endif
                            @if(Auth::user()->isAdmin())
                            <a target="_blank" href="{{ route('admin.items.edit', $item->id) }}"
                                class="btn btn-outline-primary btn-block rounded">{{ __('frontend.item.edit-listing') }}</a>
                            @endif
                            @else
                            @if(Auth::user()->isAdmin())
                            <a target="_blank" href="{{ route('admin.items.edit', $item->id) }}"
                                class="btn btn-outline-primary btn-block rounded">{{ __('frontend.item.edit-listing') }}</a>
                            @else
                            <a target="_blank" href="{{ route('user.items.edit', $item->id) }}"
                                class="btn btn-outline-primary btn-block rounded">{{ __('frontend.item.edit-listing') }}</a>
                            @endif
                            @endif
                            @else
                            <a target="_blank" href="{{ route('user.messages.create', ['item'=>$item->id]) }}"
                                class="btn btn-outline-primary btn-block rounded">{{ __('frontend.item.send-message') }}</a>
                            @endif
                        </div>
                    </div>
                    @include('frontend.partials.search.side')
                    @if($ads_after_sidebar_content->count() > 0)
                    @foreach($ads_after_sidebar_content as $ads_after_sidebar_content_key => $ad_after_sidebar_content)
                    <div class="row mt-5">
                        @if($ad_after_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                        <div class="col-12 text-left">
                            <div>
                                {!! $ad_after_sidebar_content->advertisement_code !!}
                            </div>
                        </div>
                        @elseif($ad_after_sidebar_content->advertisement_alignment ==
                        \App\Advertisement::AD_ALIGNMENT_CENTER)
                        <div class="col-12 text-center">
                            <div>
                                {!! $ad_after_sidebar_content->advertisement_code !!}
                            </div>
                        </div>
                        @elseif($ad_after_sidebar_content->advertisement_alignment ==
                        \App\Advertisement::AD_ALIGNMENT_RIGHT)
                        <div class="col-12 text-right">
                            <div>
                                {!! $ad_after_sidebar_content->advertisement_code !!}
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@if($similar_items->count() > 0)
<div class="site-section bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.item.similar-listings') }}</h2>
            </div>
        </div>
        <div class="row mt-5">
            @foreach($similar_items as $key => $similar_item)
            <div class="col-lg-6">
                <div class="d-block d-md-flex listing">
                    <a href="{{ route('page.item', $similar_item->item_slug) }}" class="img d-block"
                        style="background-image: url({{ !empty($similar_item->item_image_small) ? Storage::disk('public')->url('item/' . $similar_item->item_image_small) : (!empty($similar_item->item_image) ? Storage::disk('public')->url('item/' . $similar_item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_small.jpg')) }})"></a>
                    <div class="lh-content">
                        <a href="{{ route('page.category', $similar_item->category->category_slug) }}">
                            <span class="category">{{ $similar_item->category->category_name }}</span>
                        </a>
                        <h3><a
                                href="{{ route('page.item', $similar_item->item_slug) }}">{{ $similar_item->item_title }}</a>
                        </h3>
                        <address>
                            <a
                                href="{{ route('page.city', ['state_slug'=>$similar_item->state->state_slug, 'city_slug'=>$similar_item->city->city_slug]) }}">{{ $similar_item->city->city_name }}</a>,
                            <a
                                href="{{ route('page.state', ['state_slug'=>$similar_item->state->state_slug]) }}">{{ $similar_item->state->state_name }}</a>
                        </address>
                        @if($similar_item->getCountRating() > 0)
                        <div class="row">
                            <div class="col-12">
                                <div class="pl-0 rating_stars rating_stars_{{ $similar_item->item_slug }}"
                                    data-id="rating_stars_{{ $similar_item->item_slug }}"
                                    data-rating="{{ $similar_item->getAverageRating() }}"></div>
                                <address class="mt-1">
                                    @if($similar_item->getCountRating() == 1)
                                    {{ '(' . $similar_item->getCountRating() . ' ' . __('review.frontend.review') . ')' }}
                                    @else
                                    {{ '(' . $similar_item->getCountRating() . ' ' . __('review.frontend.reviews') . ')' }}
                                    @endif
                                </address>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            @if(!empty($similar_item->user->user_image))
                            <div class="col-2 pr-0">
                                @if(empty($similar_item->user->user_image))
                                <img src="{{ asset('frontend/images/placeholder/profile-'. intval($similar_item->user->id % 10) . '.jpg') }}"
                                    alt="Image" class="img-fluid rounded-circle">
                                @else
                                <img src="{{ Storage::disk('public')->url('user/' . $similar_item->user->user_image) }}"
                                    alt="{{ $similar_item->user->name }}" class="img-fluid rounded-circle">
                                @endif
                                @endif
                            </div>
                            <div class="col-10 line-height-1-2">
                                <div class="row pb-1">
                                    @if(!empty( $similar_item->user->name))
                                    <div class="col-12">
                                        <span class="font-size-13">{{ $similar_item->user->name }}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="row line-height-1-0">
                                    <div class="col-12">
                                        @if($similar_item->totalComments() > 1)
                                        <span class="review">{{ $similar_item->totalComments() . ' comments' }}</span>
                                        @elseif($similar_item->totalComments() == 1)
                                        <span class="review">{{ $similar_item->totalComments() . ' comment' }}</span>
                                        @endif
                                        <span class="review">{{ $similar_item->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@if($nearby_items->count() > 0)
<div class="site-section bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.item.nearby-listings') }}</h2>
            </div>
        </div>
        <div class="row mt-5">
            @foreach($nearby_items as $key => $nearby_item)
            <div class="col-lg-6">
                <div class="d-block d-md-flex listing">
                    <a href="{{ route('page.item', $nearby_item->item_slug) }}" class="img d-block"
                        style="background-image: url({{ !empty($nearby_item->item_image_small) ? Storage::disk('public')->url('item/' . $nearby_item->item_image_small) : (!empty($nearby_item->item_image) ? Storage::disk('public')->url('item/' . $nearby_item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_small.jpg')) }})"></a>
                    <div class="lh-content">
                        <a href="{{ route('page.category', $nearby_item->category->category_slug) }}">
                            <span class="category">{{ $nearby_item->category->category_name }}</span>
                        </a>
                        <h3><a
                                href="{{ route('page.item', $nearby_item->item_slug) }}">{{ $nearby_item->item_title }}</a>
                        </h3>
                        <address>
                            <a
                                href="{{ route('page.city', ['state_slug'=>$nearby_item->state->state_slug, 'city_slug'=>$nearby_item->city->city_slug]) }}">{{ $nearby_item->city->city_name }}</a>,
                            <a
                                href="{{ route('page.state', ['state_slug'=>$nearby_item->state->state_slug]) }}">{{ $nearby_item->state->state_name }}</a>
                        </address>
                        @if($nearby_item->getCountRating() > 0)
                        <div class="row">
                            <div class="col-12">
                                <div class="pl-0 rating_stars rating_stars_{{ $nearby_item->item_slug }}"
                                    data-id="rating_stars_{{ $nearby_item->item_slug }}"
                                    data-rating="{{ $nearby_item->getAverageRating() }}"></div>
                                <address class="mt-1">
                                    @if($nearby_item->getCountRating() == 1)
                                    {{ '(' . $nearby_item->getCountRating() . ' ' . __('review.frontend.review') . ')' }}
                                    @else
                                    {{ '(' . $nearby_item->getCountRating() . ' ' . __('review.frontend.reviews') . ')' }}
                                    @endif
                                </address>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-2 pr-0">
                                @if(!empty($nearby_item->user->user_image))
                                @if(empty($nearby_item->user->user_image))
                                <img src="{{ asset('frontend/images/placeholder/profile-'. intval($nearby_item->user->id % 10) . '.jpg') }}"
                                    alt="Image" class="img-fluid rounded-circle">
                                @else
                                <img src="{{ Storage::disk('public')->url('user/' . $nearby_item->user->user_image) }}"
                                    alt="{{ $nearby_item->user->name }}" class="img-fluid rounded-circle">
                                @endif
                                @endif
                            </div>
                            <div class="col-10 line-height-1-2">
                                <div class="row pb-1">
                                    @if(!empty($nearby_item->user->user_image))
                                    <div class="col-12">
                                        <span class="font-size-13">{{ $nearby_item->user->name }}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="row line-height-1-0">
                                    <div class="col-12">
                                        @if($nearby_item->totalComments() > 1)
                                        <span class="review">{{ $nearby_item->totalComments() . ' comments' }}</span>
                                        @elseif($nearby_item->totalComments() == 1)
                                        <span class="review">{{ $nearby_item->totalComments() . ' comment' }}</span>
                                        @endif
                                        <span class="review">{{ $nearby_item->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
<!-- Modal - share -->
<div class="modal fade" id="share-modal" tabindex="-1" role="dialog" aria-labelledby="share-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('frontend.item.share-listing') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>{{ __('frontend.item.share-listing-social-media') }}</p>
                        <!-- Create link with share to Facebook -->
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-facebook" href=""
                            data-social="facebook">
                            <i class="fab fa-facebook-f"></i>
                            {{ __('social_share.facebook') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-twitter" href=""
                            data-social="twitter">
                            <i class="fab fa-twitter"></i>
                            {{ __('social_share.twitter') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-linkedin" href=""
                            data-social="linkedin">
                            <i class="fab fa-linkedin-in"></i>
                            {{ __('social_share.linkedin') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-blogger" href=""
                            data-social="blogger">
                            <i class="fab fa-blogger-b"></i>
                            {{ __('social_share.blogger') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-pinterest" href=""
                            data-social="pinterest">
                            <i class="fab fa-pinterest-p"></i>
                            {{ __('social_share.pinterest') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-evernote" href=""
                            data-social="evernote">
                            <i class="fab fa-evernote"></i>
                            {{ __('social_share.evernote') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-reddit" href=""
                            data-social="reddit">
                            <i class="fab fa-reddit-alien"></i>
                            {{ __('social_share.reddit') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-buffer" href=""
                            data-social="buffer">
                            <i class="fab fa-buffer"></i>
                            {{ __('social_share.buffer') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wordpress" href=""
                            data-social="wordpress">
                            <i class="fab fa-wordpress-simple"></i>
                            {{ __('social_share.wordpress') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-weibo" href="" data-social="weibo">
                            <i class="fab fa-weibo"></i>
                            {{ __('social_share.weibo') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-skype" href="" data-social="skype">
                            <i class="fab fa-skype"></i>
                            {{ __('social_share.skype') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-telegram" href=""
                            data-social="telegram">
                            <i class="fab fa-telegram-plane"></i>
                            {{ __('social_share.telegram') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-viber" href="" data-social="viber">
                            <i class="fab fa-viber"></i>
                            {{ __('social_share.viber') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-whatsapp" href=""
                            data-social="whatsapp">
                            <i class="fab fa-whatsapp"></i>
                            {{ __('social_share.whatsapp') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wechat" href=""
                            data-social="wechat">
                            <i class="fab fa-weixin"></i>
                            {{ __('social_share.wechat') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-line" href="" data-social="line">
                            <i class="fab fa-line"></i>
                            {{ __('social_share.line') }}
                        </a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <p>{{ __('frontend.item.share-listing-email') }}</p>
                        @if(!Auth::check())
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ __('frontend.item.login-require') }}
                                </div>
                            </div>
                        </div>
                        @endif
                        <form action="{{ route('page.item.email', ['item_slug' => $item->item_slug]) }}" method="POST">
                            @csrf
                            <div class="form-row mb-3">
                                <div class="col-md-4">
                                    <label for="item_share_email_name"
                                        class="text-black">{{ __('frontend.item.name') }}</label>
                                    <input id="item_share_email_name" type="text"
                                        class="form-control @error('item_share_email_name') is-invalid @enderror"
                                        name="item_share_email_name" value="{{ old('item_share_email_name') }}"
                                        {{ Auth::check() ? '' : 'disabled' }}>
                                    @error('item_share_email_name')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="item_share_email_from_email"
                                        class="text-black">{{ __('frontend.item.email') }}</label>
                                    <input id="item_share_email_from_email" type="email"
                                        class="form-control @error('item_share_email_from_email') is-invalid @enderror"
                                        name="item_share_email_from_email"
                                        value="{{ old('item_share_email_from_email') }}"
                                        {{ Auth::check() ? '' : 'disabled' }}>
                                    @error('item_share_email_from_email')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="item_share_email_to_email"
                                        class="text-black">{{ __('frontend.item.email-to') }}</label>
                                    <input id="item_share_email_to_email" type="email"
                                        class="form-control @error('item_share_email_to_email') is-invalid @enderror"
                                        name="item_share_email_to_email" value="{{ old('item_share_email_to_email') }}"
                                        {{ Auth::check() ? '' : 'disabled' }}>
                                    @error('item_share_email_to_email')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row mb-3">
                                <div class="col-md-12">
                                    <label for="item_share_email_note"
                                        class="text-black">{{ __('frontend.item.add-note') }}</label>
                                    <textarea class="form-control @error('item_share_email_note') is-invalid @enderror"
                                        id="item_share_email_note" rows="3" name="item_share_email_note"
                                        {{ Auth::check() ? '' : 'disabled' }}>{{ old('item_share_email_note') }}</textarea>
                                    @error('item_share_email_note')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary py-2 px-4 text-white rounded"
                                        {{ Auth::check() ? '' : 'disabled' }}>
                                        {{ __('frontend.item.send-email') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded"
                    data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="{{ asset('frontend/vendor/leaflet/leaflet.js') }}"></script>
<script src="{{ asset('frontend/vendor/justified-gallery/jquery.justifiedGallery.min.js') }}"></script>
<script src="{{ asset('frontend/vendor/colorbox/jquery.colorbox-min.js') }}"></script>
<script src="{{ asset('frontend/vendor/goodshare/goodshare.min.js') }}"></script>
<script src="{{ asset('backend/vendor/jquery-bar-rating/dist/jquery.barrating.min.js') }}"></script>
<script>
    $(document).ready(function(){
            /**
             * Start initial map
             */
            var map = L.map('mapid-item', {
                center: [{{ $item->item_lat }}, {{ $item->item_lng }}],
                zoom: 13,
                scrollWheelZoom: false,
            });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            L.marker([{{ $item->item_lat }}, {{ $item->item_lng }}]).addTo(map);
            /**
             * End initial map
             */
            /**
             * Start initial image gallery justify gallery
             */
            $("#item-image-gallery").justifiedGallery({
                rowHeight : 150,
                maxRowHeight: 180,
                lastRow : 'nojustify',
                margins : 3,
                captions: false,
                randomize: true,
                rel : 'item-image-gallery-thumb', //replace with 'gallery1' the rel attribute of each link
            }).on('jg.complete', function () {
                $(this).find('a').colorbox({
                    maxWidth : '95%',
                    maxHeight : '95%',
                    opacity : 0.8,
                });
            });
            /**
             * End initial image gallery justify gallery
             */
            $('.item-share-button').on('click', function(){
                $('#share-modal').modal('show');
            });
            @error('item_share_email_name')
            $('#share-modal').modal('show');
            @enderror
            @error('item_share_email_from_email')
            $('#share-modal').modal('show');
            @enderror
            @error('item_share_email_to_email')
            $('#share-modal').modal('show');
            @enderror
            @error('item_share_email_note')
            $('#share-modal').modal('show');
            @enderror
            $('#item-save-button').on('click', function(){
                $("#item-save-button").addClass("disabled");
                $("#item-save-form").submit();
            });
            $('#item-saved-button').on('click', function(){
                $("#item-saved-button").off("mouseenter");
                $("#item-saved-button").off("mouseleave");
                $("#item-saved-button").addClass("disabled");
                $("#item-unsave-form").submit();
            });
            $("#item-saved-button").on('mouseenter', function(){
                $("#item-saved-button").attr("class", "btn btn-danger rounded text-white");
                $("#item-saved-button").html("<i class=\"far fa-trash-alt\"></i> <?php echo __('frontend.item.unsave') ?>");
            });
            $("#item-saved-button").on('mouseleave', function(){
                $("#item-saved-button").attr("class", "btn btn-warning rounded text-white");
                $("#item-saved-button").html("<i class=\"fas fa-check\"></i> <?php echo __('frontend.item.saved') ?>");
            });
            /**
             * Start rating star
             */
            @if($item->getCountRating() > 0)
            $(".rating_stars_header").rateYo({
                spacing: "5px",
                starWidth: "23px",
                readOnly: true,
                rating: {{ $item->getAverageRating() }}
            });
            $('.review_rating_stars').barrating({
                theme: 'bootstrap-stars',
                allowEmpty: true,
                readonly: true
            });
            @endif
            /**
             * End rating star
             */
        });
</script>
<script>
    var $star_rating = $('.star-rating .fa');
var SetRatingStar = function() {
  return $star_rating.each(function() {
    if (parseInt($star_rating.siblings('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
      return $(this).removeClass('fa-star-o').addClass('fa-star');
    } else {
      return $(this).removeClass('fa-star').addClass('fa-star-o');
    }
  });
};
$star_rating.on('click', function() {
  $star_rating.siblings('input.rating-value').val($(this).data('rating'));
  return SetRatingStar();
});
SetRatingStar();
$(document).ready(function() {
});
</script>
@include('frontend.partials.search.js')
@endsection