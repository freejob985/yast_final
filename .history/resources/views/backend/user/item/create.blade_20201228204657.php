@extends('backend.user.layouts.app')

@section('styles')
<link href="{{ asset('frontend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />

<!-- Image Crop Css -->
<link href="{{ asset('backend/vendor/croppie/croppie.css') }}" rel="stylesheet" />

<!-- Bootstrap FD Css-->
<link href="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.css') }}" rel="stylesheet" />
<style>
    
    a.btn.btn-success.add {
    font-size: 26px;
}
</style>
@endsection

@section('content')

<div class="row justify-content-between">
    <div class="col-9">
        @if (isset($_GET['category']))
        @if ($_GET['category'] =="7")
        <h1 class="h3 mb-2 text-gray-800">طلب مقايسه</h1>
        <p class="mb-4">اذا كنت من اصحاب الشركات او تريد عمل مقايسه واختيار افضل العمال فقم بوضع تفصيل كامل لمشروعك لكي
            يطلع عليه المناسبين لعملك</p>
        @else
        <h1 class="h3 mb-2 text-gray-800">{{ __('backend.item.add-item') }}</h1>
        <p class="mb-4">{{ __('backend.item.add-item-desc') }}</p>
        @endif
        @endif
    </div>
    <div class="col-3 text-right">
        <a href="{{ route('user.items.index') }}" class="btn btn-info btn-icon-split">
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

        @if(Request::fullUrl() === 'https://yasta.net/user/items/create?category=7')
        @else
        <div class="row mb-4">
            <div class="col-12">
                <div class="row mb-2">
                    <div class="col-12">
                        <form class="form-inline" action="{{ route('user.items.create') }}" method="GET">
                            <div class="form-group mr-2">
                                @if(Auth::user()->Type==="1" or Auth::user()->isAdmin() or Auth::user()->Type==="2" )
                                <select class="custom-select @error('category') is-invalid @enderror" name="category"
                                    onchange="$('#item-create-form').remove();">
                                    <option>{{ __('backend.item.select-category') }}</option>
                                    @foreach($all_categories as $key => $category)
                                    <option value="{{ $category->id }}"
                                        {{ $category->id == $category_id ? 'selected' : '' }}>
                                        {{ $category->category_name }}</option>
                                    @endforeach
                                </select>

                                @error('category')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <button type="submit"
                                class="btn btn-primary mr-2">{{ __('backend.item.load-form') }}</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-12">
                @if($category_id > 0)
                <form method="POST" action="{{ route('user.items.store') }}" id="item-create-form"
                    enctype="multipart/form-data">
                    @csrf
                    <hr />
                    <div class="form-row mb-3">
                        <div class="col-md-12">
                            <span class="text-lg text-gray-800">{{ __('backend.item.general-info') }}</span>
                            <small class="form-text text-muted">
                            </small>
                        </div>
                    </div>
                    <div class="form-row mb-3">
                        <div class="col-md-8">
                            <label for="item_title" class="text-black">{{ __('backend.item.title') }}</label>
                            <input id="item_title" type="text"
                                class="form-control @error('item_title') is-invalid @enderror" name="item_title"
                                value="{{ old('item_title') }}">
                            @error('item_title')
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <input name="category" value="{{ $category_id }}" type="hidden" id="input_category_id">
                        </div>

                        <div class="col-md-4">
                            @if(Auth::user()->hasPaidSubscription())

                            <label for="item_featured" class="text-black">{{ __('backend.item.featured') }}</label>
                            <select class="custom-select @error('item_featured') is-invalid @enderror"
                                name="item_featured">

                                <option value="0" selected>{{ __('backend.shared.no') }}</option>
                                <option value="{{ \App\Item::ITEM_FEATURED }}">{{ __('backend.shared.yes') }}</option>

                            </select>
                            @error('item_featured')
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                            @else
                            <input type="hidden" name="item_featured" value="{{ \App\Item::ITEM_NOT_FEATURED }}">
                            @endif
                        </div>
                    </div>

                    <div class="form-row mb-3">

                        <div class="col-md-4">
                            <label for="item_address" class="text-black">{{ __('backend.item.address') }}</label>
                            <input id="item_address" type="text"
                                class="form-control @error('item_address') is-invalid @enderror" name="item_address"
                                value="{{ old('item_address') }}">
                            @error('item_address')
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-md-4" style="
                        display: none;
                    ">
                            <label for="state_id" class="text-black">{{ __('backend.state.state') }}</label>
                            <select id="select_state_id" class="custom-select @error('state_id') is-invalid @enderror"
                                name="state_id">
                                <option selected>{{ __('backend.item.select-state') }}</option>
                                @foreach($all_states as $key => $state)
                                <option value="{{ $state->id }}" {{ $state->id == old('state_id') ? 'selected' : '' }}>
                                    {{ $state->state_name }}</option>
                                @endforeach
                            </select>
                            @error('state_id')
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-md-4" style="
                        display: none;
                    ">
                            <label for="city_id" class="text-black">{{ __('backend.city.city') }}</label>
                            <select id="select_city_id" class="custom-select @error('city_id') is-invalid @enderror"
                                name="city_id">
                                <option selected>{{ __('backend.item.select-city') }}</option>
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
                                <input {{ old('item_address_hide') == 1 ? 'checked' : '' }} class="form-check-input"
                                    type="checkbox" id="item_address_hide" name="item_address_hide" value="1">
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
                        <div class="col-md-3" style="display: none;">
                            <label for="item_postal_code"
                                class="text-black">{{ __('backend.item.postal-code') }}</label>
                            <input id="item_postal_code" type="text"
                                class="form-control @error('item_postal_code') is-invalid @enderror"
                                name="item_postal_code" value="{{ old('item_postal_code') }}">
                            @error('item_postal_code')
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-md-3" style="display: none;">
                            <label for="item_lat" class="text-black">{{ __('backend.item.lat') }}</label>
                            <input id="item_lat" type="text"
                                class="form-control @error('item_lat') is-invalid @enderror" name="item_lat"
                                value="{{ old('item_lat') }}" aria-describedby="latHelpBlock">
                            <small id="latHelpBlock" class="form-text text-muted">
                                <a
                                    class="lat_lng_select_button btn btn-sm btn-primary text-white">{{ __('backend.item.select-map') }}</a>
                            </small>
                            @error('item_lat')
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-md-3" style="display: none;">
                            <label for="item_lng" class="text-black">{{ __('backend.item.lng') }}</label>
                            <input id="item_lng" type="text"
                                class="form-control @error('item_lng') is-invalid @enderror" name="item_lng"
                                value="{{ old('item_lng') }}" aria-describedby="lngHelpBlock">
                            <small id="lngHelpBlock" class="form-text text-muted">
                                <a
                                    class="lat_lng_select_button btn btn-sm btn-primary text-white">{{ __('backend.item.select-map') }}</a>
                            </small>
                            @error('item_lng')
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>




                        <div class="col-md-3">

                            <label for="item_phone" class="text-black">{{ __('backend.item.phone') }}</label>
                            <input id="item_phone" type="text"
                                class="form-control @error('item_phone') is-invalid @enderror" name="item_phone"
                                value="{{ old('item_phone') }}">

                            @if(Request::fullUrl() === 'https://yasta.net/user/items/create?category=7' or
                            !Auth::user()->Type==="1")

                            <span style="font-size: 12px;text-decoration: underline;float: right;font-weight: 500;">
                                (برجاء وضع رقم واتساب متاح لك مع وضع كود البلد مثال 201001111111)</span>
                            <div class="form-check form-check-inline">
                                <input {{ old('item_phone_hide') == 1 ? 'checked' : '' }} class="form-check-input"
                                    type="checkbox" id="item_phone_hide" name="item_phone_hide" value="1">
                                <label class="form-check-label" for="item_phone_hide">
                                    {{ __('backend.item.hide-address1') }}
                                    <small class="text-muted">
                                        {{ __('backend.item.hide-address-help1') }}
                                    </small>
                                </label>
                            </div>

                            @endif
                        </div>

                    </div>

                    <div class="form-row mb-3">

                        <div class="col-md-12">
                            <label for="item_description"
                                class="text-black">{{ __('backend.item.description') }}</label>
                            <textarea class="form-control @error('item_description') is-invalid @enderror"
                                id="item_description" rows="5"
                                name="item_description">{{ old('item_description') }}</textarea>
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
                            <input id="item_website" type="text"
                                class="form-control @error('item_website') is-invalid @enderror" name="item_website"
                                value="{{ old('item_website') }}">
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
                            <label for="item_social_facebook"
                                class="text-black">{{ __('backend.item.facebook') }}</label>
                            <input id="item_social_facebook" type="text"
                                class="form-control @error('item_social_facebook') is-invalid @enderror"
                                name="item_social_facebook" value="{{ old('item_social_facebook') }}">
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
                            <input id="item_social_twitter" type="text"
                                class="form-control @error('item_social_twitter') is-invalid @enderror"
                                name="item_social_twitter" value="{{ old('item_social_twitter') }}">
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
                            <label for="item_social_linkedin"
                                class="text-black">{{ __('backend.item.linkedin') }}</label>
                            <input id="item_social_linkedin" type="text"
                                class="form-control @error('item_social_linkedin') is-invalid @enderror"
                                name="item_social_linkedin" value="{{ old('item_social_linkedin') }}">
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
                    <div class="form-row mb-3" >
                        <div class="col-md-12">
                            <span class="text-lg text-gray-800">{{ __('backend.item.custom-fields') }}</span>
                            <small class="form-text text-muted">
                                {{ __('backend.item.custom-field-help') }}
                            </small>
                        </div>
                    </div>
                    <div class="form-row" style="
    display: none;
