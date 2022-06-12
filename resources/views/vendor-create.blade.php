@extends('layouts.app')
@section('title') Create Vendor | {{ config('app.name') }} @endsection

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
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Basic Horizontal form layout section start -->
    <section id="basic-horizontal-layouts">
        <div class="row match-height">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Vendor</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" action="{{URL::route('category-submit')}}" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <input type="hidden" name="editCategoryId" id="editCategoryId">
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="name" class="label-control">Name <span class="text-danger">*</span></label>
                                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" maxlength="50" autocomplete="off" oninput="this.value = this.value.replace(/[^A-Za-z.]/g, '').replace(/(\..*)\./g, '$1');">
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label for="mobile_no" class="label-control">Mobile No <span class="text-danger">*</span></label>
                                                <input type="text" id="mobile_no" class="form-control @error('mobile_no') is-invalid @enderror" name="mobile_no" placeholder="Mobile Number" maxlength="15" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                @error('mobile_no')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label for="email" class="label-control">Email <span class="text-danger">*</span></label>
                                                <input type="text" id="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" maxlength="50" autocomplete="off">
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="category_id" class="label-control">Category <span class="text-danger">*</span></label>
                                                <select name="category_id" id="category_id" class="select2 form-control @error('category_id') is-invalid @enderror">
                                                    <option value="" selected >Select Category</option>
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

                                            <div class="col-md-4">
                                                <label for="shop_name" class="label-control">Shop Name <span class="text-danger">*</span></label>
                                                <input type="text" id="shop_name" class="form-control @error('shop_name') is-invalid @enderror" name="shop_name" placeholder="Shop Name" maxlength="70" autocomplete="off">
                                                @error('shop_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label for="website" class="label-control">Website <span class="text-danger">*</span></label>
                                                <input type="url" id="website" class="form-control @error('website') is-invalid @enderror" name="website" placeholder="Website" maxlength="70" autocomplete="off">
                                                @error('website')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="shop_landline" class="label-control">Shop Landline <span class="text-danger">*</span></label>
                                                <span class="fa fa-plus btn btn-primary btn-xs multiple-field-btn add-multiple-landline float-end mb-1"></span>
                                                <span class="fa fa-minus mx-1 btn btn-danger btn-xs multiple-field-btn remove-multiple-landline float-end mb-1"></span>

                                                <input type="text" id="shop_landline" class="mb-2 shop_landline form-control @error('shop_landline') is-invalid @enderror" name="shop_landline[]" placeholder="Shop Landline Number" maxlength="70" autocomplete="off">
                                                @error('shop_landline')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <label for="shop_mobile" class="label-control">Shop Mobile <span class="text-danger">*</span></label>
                                                <span class="fa fa-plus btn btn-primary btn-xs multiple-field-btn add-multiple-mobile float-end mb-1"></span>
                                                <span class="fa fa-minus mx-1 btn btn-danger btn-xs multiple-field-btn remove-multiple-mobile float-end mb-1"></span>
                                                
                                                <div class="input-group shop_mobile">
                                                    <select name="category_id" id="category_id" class="btn btn-light-secondary @error('category_id') is-invalid @enderror dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <option value="91">+91</option>
                                                        <option value="92">+92</option>
                                                        <option value="93">+93</option>
                                                        <option value="94">+94</option>
                                                        <option value="95">+95</option>
                                                        <option value="96">+96</option>
                                                    </select>
                                                    <input type="text" id="shop_mobile" class="mb-2 form-control @error('shop_mobile') is-invalid @enderror" name="shop_mobile[]" placeholder="Shop Mobile Number" maxlength="70" autocomplete="off">
                                                </div>
                                                @error('shop_mobile')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label for="shop_email" class="label-control">Shop Email <span class="text-danger">*</span></label>
                                                <span class="fa fa-plus btn btn-primary btn-xs multiple-field-btn add-multiple-email float-end mb-1"></span>
                                                <span class="fa fa-minus mx-1 btn btn-danger btn-xs multiple-field-btn remove-multiple-email float-end mb-1"></span>

                                                <input type="text" id="shop_email" class="mb-2 shop_email form-control @error('shop_email') is-invalid @enderror" name="shop_email[]" placeholder="Shop Email Address" maxlength="70" autocomplete="off">
                                                @error('shop_email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">                                        
                                            <div class="col-md-4">
                                                <label for="location" class="label-control">Location <span class="text-danger">*</span></label>
                                                <input type="text" id="location" class="form-control @error('location') is-invalid @enderror" name="location" placeholder="Location" maxlength="100" autocomplete="off">
                                                @error('location')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <label for="latitude" class="label-control">Latitude <span class="text-danger">*</span></label>
                                                <input type="text" id="latitude" class="form-control @error('latitude') is-invalid @enderror" name="latitude" placeholder="Latitude" maxlength="10" autocomplete="off">
                                                @error('latitude')
                                                    <span class="invalid-feedback" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </span>
                                                @enderror
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <label for="longitude" class="label-control">Longitude <span class="text-danger">*</span></label>
                                                <input type="text" id="longitude" class="form-control @error('longitude') is-invalid @enderror" name="longitude" placeholder="Longitude" maxlength="10" autocomplete="off">
                                                @error('longitude')
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
    $('.select2').select2();

    $(document).ready(function () {
        $('.remove-multiple-landline').css('display','none');
        $('.remove-multiple-mobile').css('display','none');
        $('.remove-multiple-email').css('display','none');
        
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

    function getReverseGeocodingData(lat, lng) {
        var latlng = new google.maps.LatLng(lat, lng);
        // This is making the Geocode request
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'latLng': latlng },  (results, status) =>{
            if (status !== google.maps.GeocoderStatus.OK) {
                alert(status);
            }
            // This is checking to see if the Geoeode Status is OK before proceeding
            if (status == google.maps.GeocoderStatus.OK) {
                console.log(results);
                var address = (results[0].formatted_address);
            }
        });
    }

    $('body').on('click','.add-multiple-landline', function () {
        if($('.shop_landline').length < 5) {
            $('body').find('.shop_landline:last').after('<input type="text" id="shop_landline" class="mb-2 shop_landline form-control @error('shop_landline') is-invalid @enderror" name="shop_landline[]" placeholder="Shop Landline Number" maxlength="70" autocomplete="off">');
        }
        if($('.shop_landline').length == 1) { $('.remove-multiple-landline').css('display','none');} else {$('.remove-multiple-landline').css('display','block');}
        if($('.shop_landline').length >= 5) { $('.add-multiple-landline').css('display','none');} else {$('.add-multiple-landline').css('display','block');}
    });
    $('body').on('click','.remove-multiple-landline', function () {
        $('body').find('.shop_landline:last').remove();
        if($('.shop_landline').length == 1) { $('.remove-multiple-landline').css('display','none');} else {$('.remove-multiple-landline').css('display','block');}
        if($('.shop_landline').length >= 5) { $('.add-multiple-landline').css('display','none');} else {$('.add-multiple-landline').css('display','block');}
    });

    $('body').on('click','.add-multiple-mobile', function () {
        if($('.shop_mobile').length < 5) {
            var mobileData = '<div class="input-group shop_mobile">'+
                '<select name="category_id" id="category_id" class="btn btn-light-secondary @error('category_id') is-invalid @enderror dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">'+
                    '<option value="91">+91</option>'+
                    '<option value="92">+92</option>'+
                    '<option value="93">+93</option>'+
                    '<option value="94">+94</option>'+
                    '<option value="95">+95</option>'+
                    '<option value="96">+96</option>'+
                '</select>'+
                '<input type="text" id="shop_mobile" class="mb-2 form-control @error('shop_mobile') is-invalid @enderror" name="shop_mobile[]" placeholder="Shop Mobile Number" maxlength="70" autocomplete="off">'+
            '</div>';
            $('body').find('.shop_mobile:last').after(mobileData);
        }
        if($('.shop_mobile').length == 1) { $('.remove-multiple-mobile').css('display','none');} else {$('.remove-multiple-mobile').css('display','block');}
        if($('.shop_mobile').length >= 5) { $('.add-multiple-mobile').css('display','none');} else {$('.add-multiple-mobile').css('display','block');}
    });
    $('body').on('click','.remove-multiple-mobile', function () {
        $('body').find('.shop_mobile:last').remove();
        if($('.shop_mobile').length == 1) { $('.remove-multiple-mobile').css('display','none');} else {$('.remove-multiple-mobile').css('display','block');}
        if($('.shop_mobile').length >= 5) { $('.add-multiple-mobile').css('display','none');} else {$('.add-multiple-mobile').css('display','block');}
    });

    $('body').on('click','.add-multiple-email', function () {
        if($('.shop_email').length < 5) {
            $('body').find('.shop_email:last').after('<input type="text" id="shop_email" class="mb-2 shop_email form-control @error('shop_email') is-invalid @enderror" name="shop_email[]" placeholder="Shop Email Address" maxlength="70" autocomplete="off">');
        }
        if($('.shop_email').length == 1) { $('.remove-multiple-email').css('display','none');} else {$('.remove-multiple-email').css('display','block');}
        if($('.shop_email').length >= 5) { $('.add-multiple-email').css('display','none');} else {$('.add-multiple-email').css('display','block');}
    });    
    $('body').on('click','.remove-multiple-email', function () {
        $('body').find('.shop_email:last').remove();
        if($('.shop_email').length == 1) { $('.remove-multiple-email').css('display','none');} else {$('.remove-multiple-email').css('display','block');}
        if($('.shop_email').length >= 5) { $('.add-multiple-email').css('display','none');} else {$('.add-multiple-email').css('display','block');}
    });
</script>
@endpush