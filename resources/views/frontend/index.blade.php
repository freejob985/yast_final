@extends('frontend.layouts.app')

@section('styles')
@endsection
@section('content')
<div class="site-blocks-cover overlay" style="background-image: url( {{ asset('frontend/images/placeholder/header-1.jpg') }});" data-aos="fade" data-stellar-background-ratio="0.5">
    <div class="container">

        <!-- Start hero section desktop view-->
        <div class="row align-items-center justify-content-center text-center d-none d-md-flex">
            <div class="col-md-12">
                <div class="row justify-content-center mb-1">
                    <div class="col-md-10 text-center">
                        <h1 class="" data-aos="fade-up">اختار افضل الشركات و الصنايعية فى مدينتك</h1>
                        <p data-aos="fade-up" data-aos-delay="100">{{ __('frontend.homepage.description') }}</p>
                    </div>
                </div>
              
                <div class="form-search-wrap" data-aos="fade-up" data-aos-delay="200">
                    @include('frontend.partials.search.head')
                </div>

            </div>
        </div>
        <!-- End hero section desktop view-->
        <!-- Start hero section desktop view-->
        <div class="row align-items-center justify-content-center text-center d-md-none mt-5">
            <div class="col-md-12">
                <div class="row justify-content-center mb-1">
                    <div class="col-md-10 text-center">
                        <h1 class="" data-aos="fade-up">اختار افضل الشركات و الصنايعية فى مدينتك</h1>
                        <p data-aos="fade-up" data-aos-delay="100">{{ __('frontend.homepage.description') }}</p>
                    </div>
                </div>
                <div class="form-search-wrap" data-aos="fade-up" data-aos-delay="200">
                    @include('frontend.partials.search.head')
                </div>
            </div>

        </div>
        <!-- End hero section desktop view-->
   


    </div>
</div>
<section class="dc-haslayout">
				<div class="dc-haslayout dc-bgcolor dc-main-section dc-workholder">
					<div class="container">
						<div class="row justify-content-center align-self-center">
							<div class="col-xs-12 col-sm-12 col-md-8 push-md-2 col-lg-8 push-lg-2">
								<div class="dc-sectionhead dc-text-center">
									<div class="dc-sectiontitle">
										<h2>كيف تستخدم ياسطا؟</h2>
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="dc-haslayout dc-main-section dc-workdetails-holder">
					<div class="container">
						<div class="row">
							<div class="col-12 col-sm-6 col-md-4 col-lg-4">
								<div class="dc-workdetails">
									<div class="dc-workdetail">
										<figure>
											<img src="https://image.freepik.com/free-vector/login-page-laptop-screen-notebook-online-login-form-sign-page-user-profile-access-account-concepts-vector-illustration_100456-187.jpg
										" alt="img description">
										</figure>
									</div>
									<div class="dc-title">
										
										<h3><a href="javascript:void(0);">سجل</a></h3>
										<span>اختار مدة الاشتراك المناسبة لك
 </span>
									</div>
								</div>
							</div>
							<div class="col-12 col-sm-6 col-md-4 col-lg-4">
								<div class="dc-workdetails dc-workdetails-border">
									<div class="dc-workdetail">
										<figure>
											<img src="	https://image.freepik.com/free-vector/characters-people-searching-through-files_53876-66241.jpg" alt="img description">
										</figure>
									</div>
									<div class="dc-title">
										
										<h3><a href="javascript:void(0);">ابحث</a></h3>
										<span>قارن و اختار من الشركات و الصنايعية المتخصصين فى جميع مجالات الانشاء و التشطيب
