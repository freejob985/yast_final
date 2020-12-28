@extends('backend.admin.layouts.app')

@section('styles')

    <link href="{{ asset('backend/vendor/jquery-bar-rating/dist/themes/bootstrap-stars.css') }}" rel="stylesheet" />

@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('review.backend.review-detail') }}</h1>
            <p class="mb-4">{{ __('review.backend.review-detail-desc') }}</p>
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

                    <a target="_blank" href="{{ route('page.item', $item->item_slug) }}" class="btn btn-primary btn-block mt-2">{{ __('backend.message.view-listing') }}</a>

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
                <div class="col-4">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <span class="text-lg text-gray-800">{{ __('review.backend.status') }}: </span>

                            @if($review->approved == \App\Item::ITEM_REVIEW_APPROVED)

                                <span class="text-success">{{ __('review.backend.review-approved') }}</span>
                            @else

                                <span class="text-warning">{{ __('review.backend.review-pending') }}</span>
                            @endif

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="rating" class="text-black">{{ __('review.backend.overall-rating') }}</label>
                            <select class="rating_stars" name="rating">
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}" {{ $review->rating == \App\Item::ITEM_REVIEW_RATING_ONE ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_ONE }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}" {{ $review->rating == \App\Item::ITEM_REVIEW_RATING_TWO ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_TWO }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}" {{ $review->rating == \App\Item::ITEM_REVIEW_RATING_THREE ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_THREE }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}" {{ $review->rating == \App\Item::ITEM_REVIEW_RATING_FOUR ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}" {{ $review->rating == \App\Item::ITEM_REVIEW_RATING_FIVE ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}</option>
                            </select>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="customer_service_rating" class="text-black">{{ __('review.backend.customer-service') }}</label>
                            <select class="rating_stars" name="customer_service_rating">
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}" {{ $review->customer_service_rating == \App\Item::ITEM_REVIEW_RATING_ONE ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_ONE }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}" {{ $review->customer_service_rating == \App\Item::ITEM_REVIEW_RATING_TWO ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_TWO }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}" {{ $review->customer_service_rating == \App\Item::ITEM_REVIEW_RATING_THREE ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_THREE }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}" {{ $review->customer_service_rating == \App\Item::ITEM_REVIEW_RATING_FOUR ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}" {{ $review->customer_service_rating == \App\Item::ITEM_REVIEW_RATING_FIVE ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="quality_rating" class="text-black">{{ __('review.backend.quality') }}</label>
                            <select class="rating_stars" name="quality_rating">
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}" {{ $review->quality_rating == \App\Item::ITEM_REVIEW_RATING_ONE ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_ONE }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}" {{ $review->quality_rating == \App\Item::ITEM_REVIEW_RATING_TWO ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_TWO }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}" {{ $review->quality_rating == \App\Item::ITEM_REVIEW_RATING_THREE ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_THREE }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}" {{ $review->quality_rating == \App\Item::ITEM_REVIEW_RATING_FOUR ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}" {{ $review->quality_rating == \App\Item::ITEM_REVIEW_RATING_FIVE ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}</option>
                            </select>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="friendly_rating" class="text-black">{{ __('review.backend.friendly') }}</label>
                            <select class="rating_stars" name="friendly_rating">
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}" {{ $review->friendly_rating == \App\Item::ITEM_REVIEW_RATING_ONE ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_ONE }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}" {{ $review->friendly_rating == \App\Item::ITEM_REVIEW_RATING_TWO ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_TWO }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}" {{ $review->friendly_rating == \App\Item::ITEM_REVIEW_RATING_THREE ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_THREE }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}" {{ $review->friendly_rating == \App\Item::ITEM_REVIEW_RATING_FOUR ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}" {{ $review->friendly_rating == \App\Item::ITEM_REVIEW_RATING_FIVE ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="pricing_rating" class="text-black">{{ __('review.backend.pricing') }}</label>
                            <select class="rating_stars" name="pricing_rating">
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}" {{ $review->pricing_rating == \App\Item::ITEM_REVIEW_RATING_ONE ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_ONE }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}" {{ $review->pricing_rating == \App\Item::ITEM_REVIEW_RATING_TWO ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_TWO }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}" {{ $review->pricing_rating == \App\Item::ITEM_REVIEW_RATING_THREE ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_THREE }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}" {{ $review->pricing_rating == \App\Item::ITEM_REVIEW_RATING_FOUR ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}</option>
                                <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}" {{ $review->pricing_rating == \App\Item::ITEM_REVIEW_RATING_FIVE ? 'selected' : '' }}>{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="col-8">

                    <div class="row mb-3 align-items-center">
                        <div class="col-md-1">
                            @if(empty(\App\User::find($review->author_id)->user_image))
                                <img src="{{ asset('frontend/images/placeholder/profile-'. intval(\App\User::find($review->author_id)->id % 10) . '.jpg') }}" alt="Image" class="img-fluid rounded-circle">
                            @else

                                <img src="{{ Storage::disk('public')->url('user/' . \App\User::find($review->author_id)->user_image) }}" alt="{{ \App\User::find($review->author_id)->name }}" class="img-fluid rounded-circle">
                            @endif
                        </div>
                        <div class="col-md-4">
                            <span>{{ \App\User::find($review->author_id)->name }}</span>
                        </div>
                        <div class="col-md-7 text-right">
                            <span>{{ __('review.backend.posted-at') . ' ' . \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                            @if($review->created_at != $review->updated_at)
                                <br>
                                <span>{{ __('review.backend.updated-at') . ' ' . \Carbon\Carbon::parse($review->updated_at)->diffForHumans() }}</span>
                            @endif

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="text-lg text-gray-800">{{ $review->title }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p>{!! clean(nl2br($review->body), array('HTML.Allowed' => 'b,strong,i,em,u,ul,ol,li,p,br')) !!}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            @if($review->recommend == \App\Item::ITEM_REVIEW_RECOMMEND_YES)
                                <span class="text-success">{{ __('review.backend.recommend') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    @if($review->approved == \App\Item::ITEM_REVIEW_APPROVED)
                        <form action="{{ route('admin.items.reviews.disapprove', ['review_id' => $review->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-warning">{{ __('backend.shared.disapprove') }}</button>
                        </form>
                    @else
                        <form action="{{ route('admin.items.reviews.approve', ['review_id' => $review->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">{{ __('backend.shared.approve') }}</button>
                        </form>
                    @endif
                </div>
                <div class="col-md-6 text-right">
                    <a class="text-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                        {{ __('backend.shared.delete') }}
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('review.backend.delete-a-review') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.items.reviews.delete', ['review_id' => $review->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </form>
                </div>
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
                allowEmpty: null,
                readonly: true
            });
        });
    </script>

@endsection
