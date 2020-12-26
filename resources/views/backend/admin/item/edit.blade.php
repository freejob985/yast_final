@extends('backend.admin.layouts.app')

@section('styles')
    <link href="{{ asset('frontend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />

    <!-- Image Crop Css -->
    <link href="{{ asset('backend/vendor/croppie/croppie.css') }}" rel="stylesheet" />

    <!-- Bootstrap FD Css-->
    <link href="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.css') }}" rel="stylesheet" />

@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            
           @if (isset($_GET['category']))
        @if ($_GET['category'] =="7")
            <h1 class="h3 mb-2 text-gray-800">طلب مقايسه</h1>
            <p class="mb-4">اذا كنت من اصحاب الشركات او تريد عمل مقايسه واختيار افضل العمال فقم بوضع تفصيل كامل لمشروعك لكي يطلع عليه المناسبين لعملك</p>
            @else
           <h1 class="h3 mb-2 text-gray-800">{{ __('backend.item.add-item') }}</h1>
            <p class="mb-4">{{ __('backend.item.add-item-desc') }}</p>
            @endif
             @endif
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.items.index') }}" class="btn btn-info btn-icon-split">
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

            <div class="row mb-4 justify-content-between">
                <div class="col-4">
                    <span class="text-lg text-gray-800">{{ __('backend.category.category') }}: </span>
                    <span class="text-lg">{{ $item->category->category_name }}</span>
                </div>
                <div class="col-4 text-right">
                    @if($item->item_status == \App\Item::ITEM_PUBLISHED)
                        <form action="{{ route('admin.items.disapprove', $item) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-warning mb-1">{{ __('backend.shared.disapprove') }}</button>
                        </form>

                        <form action="{{ route('admin.items.suspend', $item) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-danger mb-2">{{ __('backend.shared.suspend') }}</button>
                        </form>
                    @elseif($item->item_status == \App\Item::ITEM_SUBMITTED)
                        <form action="{{ route('admin.items.approve', $item) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-success mb-1">{{ __('backend.shared.approve') }}</button>
                        </form>

                        <form action="{{ route('admin.items.suspend', $item) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-danger mb-2">{{ __('backend.shared.suspend') }}</button>
                        </form>
                    @elseif($item->item_status == \App\Item::ITEM_SUSPENDED)
                        <form action="{{ route('admin.items.approve', $item) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-success mb-1">{{ __('backend.shared.approve') }}</button>
                        </form>

                        <form action="{{ route('admin.items.disapprove', $item) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-warning mb-2">{{ __('backend.shared.disapprove') }}</button>
                        </form>
                    @endif

                    <a class="text-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                        {{ __('backend.item.delete-item') }}
                    </a>
                </div>
            </div>

            @if($item->item_status == \App\Item::ITEM_PUBLISHED)
            <div class="row mb-4">
                <div class="col-12">
                    <span class="text-gray-800">{{ __('backend.item.item-link') }}:</span>
                    <a href="{{ route('page.item', $item->item_slug) }}" target="_blank">{{ route('page.item', $item->item_slug) }}</a>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.items.update', $item) }}" id="item-create-form">
                        @csrf
                        @method('PUT')
                        <hr/>
                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <span class="text-lg text-gray-800">{{ __('backend.item.general-info') }}</span>
                                <small class="form-text text-muted">
                                </small>
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <div class="col-md-4">
                                <label for="item_title" class="text-black">{{ __('backend.item.title') }}</label>
                                <input id="item_title" type="text" class="form-control @error('item_title') is-invalid @enderror" name="item_title" value="{{ old('item_title') ? old('item_title') : $item->item_title }}">
                                @error('item_title')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="item_status" class="text-black">{{ __('backend.item.status') }}</label>
                                <select class="custom-select @error('item_status') is-invalid @enderror" name="item_status">

                                    <option {{ $item->item_status == \App\Item::ITEM_SUBMITTED ? 'selected' : '' }} value="{{ \App\Item::ITEM_SUBMITTED }}">{{ __('backend.item.submitted') }}</option>
                                    <option {{ $item->item_status == \App\Item::ITEM_PUBLISHED ? 'selected' : '' }} value="{{ \App\Item::ITEM_PUBLISHED }}">{{ __('backend.item.published') }}</option>
                                    <option {{ $item->item_status == \App\Item::ITEM_SUSPENDED ? 'selected' : '' }} value="{{ \App\Item::ITEM_SUSPENDED }}">{{ __('backend.item.suspended') }}</option>

                                </select>
                                @error('item_status')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="item_featured" class="text-black">{{ __('backend.item.featured') }}</label>
                                <select class="custom-select @error('item_featured') is-invalid @enderror" name="item_featured">

                                    <option {{ $item->item_featured == \App\Item::ITEM_FEATURED ? 'selected' : '' }} value="0" selected>{{ __('backend.shared.no') }}</option>
                                    <option {{ $item->item_featured == \App\Item::ITEM_FEATURED ? 'selected' : '' }} value="{{ \App\Item::ITEM_FEATURED }}">{{ __('backend.shared.yes') }}</option>

                                </select>
                                @error('item_featured')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">

                            <div class="col-md-4">
                                <label for="item_address" class="text-black">{{ __('backend.item.address') }}</label>
                                <input id="item_address" type="text" class="form-control @error('item_address') is-invalid @enderror" name="item_address" value="{{ old('item_address') ? old('item_address') : $item->item_address }}">
                                @error('item_address')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="state_id" class="text-black">{{ __('backend.state.state') }}</label>
                                <select id="select_state_id" class="custom-select @error('state_id') is-invalid @enderror" name="state_id">
                                    <option selected>{{ __('backend.item.select-state') }}</option>
                                    @foreach($all_states as $key => $state)
                                        <option {{ $item->state_id == $state->id ? 'selected' : '' }} value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                @error('state_id')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="city_id" class="text-black">{{ __('backend.city.city') }}</label>
                                <select id="select_city_id" class="custom-select @error('city_id') is-invalid @enderror" name="city_id">
                                    <option selected>{{ __('backend.item.select-city') }}</option>
                                    @foreach($all_cities as $key => $city)
                                        <option {{ $item->city_id == $city->id ? 'selected' : '' }} value="{{ $city->id }}">{{ $city->city_name }}</option>
                                    @endforeach
                                </select>
                                @error('city_id')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <div class="form-row mb-3">

                            <div class="col-md-12">
                                <div class="form-check form-check-inline">
                                    <input {{ $item->item_address_hide == 1 ? 'checked' : '' }} class="form-check-input" type="checkbox" id="item_address_hide" name="item_address_hide" value="1">
                                    <label class="form-check-label" for="item_address_hide">
                                        {{ __('backend.item.hide-address') }}
                                        <small class="text-muted">
                                            {{ __('backend.item.hide-address-help') }}
                                        </small>
                                    </label>
                                </div>
                                @error('item_address_hide')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">

                            <div class="col-md-3" style="
    display: none;