</span>
									</div>
								</div>
							</div>
							<div class="col-12 col-sm-6 col-md-4 col-lg-4">
								<div class="dc-workdetails dc-workdetails-bordertwo">
									<div class="dc-workdetail">
										<figure>
											<img src="https://image.freepik.com/free-vector/construction-team-working-site_74855-4775.jpg" alt="img description">
										</figure>
									</div>
									<div class="dc-title">
									    	<h3><a href="javascript:void(0);">استفيد</a></h3>
										<span>استمتع بالتواصل المباشر مع افضل شركات و صنايعية الانشاء و التشطيب، و قم بطلب مقايسات لأعمالك بسهولة</span>
									
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
<div class="site-section bg-white">
    <div class="container">

        <!-- Start featured section desktop view-->
        <div class="overlap-category mb-5 d-none d-md-block">
            <h1 style="color: #f89d13!important;font-size:22px;">اهم الأقسام</h1>
            <div class="row align-items-stretch no-gutters">

                @if(count($categories))
                    @foreach($categories as $key => $category)
                        <div class="col-sm-6 col-xs-6 col-md-3 mb-4 mb-lg-0 col-lg-3" data-aos="fade-up">
                            <a href="{{ route('page.category', $category->category_slug) }}" class="popular-category h-100">

                                     @if($category->category_icon)
                                   <img src="{{Request::root()}}/laravel_project/public/files/{{ $category->fileToUpload }}" alt="{{ $category->category_name }}" >
                                    
                                @else
                                   <img src="{{Request::root()}}/laravel_project/public/files/{{ $category->fileToUpload }}" alt="{{ $category->category_name }}" >
                                @endif
                                <span class="caption mb-2 d-block">{{ $category->category_name }}</span>
                                <span class="number">{{ number_format($category->items_count) }}</span>
                            </a>
                        </div>
                    @endforeach
                        <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2" data-aos="fade-up">
                            <a href="{{ route('page.categories') }}" class="popular-category h-100">

                                <span class="icon"><span><i class="fas fa-th"></i></span></span>
                                <span class="caption mb-2 d-block">{{ __('frontend.homepage.all-categories') }}</span>
                                <span class="number">{{ number_format($total_items_count) }}</span>
                            </a>
                        </div>
                @else
                    <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2" data-aos="fade-up">
                        <p>{{ __('frontend.homepage.no-categories') }}</p>
                    </div>
                @endif
            </div>
        </div>
        <!-- End featured section desktop view-->

        <!-- Start featured section mobile view-->
        <div class="overlap-category-sm mb-5 d-md-none">
            
            <div class="row align-items-stretch no-gutters">
<div class="row mb-4">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary">أقسام الموقع</h2>
            </div>
        </div>
                @if(count($categories))
                    @foreach($categories as $key => $category)
                        <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2" data-aos="fade-up">
                            <a href="{{ route('page.category', $category->category_slug) }}" class="popular-category h-100">

                                @if($category->category_icon)
                                   <img src="{{ $category->category_icon }}" alt="{{ $category->category_name }}" >
                                @else
                                   <img src="{{ $category->category_icon }}" alt="{{ $category->category_name }}" >
                                @endif
                                <span class="caption mb-2 d-block">{{ $category->category_name }}</span>
                                <span class="number">{{ number_format($category->items_count) }}</span>
                            </a>
                        </div>
                    @endforeach
                    <div data-aos="fade-up" class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2" data-aos="fade-up">
                        <a href="{{ route('page.categories') }}" class="popular-category h-100">

                            <span class="icon"><span><i class="fas fa-th"></i></span></span>
                            <span class="caption mb-2 d-block">{{ __('frontend.homepage.all-categories') }}</span>
                            <span class="number">{{ number_format($total_items_count) }}</span>
                        </a>
                    </div>
                @else
                    <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2" data-aos="fade-up">
                        <p>{{ __('frontend.homepage.no-categories') }}</p>
                    </div>
                @endif
            </div>
        </div>
         </div>
        </div>
        <!-- End featured section mobile view-->
<div class="site-section bg-white">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.featured-ads') }}</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12  block-13">
                <div class="owl-carousel nonloop-block-13" >

                    @if(count($paid_items))
                        @foreach($paid_items as $key => $item)
                            <div class="d-block d-md-flex listing vertical" data-aos="fade-up">
                                <a href="{{ route('page.item', $item->item_slug) }}" class="img d-block" style="background-image: url({{ !empty($item->item_image_small) ? Storage::disk('public')->url('item/' . $item->item_image_small) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_small.jpg')) }})"></a>
                                <div class="lh-content">
                                    <a href="{{ route('page.category', $item->category->category_slug) }}">
                                    <span class="category">{{ $item->category->category_name }}</span>
                                    </a>

                                    <h3><a href="{{ route('page.item', $item->item_slug) }}">{{ str_limit($item->item_title, 44, '...') }}</a></h3>
                                    <address>
                                        <a href="{{ route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug]) }}">{{ $item->city->city_name }}</a>,
                                        <a href="{{ route('page.state', ['state_slug'=>$item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                                    </address>

                                    @if($item->getCountRating() > 0)
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="pl-0 rating_stars rating_stars_{{ $item->item_slug }}" data-id="rating_stars_{{ $item->item_slug }}" data-rating="{{ $item->getAverageRating() }}"></div>
                                                <address class="mt-1">
                                                    @if($item->getCountRating() == 1)
                                                        {{ '(' . $item->getCountRating() . ' ' . __('review.frontend.review') . ')' }}
                                                    @else
                                                        {{ '(' . $item->getCountRating() . ' ' . __('review.frontend.reviews') . ')' }}
                                                    @endif
                                                </address>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-3 pr-1">
                                             @if(empty($item->user->user_image))
                                            @if(!empty($item->user->user_image))
                                                <img src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.jpg') }}" alt="Image" class="img-fluid rounded-circle">
                                            @else

                                                <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}" alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                                            @endif
                                             @endif
                                        </div>
                                        <div class="col-9 line-height-1-2">

                                            <div class="row pb-1">
                                                <div class="col-12">
                                                    <span class="font-size-13">{{ $item->user->name }}</span>
                                                </div>
                                            </div>
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
                        @endforeach
                    @else
                        <div class="d-block d-md-flex listing vertical">
                            No featured listings
                        </div>
                    @endif

                </div>
            </div>


        </div>
       {{-- --}}
  
    </div>
