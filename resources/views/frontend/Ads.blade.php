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
                            <h1>{{ __('frontend.header.a1') }}</h1>
                            <p class="mb-0"></p>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


   <div class="site-section bg-light">
    <div class="container">

          <div class="row mb-4">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.header.a1') }}</h2>
            </div>
        </div>
   


        </div>




<div class="site-section" data-aos="fade">
    <div class="container">
   

        <div class="row">

            @if(count($ads))
                @foreach($ads as $key => $item)
                    <div class="col-md-6 mb-4 mb-lg-4 col-lg-4">

                        <div class="listing-item listing">
                            <div class="listing-image">
                                <img src="{{ !empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.jpg')) }}" alt="Image" class="img-fluid">
                            </div>
                            <div class="listing-item-content">

                                <a class="px-3 mb-3 category" href="{{ route('page.category', $item->category->category_slug) }}">{{ $item->category->category_name }}</a>
                                <h2 class="mb-1"><a href="{{ route('page.item', $item->item_slug) }}">{{ $item->item_title }}</a></h2>
                                <span class="address">
                                    <a href="{{ route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug]) }}">{{ $item->city->city_name }}</a>,
                                    <a href="{{ route('page.state', ['state_slug'=>$item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                                </span>

                                @if($item->getCountRating() > 0)
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="pl-0 rating_stars rating_stars_{{ $item->item_slug }}" data-id="rating_stars_{{ $item->item_slug }}" data-rating="{{ $item->getAverageRating() }}"></div>
                                            <address class="mt-1">
                                                @if($item->getCountRating() == 1)
                                                    <span>{{ '(' . $item->getCountRating() . ' ' . __('review.frontend.review') . ')' }}</span>
                                                @else
                                                    <span>{{ '(' . $item->getCountRating() . ' ' . __('review.frontend.reviews') . ')' }}</span>
                                                @endif
                                            </address>
                                        </div>
                                    </div>
                                @endif

                                <div class="row mt-1">
                                    <div class="col-2 pr-0">
                                         @if(!empty($item->user->nam))
                                        @if(empty($item->user->user_image))
                                            <img src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.jpg') }}" alt="Image" class="img-fluid rounded-circle">
                                        @else

                                            <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}" alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                                        @endif
                                         @endif
                                    </div>
                                    <div class="col-10 line-height-1-2">
  @if(!empty($item->user->nam))
                                        <div class="row pb-1">
                                            <div class="col-12">
                                                
                                                <span class="font-size-13">{{ $item->user->name }}</span>
                                            </div>
                                        </div>
                                         @endif
                                        <div class="row line-height-1-0">
                                            <div class="col-12">
                                                @if($item->totalComments() > 1)
                                                    <span class="review">{{ $item->totalComments() . ' comments' }}</span>
                                                @elseif($item->totalComments() == 1)
                                                    <span class="review">{{ $item->totalComments() . ' comment' }}</span>
                                                @endif
                                                <span class="review">{{ $item->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                @endforeach
            @endif

        </div>
        {{ $ads->links() }}
    </div>
</div>


@endsection

@section('scripts')
@endsection