">
                        @foreach($all_customFields as $key => $customField)
                        <div class="col-md-4 mb-3">
                            @if($customField->custom_field_type == \App\CustomField::TYPE_TEXT)
                            <label for="{{ str_slug($customField->custom_field_name . $customField->id) }}"
                                class="text-black">{{ $customField->custom_field_name }}</label>
                            <textarea
                                class="form-control @error(str_slug($customField->custom_field_name . $customField->id)) is-invalid @enderror"
                                id="{{ str_slug($customField->custom_field_name . $customField->id) }}" rows="5"
                                name="{{ str_slug($customField->custom_field_name . $customField->id) }}">{{ old(str_slug($customField->custom_field_name . $customField->id)) }}</textarea>
                            @error(str_slug($customField->custom_field_name . $customField->id))
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            @endif
                            @if($customField->custom_field_type == \App\CustomField::TYPE_SELECT)
                            <label for="{{ str_slug($customField->custom_field_name . $customField->id) }}"
                                class="text-black">{{ $customField->custom_field_name }}</label>
                            <select class="custom-select"
                                name="{{ str_slug($customField->custom_field_name . $customField->id) }}"
                                id="{{ str_slug($customField->custom_field_name . $customField->id) }}">
                                @foreach(explode(',', $customField->custom_field_seed_value) as $key =>
                                $custom_field_value)
                                <option value="{{ $custom_field_value }}"
                                    {{ old(str_slug($customField->custom_field_name . $customField->id)) == $custom_field_value ? 'selected' : '' }}>
                                    {{ $custom_field_value }}</option>
                                @endforeach
                            </select>
                            @error(str_slug($customField->custom_field_name . $customField->id))
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            @endif
                            @if($customField->custom_field_type == \App\CustomField::TYPE_MULTI_SELECT)
                            <label for="{{ str_slug($customField->custom_field_name . $customField->id) }}"
                                class="text-black">{{ $customField->custom_field_name }}</label>
                            <select multiple class="custom-select"
                                name="{{ str_slug($customField->custom_field_name . $customField->id) }}[]"
                                id="{{ str_slug($customField->custom_field_name . $customField->id) }}">
                                @foreach(explode(',', $customField->custom_field_seed_value) as $key =>
                                $custom_field_value)
                                <option value="{{ $custom_field_value }}"
                                    {{ old(str_slug($customField->custom_field_name . $customField->id)) == $custom_field_value ? 'selected' : '' }}>
                                    {{ $custom_field_value }}</option>
                                @endforeach
                            </select>
                            @error($customField->custom_field_name . $customField->id)
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            @endif
                            @if($customField->custom_field_type == \App\CustomField::TYPE_LINK)
                            <label for="{{ str_slug($customField->custom_field_name . $customField->id) }}"
                                class="text-black">{{ $customField->custom_field_name }}</label>
                            <input id="{{ str_slug($customField->custom_field_name . $customField->id) }}" type="text"
                                class="form-control @error(str_slug($customField->custom_field_name . $customField->id)) is-invalid @enderror"
                                name="{{ str_slug($customField->custom_field_name . $customField->id) }}"
                                value="{{ old(str_slug($customField->custom_field_name . $customField->id)) }}"
                                aria-describedby="linkHelpBlock">
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
                    <div class="row">


                        @include('backend.user.item.Area')

                    </div>
                    <hr>

                    <a class="btn btn-success add" style="color: white;"><i class="fas fa-plus-square"></i> اضافة مدن ومحافظات عمل</a>
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
                                    <button id="upload_image" type="button"
                                        class="btn btn-primary btn-block mb-2">{{ __('backend.item.select-image') }}</button>
                                    <img id="image_preview"
                                        src="{{ asset('frontend/images/placeholder/full_item_feature_image.jpg') }}"
                                        class="img-responsive">
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
                                    <button id="upload_gallery" type="button"
                                        class="btn btn-primary btn-block mb-2">{{ __('backend.item.select-images') }}</button>
                                    <div class="row" id="selected-images">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (isset($_GET['category']))
                    @if ($_GET['category'] =="7")
                    <label for="myfile">Select a file:</label>
                    <input type="file" id="myfiles" name="file">
                    @endif
                    @endif
                    <hr />
                    <div class="form-row mb-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                {{ __('backend.shared.create') }}
                            </button>
                        </div>
                    </div>

                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal - feature image -->