</div>

<div class="site-section bg-light">
    <div class="container">

          <div class="row mb-4">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.header.a1') }}</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12  block-13">
                <div class="owl-carousel nonloop-block-13">

                    @if(count($ads))
                        @foreach($ads as $key => $item)
                            <div class="d-block d-md-flex listing vertical">
                                <a href="{{ route('page.item', $item->item_slug) }}" class="img d-block" style="background-image: url({{ !empty($item->item_image_small) ? Storage::disk('public')->url('item/' . $item->item_image_small) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_small.jpg')) }})"></a>
                                <div class="lh-content">
                                    <a href="{{ route('page.category', $item->category->category_slug) }}">
                                    <span class="category">{{ $item->category->category_name }}</span>
                                    </a>

                                    <h3><a href="{{ route('page.item', $item->item_slug) }}">{{ str_limit($item->item_title, 44, '...') }}</a></h3>
                                    <address>
                                        <a href="{{ route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug]) }}">{{ $item->city->city_name }}</a>,
                                        <a href="{{ route('page.state', ['state_slug'=>$item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                                    </address>

                                    @if($item->getCountRating() > 0)
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="pl-0 rating_stars rating_stars_{{ $item->item_slug }}" data-id="rating_stars_{{ $item->item_slug }}" data-rating="{{ $item->getAverageRating() }}"></div>
                                                <address class="mt-1">
                                                    @if($item->getCountRating() == 1)
                                                        {{ '(' . $item->getCountRating() . ' ' . __('review.frontend.review') . ')' }}
                                                    @else
                                                        {{ '(' . $item->getCountRating() . ' ' . __('review.frontend.reviews') . ')' }}
                                                    @endif
                                                </address>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-3 pr-1">
                                             @if(!empty($item->user->name))
                                            @if(empty($item->user->user_image))
                                                <img src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.jpg') }}" alt="Image" class="img-fluid rounded-circle">
                                            @else

                                                <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}" alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                                            @endif
                                            @endif
                                        </div>
                                        <div class="col-9 line-height-1-2">
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
                        @endforeach
                        
                    @else
                        <div class="d-block d-md-flex listing vertical">
                            No featured listings
                        </div>
                    @endif

                </div>
            </div>


        </div>
           </div>
   <div class="col-12 text-center mt-4">
                <a href="/ads" class="btn btn-primary rounded py-2 px-4 text-white">كل المقايسات</a>
            </div>

        </div>

@if(count($all_testimonials))
<div class="site-section bg-white">
    <div class="container">

        <div class="row justify-content-center mb-5">
            <div class="col-md-7 text-center border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.testimonials') }}</h2>
            </div>
        </div>

        <div class="slide-one-item home-slider owl-carousel">

                @foreach($all_testimonials as $key => $testimonial)
                    <div>
                        <div class="testimonial">
                            <figure class="mb-4">
                                @if(empty($testimonial->testimonial_image))
                                    <img src="{{ asset('frontend/images/placeholder/profile-'. intval($testimonial->id % 10) . '.jpg') }}" alt="Image" class="img-fluid mb-3">
                                @else
                                    <img src="{{ Storage::disk('public')->url('testimonial/' . $testimonial->testimonial_image) }}" alt="Image" class="img-fluid mb-3">
                                @endif
                                <p>
                                    {{ $testimonial->testimonial_name }}
                                    @if($testimonial->testimonial_job_title)
                                        {{ '• ' . $testimonial->testimonial_job_title }}
                                    @endif
                                    @if($testimonial->testimonial_company)
                                        {{ 'of ' . $testimonial->testimonial_company }}
                                    @endif
                                </p>
                            </figure>
                            <blockquote>
                                <p>{{ $testimonial->testimonial_description }}</p>
                            </blockquote>
                        </div>
                    </div>
                @endforeach
                

        </div>
    </div>
