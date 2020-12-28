<?php
$it=DB::table('items')->find($adv);
$url = route('admin.invitations');
?>
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
<form action="{{route('admin.invitations')}}" method="POST" enctype="multipart/form-data">
    @csrf
    {{-- ##########################(from bg)################################### --}}
    <div class="form-group">
        <label for="usr">Title:</label>
        <input type="text" class="form-control" id="usr" name="Title">
      </div>
      <div class="form-group">
        <label for="comment">Text :</label>
        <textarea class="form-control" rows="5" id="comment" name="Text"></textarea>
      </div>
      <div class="form-group">
        <label for="sel1">user:</label>
        <select class="form-control" id="sel1" name="users">
            @foreach(DB::table('users')->orderBy('id','desc')->get() as $item_research_project)
            <option value="{{ $item_research_project->id}}">{{ $item_research_project->name}} </option>
            @endforeach
        </select>
      </div>
      <input type="hidden" name="Link" value="{{ $it->item_slug}}">
    {{-- ##########################(end bg)################################### --}}
    <input type="submit" style="background: #011a25;" class="btn btn-primary btn-large btn-block"
      value="اضافة موضوع جديد" />
    <br>
    <a  href="{{ route('admin.msg') }}" class="btn btn-primary active">عرض الدعوات</a>
@if(session()->has('alert-success'))
<input type="submit" style="background: #011a25;background: #20a049;padding: 1%;font-size: 16px;"
  class="btn btn-success save btn-large btn-block" value="  {{ session()->get('alert-success') }}" />
@endif
  </form>
  <br>
  
  @endsection
  </form>
</div>
</div>
@section('content')
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
                zoom: 7,
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
             * Start image gallery uplaod
             */
            $('#upload_gallery').on('click', function(){
                window.selectedImages = [];

                $.FileDialog({
                    accept: "image/jpeg",
                }).on("files.bs.filedialog", function (event) {
                    var html = "";
                    for (var a = 0; a < event.files.length; a++) {

                        if(a === 12) {break;}
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
    <script>
        $(document).ready(function(){
              $("[value=7]").hide();

              });
</script>
@endsection