<div class="modal fade" id="image-crop-modal" tabindex="-1" role="dialog" aria-labelledby="image-crop-modal"
    aria-hidden="true">
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
                            <label class="custom-file-label"
                                for="upload_image_input">{{ __('backend.item.choose-image') }}</label>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                <button id="crop_image" type="button"
                    class="btn btn-primary">{{ __('backend.item.crop-image') }}</button>
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
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                <button id="lat_lng_confirm" type="button"
                    class="btn btn-primary">{{ __('backend.shared.confirm') }}</button>
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
    // Call the dataTables jQuery plugin
        $(document).ready(function() {

            /**
             * Start map modal
             */
            var map = L.map('map-modal-body', {
              center: [26.27371402440643, 389.75097656250006],
                zoom: 5,
            });

            var layerGroup = L.layerGroup().addTo(map);
            var current_lat = 0;
            var current_lng = 0;

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
             * Start state, city selector
             */

             $(document).on('change','.select_state_id_',function (e) {
                  var selectedCountry = $(this).children("option:selected").text();
               var id= ".select_city_id_"+$(this).attr('id');
               var conceptName = $('.select_city_id_').find(":selected").text();

           
            

                $(id).html('<option selected>Loading, please wait...</option>');

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
                           var all=     "جميع مدن "+selectedCountry;
                            $(id).html('<option value=0 selected>أختر مدينة</option>');
                            $.each(JSON.parse(result), function(key, value) {
                                var city_id = value.id;
                                var city_name = value.city_name;
                                $(id).append('<option value="'+ city_id +'">' + city_name + '</option>');
                            });
                    }});
                }

            });
            /**
             * End state, city selector
             */


                  /**
             * Start state, city selector
             */

             $(document).on('change','.select_state_id_',function (e) {
                  var selectedCountry = $(this).children("option:selected").text();
               var id= ".select_city_id_"+$(this).attr('id');
               var conceptName = $('.select_city_id_').find(":selected").text();

           
            

                $(id).html('<option selected>Loading, please wait...</option>');

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
                            $(id).html('<option value=0 selected>أختر مدينة</option>');
                            $.each(JSON.parse(result), function(key, value) {
                                var city_id = value.id;
                                var city_name = value.city_name;
                                $(id).append('<option value="'+ city_id +'">' + city_name + '</option>');
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

                $('#image-crop-modal').modal('hide');
            });
            /**
             * End the croppie image plugin
             */

        });
