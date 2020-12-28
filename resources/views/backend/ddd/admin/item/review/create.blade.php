@extends('backend.admin.layouts.app')

@section('styles')

    <link href="{{ asset('backend/vendor/jquery-bar-rating/dist/themes/bootstrap-stars.css') }}" rel="stylesheet" />

@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('review.backend.write-a-review') }}</h1>
            <p class="mb-4">{{ __('review.backend.write-a-review-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.items.reviews.index') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-backspace"></i>
                </span>
                <span class="text">{{ __('backend.shared.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

            <div class="row">
                <div class="col-3">
                    @if(empty($item->item_image))
                        <img id="image_preview" src="{{ asset('frontend/images/placeholder/full_item_feature_image.jpg') }}" class="img-responsive">
                    @else
                        <img id="image_preview" src="{{ Storage::disk('public')->url('item/'. $item->item_image) }}" class="img-responsive">
                    @endif

                    <a href="{{ route('page.item', $item->item_slug) }}" class="btn btn-primary btn-block mt-2">{{ __('backend.message.view-listing') }}</a>

                </div>
                <div class="col-9">
                    <p class="mb-2">{{ $item->category->category_name }}</p>
                    <h1 class="h4 mb-2 text-gray-800">{{ $item->item_title }}</h1>
                    <p class="mb-4">{{ $item->item_address_hide == 0 ? $item->item_address . ', ' : '' }} {{ $item->city->city_name . ', ' . $item->state->state_name . ' ' . $item->item_postal_code }}</p>
                    <hr/>
                    <p class="mb-4">{{ $item->item_description }}</p>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-8">
                    <form method="POST" action="{{ route('admin.items.reviews.store', ['item_slug' => $item->item_slug]) }}">
                        @csrf

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <span class="text-lg text-gray-800">{{ __('review.backend.select-rating') }}</span>
                                <small class="form-text text-muted">
                                </small>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <label for="rating" class="text-black">{{ __('review.backend.overall-rating') }}</label>
                                <select class="rating_stars" name="rating">
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}">{{ \App\Item::ITEM_REVIEW_RATING_ONE }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}">{{ \App\Item::ITEM_REVIEW_RATING_TWO }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}">{{ \App\Item::ITEM_REVIEW_RATING_THREE }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}">{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}">{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}</option>
                                </select>
                                @error('rating')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-row mb-3">
                            <div class="col-md-3">
                                <label for="customer_service_rating" class="text-black">{{ __('review.backend.customer-service') }}</label>
                                <select class="rating_stars" name="customer_service_rating">
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}">{{ \App\Item::ITEM_REVIEW_RATING_ONE }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}">{{ \App\Item::ITEM_REVIEW_RATING_TWO }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}">{{ \App\Item::ITEM_REVIEW_RATING_THREE }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}">{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}">{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}</option>
                                </select>
                                @error('customer_service_rating')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="quality_rating" class="text-black">{{ __('review.backend.quality') }}</label>
                                <select class="rating_stars" name="quality_rating">
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}">{{ \App\Item::ITEM_REVIEW_RATING_ONE }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}">{{ \App\Item::ITEM_REVIEW_RATING_TWO }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}">{{ \App\Item::ITEM_REVIEW_RATING_THREE }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}">{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}">{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}</option>
                                </select>
                                @error('quality_rating')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="friendly_rating" class="text-black">{{ __('review.backend.friendly') }}</label>
                                <select class="rating_stars" name="friendly_rating">
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}">{{ \App\Item::ITEM_REVIEW_RATING_ONE }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}">{{ \App\Item::ITEM_REVIEW_RATING_TWO }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}">{{ \App\Item::ITEM_REVIEW_RATING_THREE }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}">{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}">{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}</option>
                                </select>
                                @error('friendly_rating')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="pricing_rating" class="text-black">{{ __('review.backend.pricing') }}</label>
                                <select class="rating_stars" name="pricing_rating">
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}">{{ \App\Item::ITEM_REVIEW_RATING_ONE }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}">{{ \App\Item::ITEM_REVIEW_RATING_TWO }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}">{{ \App\Item::ITEM_REVIEW_RATING_THREE }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}">{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}">{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}</option>
                                </select>
                                @error('pricing_rating')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <span class="text-lg text-gray-800">{{ __('review.backend.tell-experience') }}</span>
                                <small class="form-text text-muted">
                                </small>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <label for="title" class="text-black">{{ __('review.backend.title') }}</label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}">
                                @error('title')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <label for="body" class="text-black">{{ __('review.backend.description') }}</label>
                                <textarea class="form-control @error('body') is-invalid @enderror" id="body" rows="5" name="body">{{ old('body') }}</textarea>
                                @error('body')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">

                            <div class="col-md-12">
                                <div class="form-check form-check-inline">
                                    <input {{ old('recommend') == 1 ? 'checked' : '' }} class="form-check-input" type="checkbox" id="recommend" name="recommend" value="1">
                                    <label class="form-check-label" for="recommend">
                                        {{ __('review.backend.recommend') }}
                                    </label>
                                </div>
                                @error('recommend')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('review.backend.post-review') }}
                                </button>
                            </div>
                        </div>

                </form>
                </div>
                <div class="col-4"></div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')

    <script src="{{ asset('backend/vendor/jquery-bar-rating/dist/jquery.barrating.min.js') }}"></script>
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function() {
            $('.rating_stars').barrating({
                theme: 'bootstrap-stars',
                allowEmpty: null
            });
        });
    </script>

@endsection