</div>
@endif


        
        
        

<div class="site-section bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.recent-listings') }}</h2>
            </div>
        </div>
        <div class="row mt-5">

            @if(count($latest_items))
                @foreach($latest_items as $key => $item)
                    <div class="col-lg-6" data-aos="fade-up">
                        <div class="d-block d-md-flex listing">
                            <a href="{{ route('page.item', $item->item_slug) }}" class="img d-block" style="background-image: url({{ !empty($item->item_image_small) ? Storage::disk('public')->url('item/' . $item->item_image_small) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_small.jpg')) }})"></a>
                            <div class="lh-content">
                                <a href="{{ route('page.category', $item->category->category_slug) }}">
                                <span class="category">{{ $item->category->category_name }}</span>
                                </a>

                                <h3><a href="{{ route('page.item', $item->item_slug) }}">{{ $item->item_title }}</a></h3>
                                <address>
                                    <a href="{{ route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug]) }}">{{ $item->city->city_name }}</a>,
                                    <a href="{{ route('page.state', ['state_slug'=>$item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                                </address>

                                @if($item->getCountRating() > 0)
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="pl-0 rating_stars rating_stars_{{ $item->item_slug }}" data-id="rating_stars_{{ $item->item_slug }}" data-rating="{{ $item->getAverageRating() }}"></div>
                                            <address class="mt-1">
                                                @if($item->getCountRating() == 1)
                                                    {{ '(' . $item->getCountRating() . ' ' . __('review.frontend.review') . ')' }}
                                                @else
                                                    {{ '(' . $item->getCountRating() . ' ' . __('review.frontend.reviews') . ')' }}
                                                @endif
                                            </address>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-2 pr-0">
                                          @if(!empty($item->user->user_image))
                                        @if(empty($item->user->user_image))
                                            <img src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.jpg') }}" alt="Image" class="img-fluid rounded-circle">
                                        @else

                                            <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}" alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                                        @endif
                                          @endif
                                    </div>
                                    <div class="col-10 line-height-1-2">
 @if(!empty($item->user->name))
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
    </div>
</div>
@if(count($recent_blog))
<div class="site-section bg-white">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-7 text-center border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.our-blog') }}</h2>
                <p class="color-black-opacity-5">{{ __('frontend.homepage.our-blog-decr') }}</p>
            </div>
        </div>
        <div class="row mb-3 align-items-stretch">

                @foreach($recent_blog as $key => $post)
                    <div class="col-md-6 col-lg-4 mb-4 mb-lg-4" data-aos="fade-up">
                        <div class="h-entry">
                            @if(empty($post->featured_image))
                                <div class="mb-3" style="min-height:300px;border-radius: 0.25rem;background-image:url({{ asset('frontend/images/placeholder/full_item_feature_image_medium.jpg') }});background-size:cover;background-repeat:no-repeat;background-position: center center;"></div>
                            @else
                                <div class="mb-3" style="min-height:300px;border-radius: 0.25rem;background-image:url({{ url('laravel_project/public' . $post->featured_image) }});background-size:cover;background-repeat:no-repeat;background-position: center center;"></div>
                            @endif
                            <h2 class="font-size-regular"><a href="{{ route('page.blog.show', $post->slug) }}" class="text-black">{{ $post->title }}</a></h2>
                            <div class="meta mb-3">
                                by {{ $post->user()->get()->first()->name }}<span class="mx-1">&bullet;</span> {{ $post->updated_at->diffForHumans() }} <span class="mx-1">&bullet;</span>
                                @if($post->topic()->get()->count() != 0)
                                <a href="{{ route('page.blog.topic', $post->topic()->get()->first()->slug) }}">{{ $post->topic()->get()->first()->name }}</a>
                                @else
                                    {{ __('frontend.blog.uncategorized') }}
                                @endif

                            </div>
                            <p>{{ str_limit(preg_replace("/&#?[a-z0-9]{2,8};/i"," ", strip_tags($post->body)), 200) }}</p>
                        </div>
                    </div>
                @endforeach

            <div class="col-12 text-center mt-4">
                <a href="{{ route('page.blog') }}" class="btn btn-primary rounded py-2 px-4 text-white">{{ __('frontend.homepage.all-posts') }}</a>
            </div>
        </div>
    </div>
</div>
@endif



@endsection

@section('scripts')

    @include('frontend.partials.search.js')


@endsection
