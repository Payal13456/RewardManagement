@extends('layouts.app')
@section('title')
    Users List | {{ config('app.name') }}
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
                    <h3>Users List</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL::route('/') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0)">User</a></li>
                            <li class="breadcrumb-item active" aria-current="page">User List</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="user-list-tbl">
                            <thead class="text-nowrap">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>DOB</th>
                                    <th>Location</th>
                                    <th>Emirates ID</th>
                                    <th>Passport</th>
                                    <th>Address</th>
                                    <th>Referal Code</th>
                                    <th>Status</th>
                                    <th>Block Status</th>
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
                    <h5 class="modal-title" id="viewDetailsModalTitle">Vertically Centered </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body details-modal">
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-3" id="userProfile">

                                </div>

                                <div class="col-md-9" id="userOtherDetail">
                                    
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
            var table = $('#user-list-tbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: baseUrl + '/users-list/all',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'mobile_no', name: 'mobile_no' },
                    { data: 'dob', name: 'dob' },
                    { data: 'location', name: 'location', sWidth: '20%' },
                    { data: 'emirates_id', name: 'emirates_id' },
                    { data: 'passport_no', name: 'passport_no' },
                    { data: 'address', name: 'address' },
                    { data: 'referal_code', name: 'referal_code' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'blockStatus', name: 'blockStatus', orderable: false, searchable: false },
                    { data: 'process', name: 'process', orderable: false, searchable: false },
                ]
            });
        });

        $('body').on('click', '.blockUnblockUser', function() {
            var action = $(this).attr('data-action');
            var id = $(this).attr('data-id');
            swal({
                    title: "Are you sure, You want to " + action + " this user ?",
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
                            url: baseUrl + '/users-list/block-unblock',
                            type: 'put',
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
                                    $('#user-list-tbl').DataTable().ajax.url(baseUrl+'/users-list/all').load();
                                } else {
                                    swal(re.message);
                                }
                            }
                        });
                    }
                })
        });

        $('body').on('click', '.activeDeactiveUser', function() {
            var action = $(this).attr('data-action');
            var id = $(this).attr('data-id');
            swal({
                    title: "Are you sure, You want to " + action + " this user ?",
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
                            url: baseUrl + '/users-list/active-deactive',
                            type: 'put',
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
                                    $('#user-list-tbl').DataTable().ajax.url(baseUrl+'/users-list/all').load();
                                } else {
                                    swal(re.message);
                                }
                            }
                        });
                    }
                })
        });

        $('body').on('click', '.viewDetailsUser', function() {
            var userId = $(this).attr('data-id');
            var action = $(this).attr('data-action');            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: baseUrl + '/users-detail',
                type: 'get',
                data: { action: action, userId: userId },
                success: function(re) {
                    if (re.status === true) {
                        $('#viewDetailsModalTitle').text('').text('User : '+re.userDt.name);
                        var userProfile = '<div class="card card-primary card-outline">'+
                            '<div class="card-body box-profile">'+
                                '<div class="text-center">'+
                                    '<img class="profile-user-img img-fluid img-circle" src="{{asset('public/assets/images/faces/2.jpg')}}" alt="User profile picture">'+
                                '</div>'+
                                '<h3 class="profile-username text-center">'+re.userDt.name+'</h3>'+
                                '<p class="text-muted text-center">'+re.userDt.location+'</p>'+
                                '<ul class="list-group list-group-unbordered mb-3">'+
                                    '<li class="list-group-item">'+
                                        '<b>Mobile No</b> <a class="float-end" href="tel:'+re.userDt.mobile_no+'">'+re.userDt.mobile_no+'</a>'+
                                    '</li>'+
                                    '<li class="list-group-item">'+
                                        '<b>Email</b> <a class="float-end" href="mailto:'+re.userDt.email+'">'+re.userDt.email+'</a>'+
                                    '</li>'+
                                    '<li class="list-group-item">'+
                                        '<b>DOB</b> <a class="float-end">'+re.userDt.dob+'</a>'+
                                    '</li>'+
                                    '<li class="list-group-item">'+
                                        '<b>Emirates Id</b> <a class="float-end">--</a>'+
                                    '</li>'+
                                    '<li class="list-group-item">'+
                                        '<b>Passport No</b> <a class="float-end">--</a>'+
                                    '</li>'+
                                '</ul>'+
                            '</div>'+
                        '</div>';

                        var userOther = '<div class="card">'+
                            '<div class="card-header p-2">'+
                                '<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">'+
                                    '<li class="nav-item cursor-point" role="presentation">'+
                                        '<span class="nav-link active" id="payment-history-tab" data-bs-toggle="pill" data-bs-target="#payment-history" role="tab" aria-controls="payment-history" aria-selected="true">Payment History</span>'+
                                    '</li>'+
                                    '<li class="nav-item cursor-point" role="presentation">'+
                                        '<span class="nav-link" id="redemption-history-tab" data-bs-toggle="pill" data-bs-target="#redemption-history" role="tab" aria-controls="redemption-history" aria-selected="false">Redemption History</span>'+
                                    '</li>'+
                                    '<li class="nav-item cursor-point" role="presentation">'+
                                        '<span class="nav-link" id="referral-history-tab" data-bs-toggle="pill" data-bs-target="#referral-history" role="tab" aria-controls="referral-history" aria-selected="false">Referral History</span>'+
                                    '</li>'+
                                    '<li class="nav-item cursor-point" role="presentation">'+
                                        '<span class="nav-link" id="referral-history-tab" data-bs-toggle="pill" data-bs-target="#referral-history" role="tab" aria-controls="referral-history" aria-selected="false">Wallet</span>'+
                                    '</li>'+
                                '</ul>'+
                            '</div>'+
                            '<div class="card-body">'+
                                '<div class="tab-content" id="pills-tabContent">'+
                                    '<div class="tab-pane fade show active" id="payment-history" role="tabpanel" aria-labelledby="payment-history-tab">'+
                                        '<div class="table-responsive"></div>'+
                                        '<table class="table table-bordered table-striped" id="payment-history-tbl">'+
                                            '<thead>'+
                                                '<tr>'+
                                                    '<th>#</th>'+
                                                    '<th>Plan</th>'+
                                                    '<th>Transaction ID</th>'+
                                                    '<th>Expiry Date</th>'+
                                                    '<th>Is Expired</th>'+
                                                    '<th>Status</th>'+
                                                '</tr>'+
                                            '</thead>'+
                                            '<tbody>'+
                                            '</tbody>'+
                                        '</table>'+
                                    '</div>'+
                                    '<div class="tab-pane fade" id="redemption-history" role="tabpanel" aria-labelledby="redemption-history-tab">'+
                                        '<table class="table table-bordered table-striped" id="redemption-history-tbl">'+
                                            '<thead>'+
                                                '<tr>'+
                                                    '<th>#</th>'+
                                                    '<th>Request Date</th>'+
                                                    '<th>Request Amount</th>'+
                                                    '<th>Approved Status</th>'+
                                                '</tr>'+
                                            '</thead>'+
                                            '<tbody>'+
                                            '</tbody>'+
                                        '</table>'+
                                    '</div>'+
                                    '<div class="tab-pane fade" id="referral-history" role="tabpanel" aria-labelledby="referral-history-tab">'+
                                        '<table class="table table-bordered table-striped" id="referral-history-tbl">'+
                                            '<thead>'+
                                                '<tr>'+
                                                    '<th>#</th>'+
                                                    '<th>Date</th>'+
                                                    '<th>Referral Code</th>'+
                                                    '<th>Amount</th>'+
                                                    '<th>Status</th>'+
                                                '</tr>'+
                                            '</thead>'+
                                            '<tbody>'+
                                            '</tbody>'+
                                        '</table>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';

                        $('#userProfile').html('').html(userProfile);
                        $('#userOtherDetail').html('').html(userOther);
                        
                        if(re.subscript.length > 0) {
                            var tableSTr = '';
                            $.each(re.subscript, function (key, value) {
                                tableSTr += '<tr>'+
                                    '<td>'+(key+1)+'</td>'+
                                    '<td>'+value.plan_name+'</td>'+
                                    '<td>'+value.transaction_id+'</td>'+
                                    '<td>'+value.expiry_date+'</td>'+
                                    '<td>'+value.is_expired+'</td>'+
                                    '<td>'+value.status+'</td>'+
                                '</tr>';
                            });
                            $('#payment-history-tbl').find('tbody').html(tableSTr);
                        }

                        if(re.redem.length > 0) {
                            var tableRTr = '';
                            $.each(re.redem, function (key, value) {
                                tableRTr += '<tr>'+
                                    '<td>'+(key+1)+'</td>'+
                                    '<td>'+value.req_date+'</td>'+
                                    '<td>'+value.amount+'</td>'+
                                    '<td>'+value.is_approved+'</td>'+
                                '</tr>';
                            });
                            $('#redemption-history-tbl').find('tbody').html(tableRTr);
                        }

                        if(re.referral.length > 0) {
                            var tableReTr = '';
                            $.each(re.referral, function (key, value) {
                                tableReTr += '<tr>'+
                                    '<td>'+(key+1)+'</td>'+
                                    '<td>'+value.ref_date+'</td>'+
                                    '<td>'+value.referal_code+'</td>'+
                                    '<td>'+value.amount+'</td>'+
                                    '<td>'+value.status+'</td>'+
                                '</tr>';
                            });
                            $('#referral-history-tbl').find('tbody').html(tableReTr);
                        }

                        $('#payment-history-tbl, #redemption-history-tbl, #referral-history-tbl').DataTable({
                            "bDestroy": true,
                            "pageLength": 5,
                            "lengthMenu": [[5, 10, 25, 50, -1],[5, 10, 25, 50, 'All']],
                        });
                    }
                }
            });
            $('#viewDetailsModal').modal('show');
        });
    </script>
@endpush
