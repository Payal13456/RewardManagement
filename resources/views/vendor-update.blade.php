@extends('layouts.app')
@section('title') Edit Vendor | {{ config('app.name') }} @endsection

@section('content')

<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Vendor</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{URL::route('/')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Vendor</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Basic Horizontal form layout section start -->
    <section id="basic-horizontal-layouts">
        <div class="row match-height">
            <div class="offset-md-1 col-md-10 col-12"`>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Vendor</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" action="{{URL::route('vendor-create-submit')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <input type="hidden" name="editCategoryId" id="editCategoryId">
                                        <div class="form-group row mb-4">
                                            <div class="col-md-6">
                                                <label for="name" class="label-control">Name <span class="text-danger">*</span></label>
                                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" maxlength="50" autocomplete="off" value="{{old('name')}}">
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="mobile_no" class="label-control">Mobile No <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <select name="mobile_no_code" id="mobile_no_code" class="select2 btn btn-light-secondary @error('mobile_no_code') is-invalid @enderror" required >
                                                        @if(count($countryCode) > 0)
                                                        @foreach($countryCode as $code)
                                                        <option value="{{$code->phone_code}}">{{'+'.$code->phone_code}}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                    <input type="text" id="mobile_no" class="form-control @error('mobile_no') is-invalid @enderror" name="mobile_no" placeholder="Mobile Number" maxlength="15" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9 ]/g, '').replace(/(\..*)\./g, '$1');" value="{{old('mobile_no')}}">
                                                </div>
                                                @error('mobile_no')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                            
                                        <div class="form-group row mb-4">
                                            <div class="col-md-6">
                                                <label for="email" class="label-control">Email <span class="text-danger">*</span></label>
                                                <input type="text" id="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}" name="email" placeholder="Email" maxlength="50" autocomplete="off">
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="category_id" class="label-control">Category <span class="text-danger">*</span></label>
                                                <select name="category_id" id="category_id" class="select2 form-control @error('category_id') is-invalid @enderror" >
                                                    <option value="" selected disabled >Select Category</option>
                                                    @if(count($category) > 0)
                                                    @foreach ($category as $ct)
                                                    <option value="{{$ct->id}}">{{$ct->name}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                                @error('category_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row mb-4">
                                            <div class="col-md-6">
                                                <label for="shop_name" class="label-control">Shop Name <span class="text-danger">*</span></label>
                                                <input type="text" id="shop_name" value="{{old('shop_name')}}" class="form-control @error('shop_name') is-invalid @enderror" name="shop_name" placeholder="Shop Name" maxlength="70" autocomplete="off">
                                                @error('shop_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="shop_website" class="label-control">Website <span class="text-danger">*</span></label>
                                                <input type="url" id="shop_website" value="{{old('shop_website')}}" class="form-control @error('shop_website') is-invalid @enderror" name="shop_website" placeholder="Website" maxlength="70" autocomplete="off">
                                                @error('shop_website')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row mb-4">
                                            <div class="col-md-6">
                                                <label for="shop_landline" class="label-control">Shop Landline <span class="text-danger">*</span></label>
                                                <span class="fa fa-plus btn btn-primary btn-xs multiple-field-btn add-multiple-landline float-end mb-1"></span>

                                                <div class="input-group shop_landline_div">
                                                    <select name="shop_landline_code[]" id="shop_landline_code" class="select2 btn btn-light-secondary @error('shop_landline_code') is-invalid @enderror" required >
                                                        @if(count($countryCode) > 0)
                                                        @foreach($countryCode as $code)
                                                        <option value="{{$code->phone_code}}">{{'+'.$code->phone_code}}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                    <input type="text" id="shop_landline" class="mb-2 shop_landline form-control @error('shop_landline') is-invalid @enderror" name="shop_landline[]" placeholder="Shop Landline Number" maxlength="15" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9 ]/g, '').replace(/(\..*)\./g, '$1');">
                                                </div>
                                                @error('shop_landline')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="shop_mobile" class="label-control">Shop Mobile <span class="text-danger">*</span></label>
                                                <span class="fa fa-plus btn btn-primary btn-xs multiple-field-btn add-multiple-mobile float-end mb-1"></span>
                                                
                                                <div class="input-group shop_mobile_div">
                                                    <select name="shop_mob_code[]" id="shop_mob_code" class="select2 btn btn-light-secondary @error('shop_mob_code') is-invalid @enderror" required >
                                                        @if(count($countryCode) > 0)
                                                        @foreach($countryCode as $code)
                                                        <option value="{{$code->phone_code}}">{{'+'.$code->phone_code}}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                    <input type="text" id="shop_mobile" class="mb-2 form-control @error('shop_mobile') is-invalid @enderror" name="shop_mobile[]" placeholder="Shop Mobile Number" maxlength="10" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9 ]/g, '').replace(/(\..*)\./g, '$1');">
                                                </div>
                                                @error('shop_mobile')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                            
                                        <div class="form-group row mb-4">
                                            <div class="col-md-6">
                                                <label for="shop_email" class="label-control">Shop Email <span class="text-danger">*</span></label>
                                                <span class="fa fa-plus btn btn-primary btn-xs multiple-field-btn add-multiple-email float-end mb-1"></span>

                                                <div class="input-group shop_email_div">
                                                    <input type="text" id="shop_email" class="mb-2 shop_email form-control @error('shop_email') is-invalid @enderror" name="shop_email[]" placeholder="Shop Email Address" maxlength="70" autocomplete="off">
                                                </div>
                                                @error('shop_email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="cover_img" class="label-control">Cover Image <span class="text-danger">*</span></label>
                                                <span class="fa fa-plus btn btn-primary btn-xs multiple-field-btn add-multiple-coverImg float-end mb-1"></span>
                                                
                                                <div class="input-group shop_cover_img_div">
                                                    <input type="file" id="cover_img" class="mb-2 cover_img form-control @error('cover_img') is-invalid @enderror" name="cover_img[]" placeholder="Location" autocomplete="off">
                                                </div>
                                                @error('cover_img')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row mb-4">
                                            <div class="col-md-6">
                                                <label for="shop_logo" class="label-control">Shop Logo <span class="text-danger">*</span></label>
                                                <input type="file" id="shop_logo" class="form-control @error('shop_logo') is-invalid @enderror" name="shop_logo" placeholder="Location" autocomplete="off">
                                                @error('shop_logo')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="location" class="label-control">Location <span class="text-danger">*</span></label>
                                                <input type="text" id="location" value="{{old('location')}}" class="form-control @error('location') is-invalid @enderror" name="location" placeholder="Location" maxlength="100" autocomplete="off">
                                                @error('location')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row mb-4">
                                            <div class="col-md-6">
                                                <label for="latitude" class="label-control">Latitude <span class="text-danger">*</span></label>
                                                <input type="text" id="latitude" value="{{old('latitude')}}" class="form-control @error('latitude') is-invalid @enderror" name="latitude" placeholder="Latitude" maxlength="10" autocomplete="off">
                                                @error('latitude')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <label for="longitude" class="label-control">Longitude <span class="text-danger">*</span></label>
                                                <input type="text" id="longitude" value="{{old('longitude')}}" class="form-control @error('longitude') is-invalid @enderror" name="longitude" placeholder="Longitude" maxlength="10" autocomplete="off">
                                                @error('longitude')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label for="description" class="label-control">Short Description <span class="text-danger">*</span></label>
                                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{old('name')}}</textarea>
                                                @error('description')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
@push('script')
<script>
    $(document).ready(function () {
        $('.remove-multiple-landline').css('display','none');
        $('.remove-multiple-mobile').css('display','none');
        $('.remove-multiple-email').css('display','none');
        $('.remove-multiple-coverImg').css('display','none');
        CKEDITOR.replace( 'description',{
            toolbar: [
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript'] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
            ]
        });
        
        if ("geolocation" in navigator){
            //check geolocation available 
            //try to get user current location using getCurrentPosition() method
            navigator.geolocation.getCurrentPosition(function(position){ 
                    console.log("Found your location \nLat : "+position.coords.latitude+" \nLang :"+ position.coords.longitude);
                    getReverseGeocodingData(''+position.coords.latitude+'', ''+position.coords.longitude+'')
                });
        }else{
            console.log("Browser doesn't support geolocation!");
        }
    });

    // function getReverseGeocodingData(lat, lng) {
    //     var latlng = new google.maps.LatLng(lat, lng);
    //     // This is making the Geocode request
    //     var geocoder = new google.maps.Geocoder();
    //     geocoder.geocode({ 'latLng': latlng },  (results, status) =>{
    //         if (status !== google.maps.GeocoderStatus.OK) {
    //             alert(status);
    //         }
    //         // This is checking to see if the Geoeode Status is OK before proceeding
    //         if (status == google.maps.GeocoderStatus.OK) {
    //             console.log(results);
    //             var address = (results[0].formatted_address);
    //         }
    //     });
    // }

    $('body').on('click','.add-multiple-landline', function () {
        if($('.shop_landline').length < 5) {
            var landlineHtml = '<div class="input-group shop_landline_div">'+
                '<select name="shop_landline_code[]" id="shop_landline_code" class="select2 btn btn-light-secondary @error('shop_landline_code') is-invalid @enderror" required >'+
                    '@if(count($countryCode) > 0)'+
                    '@foreach($countryCode as $code)'+
                    '<option value="{{$code->phone_code}}">{{'+'.$code->phone_code}}</option>'+
                    '@endforeach'+
                    '@endif'+
                '</select>'+
                '<input type="text" id="shop_landline" class="mb-2 shop_landline form-control @error('shop_landline') is-invalid @enderror" name="shop_landline[]" placeholder="Shop Landline Number" maxlength="70" autocomplete="off">'+
                '<span class="btn btn-danger fa fa-minus float-end mb-1 mx-1  multiple-field-btn remove-multiple-landline pd-10"></span>'+
            '</div>';
            $('body').find('.shop_landline_div:last').after(landlineHtml);
        }
        if($('.shop_landline_div').length >= 5) { $('.add-multiple-landline').css('display','none');}
    });
    $('body').on('click','.remove-multiple-landline', function () {
        $(this).parents(".shop_landline_div").remove();

        if($('.shop_landline_div').length >= 5) { $('.add-multiple-landline').css('display','none');} else {$('.add-multiple-landline').css('display','block');}
    });


    $('body').on('click','.add-multiple-mobile', function () {
        if($('.shop_mobile').length < 5) {
            var mobileData = '<div class="input-group shop_mobile_div">'+
                '<select name="shop_mob_code[]" id="shop_mob_code" class="select2 btn btn-light-secondary @error('shop_mob_code') is-invalid @enderror" required >'+
                    '@if(count($countryCode) > 0)'+
                    '@foreach($countryCode as $code)'+
                    '<option value="{{$code->phone_code}}">{{'+'.$code->phone_code}}</option>'+
                    '@endforeach'+
                    '@endif'+
                '</select>'+
                '<input type="text" id="shop_mobile" class="mb-2 form-control @error('shop_mobile') is-invalid @enderror" name="shop_mobile[]" placeholder="Shop Mobile Number" maxlength="70" autocomplete="off">'+
                '<span class="btn btn-danger fa fa-minus float-end mb-1 mx-1  multiple-field-btn remove-multiple-mobile pd-10"></span>'+
            '</div>';
            $('body').find('.shop_mobile_div:last').after(mobileData);
            $('body').find('.select2').select2();
        }
        if($('.shop_mobile_div').length >= 5) { $('.add-multiple-mobile').css('display','none');}
    });
    $('body').on('click','.remove-multiple-mobile', function () {
        $(this).parents(".shop_mobile_div").remove();
        
        if($('.shop_mobile_div').length >= 5) { $('.add-multiple-mobile').css('display','none');} else {$('.add-multiple-mobile').css('display','block');}
    });

    $('body').on('click','.add-multiple-email', function () {
        if($('.shop_email_div').length < 5) {
            $('body').find('.shop_email_div:last').after('<div class="input-group shop_email_div"><input type="text" id="shop_email" class="mb-2 shop_email form-control @error('shop_email') is-invalid @enderror" name="shop_email[]" placeholder="Shop Email Address" maxlength="70" autocomplete="off">'+
            '<span class="btn btn-danger fa fa-minus float-end mb-1 mx-1 multiple-field-btn remove-multiple-email pd-10"></span></div>');
        }
        if($('.shop_email_div').length >= 5) { $('.add-multiple-email').css('display','none');} else {$('.add-multiple-email').css('display','block');}
    });    
    $('body').on('click','.remove-multiple-email', function () {
        $(this).parents(".shop_email_div").remove();
        
        if($('.shop_email_div').length >= 5) { $('.add-multiple-email').css('display','none');} else {$('.add-multiple-email').css('display','block');}
    });

    $('body').on('click','.add-multiple-coverImg', function () {
        if($('.shop_cover_img_div').length < 5) {
            $('body').find('.shop_cover_img_div:last').after('<div class="input-group shop_cover_img_div"><input type="file" id="cover_img" class="mb-2 cover_img form-control @error('cover_img') is-invalid @enderror" name="cover_img[]" autocomplete="off">'+
            '<span class="btn btn-danger fa fa-minus float-end mb-1 mx-1 multiple-field-btn remove-multiple-coverImg pd-10"></span></div>');
        }
        if($('.shop_cover_img_div').length >= 5) { $('.add-multiple-coverImg').css('display','none');} else {$('.add-multiple-coverImg').css('display','block');}
    });    
    $('body').on('click','.remove-multiple-coverImg', function () {
        $(this).parents('.shop_cover_img_div').remove();

        if($('.shop_cover_img_div').length >= 5) { $('.add-multiple-coverImg').css('display','none');} else {$('.add-multiple-coverImg').css('display','block');}
    });
</script>
@endpush