</script>

<script>
    $(document).ready(function(){
              $("[value=7]").hide();
            //    $("[value=2]").hide();
              $("[value=4]").hide();
              $("[value=23]").hide();
              $("[value=25]").hide();
          //   $("[value=1]").hide();

              });
</script>


<script>
    var c=0;
    $(document).on('click','.add',function (e) {
        c++;
       // e.preventDefault();
       
         $("#add").append("<div class=\"col-md-3\">\n" +
            "    <label for=\"state_id\" for=\"state_id\" class=\"text-black\">{{ __('backend.state.state') }}</label>\n" +
            "    <select id="+c+" class=\" select_state_id_ custom-select @error('state_id') is-invalid @enderror\" name=\"a1[]\">\n" +
            "        <option selected>{{ __('backend.item.select-state') }}</option>\n" +
            "        @foreach($all_states as $key => $state)\n" +
            "        <option value=\"{{ $state->id }}\" {{ $state->id == old('state_id') ? 'selected' : '' }}>\n" +
            "            {{ $state->state_name }}</option>\n" +
            "        @endforeach\n" +
            "    </select>\n" +
            "    @error('state_id')\n" +
            "    <span class=\"invalid-tooltip\">\n" +
            "        <strong>{{ $message }}</strong>\n" +
            "    </span>\n" +
            "    @enderror\n" +
            "</div>\n" +
            "<div class=\"col-md-3\">\n" +
            "    <label for=\"city_id\" class=\"text-black\">{{ __('backend.city.city') }}</label>\n" +
            "    <select id=\"select_city_id_\" class=\" select_city_id_"+c+" custom-select @error('city_id') is-invalid @enderror\" name=\"a2[]\">\n" +
            "        <option selected>{{ __('backend.item.select-city') }}</option>\n" +
            "    </select>\n" +
            "    @error('city_id')\n" +
            "    <span class=\"invalid-tooltip\">\n" +
            "        <strong>{{ $message }}</strong>\n" +
            "    </span>\n" +
            "    @enderror\n" +
            "</div>");
            return false;
     });

</script>


@endsection