">
                                <label for="item_postal_code" class="text-black">{{ __('backend.item.postal-code') }}</label>
                                <input id="item_postal_code" type="text" class="form-control @error('item_postal_code') is-invalid @enderror" name="item_postal_code" value="{{ old('item_postal_code') ? old('item_postal_code') : $item->item_postal_code }}">
                                @error('item_postal_code')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-3" style="
    display: none;
">
                                <label for="item_lat" class="text-black">{{ __('backend.item.lat') }}</label>
                                <input id="item_lat" type="text" class="form-control @error('item_lat') is-invalid @enderror" name="item_lat" value="{{ old('item_lat') ? old('item_lat') : $item->item_lat }}" aria-describedby="latHelpBlock">
                                <small id="lngHelpBlock" class="form-text text-muted">
                                    <a class="lat_lng_select_button btn btn-sm btn-primary text-white">{{ __('backend.item.select-map') }}</a>
                                </small>
                                @error('item_lat')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-3" style="
    display: none;
">
                                <label for="item_lng" class="text-black">{{ __('backend.item.lng') }}</label>
                                <input id="item_lng" type="text" class="form-control @error('item_lng') is-invalid @enderror" name="item_lng" value="{{ old('item_lng') ? old('item_lng') : $item->item_lng }}" aria-describedby="lngHelpBlock">
                                <small id="lngHelpBlock" class="form-text text-muted">
                                    <a class="lat_lng_select_button btn btn-sm btn-primary text-white">{{ __('backend.item.select-map') }}</a>
                                </small>
                                @error('item_lng')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="item_phone" class="text-black">{{ __('backend.item.phone') }}</label>
                                <input id="item_phone" type="text" class="form-control @error('item_phone') is-invalid @enderror" name="item_phone" value="{{ old('item_phone') ? old('item_phone') : $item->item_phone }}" aria-describedby="lngHelpBlock">
                                @error('item_phone')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <div class="form-row mb-3">

                            <div class="col-md-12">
                                <label for="item_description" class="text-black">{{ __('backend.item.description') }}</label>
                                <textarea class="form-control @error('item_description') is-invalid @enderror" id="item_description" rows="5" name="item_description">{{ old('item_description') ? old('item_description') : $item->item_description }}</textarea>
                                @error('item_description')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Start web & social media -->
                        <div class="form-row mb-3">
                            <div class="col-md-3">
                                <label for="item_website" class="text-black">{{ __('backend.item.website') }}</label>
                                <input id="item_website" type="text" class="form-control @error('item_website') is-invalid @enderror" name="item_website" value="{{ old('item_website') ? old('item_website') : $item->item_website }}">
                                <small id="linkHelpBlock" class="form-text text-muted">
                                    {{ __('backend.shared.url-help') }}
                                </small>
                                @error('item_website')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="item_social_facebook" class="text-black">{{ __('backend.item.facebook') }}</label>
                                <input id="item_social_facebook" type="text" class="form-control @error('item_social_facebook') is-invalid @enderror" name="item_social_facebook" value="{{ old('item_social_facebook') ? old('item_social_facebook') : $item->item_social_facebook }}">
                                <small id="linkHelpBlock" class="form-text text-muted">
                                    {{ __('backend.shared.url-help') }}
                                </small>
                                @error('item_social_facebook')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="item_social_twitter" class="text-black">{{ __('backend.item.twitter') }}</label>
                                <input id="item_social_twitter" type="text" class="form-control @error('item_social_twitter') is-invalid @enderror" name="item_social_twitter" value="{{ old('item_social_twitter') ? old('item_social_twitter') : $item->item_social_twitter }}">
                                <small id="linkHelpBlock" class="form-text text-muted">
                                    {{ __('backend.shared.url-help') }}
                                </small>
                                @error('item_social_twitter')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="item_social_linkedin" class="text-black">{{ __('backend.item.linkedin') }}</label>
                                <input id="item_social_linkedin" type="text" class="form-control @error('item_social_linkedin') is-invalid @enderror" name="item_social_linkedin" value="{{ old('item_social_linkedin') ? old('item_social_linkedin') : $item->item_social_linkedin }}">
                                <small id="linkHelpBlock" class="form-text text-muted">
                                    {{ __('backend.shared.url-help') }}
                                </small>
                                @error('item_social_linkedin')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>
                        <!-- End web & social media -->

                        <!-- Start custom field section -->
                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <span class="text-lg text-gray-800">{{ __('backend.item.custom-fields') }}</span>
                                <small class="form-text text-muted">
                                    {{ __('backend.item.custom-field-help') }}
                                </small>
                            </div>
                        </div>
                        <div class="form-row">
                            @foreach($all_customFields as $key => $customField)
                                <div class="col-md-4 mb-3">
                                    @if($customField->custom_field_type == \App\CustomField::TYPE_TEXT)
                                        <label for="{{ str_slug($customField->custom_field_name . $customField->id) }}" class="text-black">{{ $customField->custom_field_name }}</label>
                                        <textarea class="form-control @error(str_slug($customField->custom_field_name . $customField->id)) is-invalid @enderror" id="{{ str_slug($customField->custom_field_name . $customField->id) }}" rows="5" name="{{ str_slug($customField->custom_field_name . $customField->id) }}">{{ old(str_slug($customField->custom_field_name . $customField->id)) ? old(str_slug($customField->custom_field_name . $customField->id)) : ($item->features()->where('custom_field_id', $customField->id)->get()->count() > 0 ? $item->features()->where('custom_field_id', $customField->id)->get()->first()->item_feature_value : '') }}</textarea>
                                        @error(str_slug($customField->custom_field_name . $customField->id))
                                        <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    @endif
                                  
                                    @if($customField->custom_field_type == \App\CustomField::TYPE_SELECT)
                                        <label for="{{ str_slug($customField->custom_field_name . $customField->id) }}" class="text-black">{{ $customField->custom_field_name }}</label>
                                        <select class="custom-select" name="{{ str_slug($customField->custom_field_name . $customField->id) }}" id="{{ str_slug($customField->custom_field_name . $customField->id) }}">
                                            @foreach(explode(',', $customField->custom_field_seed_value) as $key => $custom_field_value)
                                                <option {{ ($item->features()->where('custom_field_id', $customField->id)->get()->count() > 0 ? $item->features()->where('custom_field_id', $customField->id)->get()->first()->item_feature_value : '') == trim($custom_field_value) ? 'selected' : '' }} value="{{ $custom_field_value }}">{{ $custom_field_value }}</option>
                                            @endforeach
                                        </select>
                                        @error(str_slug($customField->custom_field_name . $customField->id))
                                        <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    @endif
                               
                                    @if($customField->custom_field_type == \App\CustomField::TYPE_LINK)
                                        <label for="{{ str_slug($customField->custom_field_name . $customField->id) }}" class="text-black">{{ $customField->custom_field_name }}</label>
                                        <input id="{{ str_slug($customField->custom_field_name . $customField->id) }}" type="text" class="form-control @error(str_slug($customField->custom_field_name . $customField->id)) is-invalid @enderror" name="{{ str_slug($customField->custom_field_name . $customField->id) }}" value="{{ old(str_slug($customField->custom_field_name . $customField->id)) ? old(str_slug($customField->custom_field_name . $customField->id)) : ($item->features()->where('custom_field_id', $customField->id)->get()->count() > 0 ? $item->features()->where('custom_field_id', $customField->id)->get()->first()->item_feature_value : '') }}" aria-describedby="linkHelpBlock">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('backend.shared.url-help') }}
                                        </small>
                                     
                                        @error(str_slug($customField->custom_field_name . $customField->id))
                                        <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <!-- End custom field section -->
     
                        <div class="form-row mb-3">
                            <div class="col-md-6">
                                <span class="text-lg text-gray-800">{{ __('backend.item.feature-image') }}</span>
                                <small class="form-text text-muted">
                                    {{ __('backend.item.feature-image-help') }}
                                </small>
                                @error('feature_image')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="row mt-3">
                                    <div class="col-8">
                                        <button id="upload_image" type="button" class="btn btn-primary btn-block mb-2">{{ __('backend.item.select-image') }}</button>
                                        @if(empty($item->item_image))
                                            <img id="image_preview" src="{{ asset('frontend/images/placeholder/full_item_feature_image.jpg') }}" class="img-responsive">
                                        @else
                                            <img id="image_preview" src="{{ Storage::disk('public')->url('item/'. $item->item_image) }}" class="img-responsive">
                                        @endif
                                        <input id="feature_image" type="hidden" name="feature_image">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <span class="text-lg text-gray-800">{{ __('backend.item.gallery-images') }}</span>
                                <small class="form-text text-muted">
                                    {{ __('backend.item.gallery-images-help') }}
                                </small>
                                @error('image_gallery')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button id="upload_gallery" type="button" class="btn btn-primary btn-block mb-2">{{ __('backend.item.select-images') }}</button>
                                        <div class="row" id="selected-images">
                                            @foreach($item->galleries as $key => $gallery)
                                                <div class="col-3 mb-2" id="item_image_gallery_{{ $gallery->id }}">
                                                    <img class="item_image_gallery_img" src="{{ Storage::disk('public')->url('item/gallery/'. $gallery->item_image_gallery_name) }}">
                                                    <br/><button class="btn btn-danger btn-sm text-white mt-1" onclick="$(this).attr('disabled', true); deleteGallery({{ $gallery->id }});">{{ __('backend.shared.delete') }}</button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr/>
                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('backend.shared.update') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - feature image -->
    <div class="modal fade" id="image-crop-modal" tabindex="-1" role="dialog" aria-labelledby="image-crop-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.item.crop-feature-image') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div id="image_demo"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="custom-file">
                                <input id="upload_image_input" type="file" class="custom-file-input">
                                <label class="custom-file-label" for="upload_image_input">{{ __('backend.item.choose-image') }}</label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <button id="crop_image" type="button" class="btn btn-primary">{{ __('backend.item.crop-image') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete Listing -->
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
                    {{ __('backend.shared.delete-message', ['record_type' => __('backend.shared.item'), 'record_name' => $item->item_title]) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.items.destroy', $item) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - map -->
    <div class="modal fade" id="map-modal" tabindex="-1" role="dialog" aria-labelledby="map-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.item.select-map-title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div id="map-modal-body"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span id="lat_lng_span"></span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <button id="lat_lng_confirm" type="button" class="btn btn-primary">{{ __('backend.shared.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="{{ asset('frontend/vendor/leaflet/leaflet.js') }}"></script>

    <!-- Image Crop Plugin Js -->
    <script src="{{ asset('backend/vendor/croppie/croppie.js') }}"></script>

    <!-- Bootstrap Fd Plugin Js-->
    <script src="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.js') }}"></script>

    <script>

        function deleteGallery(domId)
        {
            //$("form :submit").attr("disabled", true);

            var ajax_url = '/ajax/item/gallery/delete/' + domId;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: ajax_url,
                method: 'post',
                data: {
                },
                success: function(result){
                    console.log(result);
                    $('#item_image_gallery_' + domId).remove();
                }});

        }

        $(document).ready(function() {

            /**
             * Start map modal
             */
            var map = L.map('map-modal-body', {
                center: [{{ $item->item_lat }}, {{ $item->item_lng }}],
                zoom: 10,
            });

            var layerGroup = L.layerGroup().addTo(map);
            L.marker([{{ $item->item_lat }}, {{ $item->item_lng }}]).addTo(layerGroup);

            var current_lat = {{ $item->item_lat }};
            var current_lng = {{ $item->item_lng }};

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            map.on('click', function(e) {

                // remove all the markers in one go
                layerGroup.clearLayers();
                L.marker([e.latlng.lat, e.latlng.lng]).addTo(layerGroup);

                current_lat = e.latlng.lat;
                current_lng = e.latlng.lng;

                $('#lat_lng_span').text("Lat, Lng : " + e.latlng.lat + ", " + e.latlng.lng);
            });

            $('#lat_lng_confirm').on('click', function(){

                $('#item_lat').val(current_lat);
                $('#item_lng').val(current_lng);
                $('#map-modal').modal('hide')
            });
            $('.lat_lng_select_button').on('click', function(){
                $('#map-modal').modal('show');
                setTimeout(function(){ map.invalidateSize()}, 500);
            });
            /**
             * End map modal
             */

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
                            console.log(result);
                            $('#select_city_id').html('<option selected>Select city</option>');
                            $.each(JSON.parse(result), function(key, value) {
                                var city_id = value.id;
                                var city_name = value.city_name;
                                $('#select_city_id').append('<option value="'+ city_id +'">' + city_name + '</option>');
                            });
                    }});
                }

            });
            /**
             * End state, city selector
             */

            /**
             * Start image gallery uplaod
             */
            $('#upload_gallery').on('click', function(){
                window.selectedImages = [];

                $.FileDialog({
                    accept: "image/jpeg",
                }).on("files.bs.filedialog", function (event) {
                    var html = "";
                    for (var a = 0; a < event.files.length; a++) {

                        if(a == 12) {break;}
                        selectedImages.push(event.files[a]);
                        html += "<div class='col-3 mb-2' id='item_image_gallery_" + a + "'>" +
                            "<img style='max-width: 120px;' src='" + event.files[a].content + "'>" +
                            "<br/><button class='btn btn-danger btn-sm text-white mt-1' onclick='$(\"#item_image_gallery_" + a + "\").remove();'>Delete</button>" +
                            "<input type='hidden' value='" + event.files[a].content + "' name='image_gallery[]'>" +
                            "</div>";
                    }
                    document.getElementById("selected-images").innerHTML += html;
                });
            });
            /**
             * End image gallery uplaod
             */


            /**
             * Start the croppie image plugin
             */
            $image_crop = null;

            $('#upload_image').on('click', function(){

                $('#image-crop-modal').modal('show');
            });


            $('#upload_image_input').on('change', function(){

                if(!$image_crop)
                {
                    $image_crop = $('#image_demo').croppie({
                        enableExif: true,
                        mouseWheelZoom: false,
                        viewport: {
                            width:800,
                            height:687,
                            type:'square'
                        },
                        boundary:{
                            width:850,
                            height:720
                        }
                    });

                    $('#image-crop-modal .modal-dialog').css({
                        'max-width':'100%'
                    });
                }

                var reader = new FileReader();

                reader.onload = function (event) {

                    $image_crop.croppie('bind', {
                        url: event.target.result
                    }).then(function(){
                        console.log('jQuery bind complete');
                    });

                }
                reader.readAsDataURL(this.files[0]);
            });

            $('#crop_image').on("click", function(event){

                $image_crop.croppie('result', {
                    type: 'base64',
                    size: 'viewport'
                }).then(function(response){
                    $('#feature_image').val(response);
                    $('#image_preview').attr("src", response);
                });

                $('#image-crop-modal').modal('hide')
            });
            /**
             * End the croppie image plugin
             */
        });
    </script>
@endsection
