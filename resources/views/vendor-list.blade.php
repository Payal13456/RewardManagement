@extends('layouts.app')
@section('title')
    Vendor List | {{ config('app.name') }}
@endsection

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
                    <h3>Vendor List</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL::route('/') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Vendor</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Vendor List</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="vendor-list-tbl">
                            <thead class="text-nowrap">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Shop Name</th>
                                    <th>Website</th>
                                    <th>Category</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-nowrap">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="viewDetailsModal" role="dialog" aria-labelledby="viewDetailsModalTitle" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDetailsModalTitle"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body details-modal">
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-3" id="vendorProfile">

                                </div>

                                <div class="col-md-9" id="vendorOtherDetail">
                                    
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#vendor-list-tbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: baseUrl + '/vendor-list/all',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'mobileNo', name: 'mobileNo' },
                    { data: 'shop_name', name: 'shop_name' },
                    { data: 'website', name: 'website' },
                    { data: 'cate_name', name: 'cate_name' },
                    { data: 'location', name: 'location' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });

        $('body').on('click', '.actionRequestVendor', function() {
            var action = $(this).attr('data-action');
            var id = $(this).attr('data-id');
            swal({
                    title: "Are you sure, You want to " + action + " this vendor ?",
                    text: "Once you "+action+" this vendor, All the details of this vendor will be "+action+".",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: baseUrl + '/vendor/action',
                            type: 'GET',
                            data: {
                                action: action,
                                id: id
                            },
                            success: function(re) {
                                if (re.status === true) {
                                    swal({
                                        title: re.message,
                                        icon: "success",
                                    });
                                    $('#vendor-list-tbl').DataTable().ajax.url(baseUrl+'/vendor-list/all').load();
                                } else {
                                    swal({
                                        title: re.message,
                                        icon: "error",
                                    });
                                }
                            }
                        });
                    }
                })
        });

        $('body').on('click', '.viewDetailsVendor', function() {
            var vendorId = $(this).attr('data-id');
            var action = $(this).attr('data-action');            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: baseUrl + '/vendor/details',
                type: 'get',
                data: { action: action, vendorId: vendorId },
                success: function(re) {
                    console.log(re);
                    if (re.status === true) {
                        $('#viewDetailsModalTitle').text('').text('Vendor : '+re.vendor.name);
                        var website = '';
                        var websiteHref = '#!';
                        if(re.vendor.website != null) { 
                            website = re.vendor.website; 
                            websiteHref = re.vendor.website; 
                        }
                        var vendorProfile = '<div class="card card-primary card-outline">'+
                            '<div class="card-body box-profile">'+
                                '<div class="text-center">'+
                                    '<img class="profile-user-img img-fluid img-circle" src="'+baseUrl+'/public/uploads/shop/logo/'+re.vendor.shop_logo+'" alt="'+re.vendor.shop_logo+'">'+
                                '</div>'+
                                '<h3 class="profile-username text-center">'+re.vendor.name+'</h3>'+
                                '<p class="text-muted text-center">'+re.vendor.shop_name+'</p>'+
                                '<ul class="list-group list-group-unbordered mb-3">'+
                                    '<li class="list-group-item">'+
                                        '<b>Mobile No</b> <a class="float-end" href="tel:'+re.vendor.mobile_no+'">+'+re.vendor.phone_code+' '+re.vendor.mobile_no+'</a>'+
                                    '</li>'+
                                    '<li class="list-group-item">'+
                                        '<b>Email</b> <a class="float-end" href="mailto:'+re.vendor.email+'">'+re.vendor.email+'</a>'+
                                    '</li>'+
                                    '<li class="list-group-item">'+
                                        '<b>Website</b> <a href="'+websiteHref+'" target="_blank" class="float-end">'+website+'</a>'+
                                    '</li>'+
                                    '<li class="list-group-item">'+
                                        '<b>Opening Time</b> <a class="float-end">'+re.vendor.opening_time+'</a>'+
                                    '</li>'+
                                    '<li class="list-group-item">'+
                                        '<b>Closing Time</b> <a class="float-end">'+re.vendor.closing_time+'</a>'+
                                    '</li>'+
                                '</ul>'+
                            '</div>'+
                        '</div>';

                        var vendorOthers = '<div class="row">'+
                            '<div class="col-6 col-lg-12 col-md-12">'+
                                '<div class="card">'+
                                    '<div class="card-header p-2">'+
                                        '<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">'+
                                            '<li class="nav-item cursor-point" role="presentation">'+
                                                '<span class="nav-link active" id="landline-div-tab" data-bs-toggle="pill" data-bs-target="#landline-div" role="tab" aria-controls="landline-div" aria-selected="true">Landline</span>'+
                                            '</li>'+
                                            '<li class="nav-item cursor-point" role="presentation">'+
                                                '<span class="nav-link" id="mobile-no-div-tab" data-bs-toggle="pill" data-bs-target="#mobile-no-div" role="tab" aria-controls="mobile-no-div" aria-selected="false">Mobile No</span>'+
                                            '</li>'+
                                            '<li class="nav-item cursor-point" role="presentation">'+
                                                '<span class="nav-link" id="emails-div-tab" data-bs-toggle="pill" data-bs-target="#emails-div" role="tab" aria-controls="emails-div" aria-selected="false">Emails</span>'+
                                            '</li>'+                                            
                                            '<li class="nav-item cursor-point" role="presentation">'+
                                                '<span class="nav-link" id="coverImg-div-tab" data-bs-toggle="pill" data-bs-target="#coverImg-div" role="tab" aria-controls="coverImg-div" aria-selected="false">Cover Img</span>'+
                                            '</li>'+
                                            '<li class="nav-item cursor-point" role="presentation">'+
                                                '<span class="nav-link" id="description-div-tab" data-bs-toggle="pill" data-bs-target="#description-div" role="tab" aria-controls="description-div" aria-selected="false">Description</span>'+
                                            '</li>'+
                                        '</ul>'+
                                    '</div>'+

                                    '<div class="card-body">'+
                                        '<div class="tab-content" id="pills-tabContent">'+
                                            
                                            '<div class="tab-pane fade show active" id="landline-div" role="tabpanel" aria-labelledby="landline-div-tab">'+
                                                '<table class="table table-bordered table-striped" id="landline-div-tbl">'+
                                                    '<thead>'+
                                                        '<tr>'+
                                                            '<th>#</th>'+
                                                            '<th>Phone Code</th>'+
                                                            '<th>Landline</th>'+
                                                        '</tr>'+
                                                    '</thead>'+
                                                    '<tbody>'+
                                                    
                                                    '</tbody>'+
                                                '</table>'+
                                            '</div>'+
                                            
                                            '<div class="tab-pane fade" id="mobile-no-div" role="tabpanel" aria-labelledby="mobile-no-div-tab">'+
                                                '<table class="table table-bordered table-striped" id="mobile-no-div-tbl">'+
                                                    '<thead>'+
                                                        '<tr>'+
                                                            '<th>#</th>'+
                                                            '<th>Phone Code</th>'+
                                                            '<th>Mobile No</th>'+
                                                        '</tr>'+
                                                    '</thead>'+
                                                    '<tbody>'+
                                                    
                                                    '</tbody>'+
                                                '</table>'+
                                            '</div>'+
                                            
                                            '<div class="tab-pane fade" id="emails-div" role="tabpanel" aria-labelledby="emails-div-tab">'+
                                                '<table class="table table-bordered table-striped" id="emails-div-tbl">'+
                                                    '<thead>'+
                                                        '<tr>'+
                                                            '<th>#</th>'+
                                                            '<th>Emails</th>'+
                                                        '</tr>'+
                                                    '</thead>'+
                                                    '<tbody>'+
                                                    
                                                    '</tbody>'+
                                                '</table>'+
                                            '</div>'+
                                            
                                            '<div class="tab-pane fade" id="coverImg-div" role="tabpanel" aria-labelledby="coverImg-div-tab">'+
                                                '<div class="container-fluid">'+
                                                    '<div class="col-md-12">'+
                                                        '<div class="row" id="coverImg-show">'+
                                                            
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+

                                            '<div class="tab-pane fade" id="description-div" role="tabpanel" aria-labelledby="description-div-tab">'+
                                                '<div class="container-fluid">'+
                                                    '<div class="col-md-12">'+
                                                        '<div class="row" id="description-show">'+
                                                            
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+

                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';

                        $('#vendorProfile').html('').html(vendorProfile);
                        $('#vendorOtherDetail').html('').html(vendorOthers);
                        
                        if(re.landLine.length > 0) {
                            var landLineTr = '';
                            var i = 1;
                            $.each(re.landLine, function (key, value) {
                                landLineTr +='<tr>'+
                                    '<td>'+i+'</td>'+
                                    '<td>+'+value.phone_code+'</td>'+
                                    '<td>'+value.landline_no+'</td>'+
                                '</tr>';
                                i++;
                            });
                            $('#landline-div-tbl').find('tbody').html(landLineTr);
                        }

                        if(re.mobileNo.length > 0) {
                            var mobileNoTr = '';
                            var i = 1;
                            $.each(re.mobileNo, function (key, value) {
                                mobileNoTr +='<tr>'+
                                    '<td>'+i+'</td>'+
                                    '<td>+'+value.phone_code+'</td>'+
                                    '<td>'+value.mobile_no+'</td>'+
                                '</tr>';
                                i++;
                            });
                            $('#mobile-no-div-tbl').find('tbody').html(mobileNoTr);
                        }

                        if(re.emails.length > 0) {
                            var emailsTr = '';
                            var i = 1;
                            $.each(re.emails, function (key, value) {
                                emailsTr +='<tr>'+
                                    '<td>'+i+'</td>'+
                                    '<td>'+value.shop_email+'</td>'+
                                '</tr>';
                                i++;
                            });
                            $('#emails-div-tbl').find('tbody').html(emailsTr);
                        }

                        if(re.coverImg.length > 0) {
                            var coverImgHtml = '';
                            $.each(re.coverImg, function (key, value) {
                                coverImgHtml +='<div class="col-md-3">'+
                                    '<img class="d-block w-100" src="'+baseUrl+'/public/uploads/shop/cover/'+value.cover_image+'" alt="'+value.cover_image+'">'+
                                '</div>';
                            });
                            $('#coverImg-show').html('').html(coverImgHtml);
                        }

                        if(re.vendor.description.length > 0) {
                            $('#description-show').html('').html('<div>'+re.vendor.description+'</div>');
                        }

                        $('#landline-div-tbl, #mobile-no-div-tbl, #emails-div-tbl').DataTable({
                            "bDestroy": true,
                            "pageLength": 5,
                            "lengthMenu": [[5, 10, 25, 50, -1],[5, 10, 25, 50, 'All']],
                        });
                        $('#viewDetailsModal').modal('show');
                    }
                    else {
                        swal({
                            title:re.message,
                            icon: 'error',
                        });
                    }
                }
            });
        });
    </script>
@endpush
