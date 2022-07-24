@extends('layouts.app')
@section('title')
    Reedem Request | {{ config('app.name') }}
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
                    <h3>Reedem Request</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL::route('/') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reedem Request</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item cursor-point" role="presentation">
                                <span class="nav-link active" id="request-list-tab" data-bs-toggle="pill" data-bs-target="#request-list" role="tab" aria-controls="request-list" aria-selected="true">Request List</span>
                            </li>
                            <li class="nav-item cursor-point" role="presentation">
                                <span class="nav-link" id="approved-list-tab" data-bs-toggle="pill" data-bs-target="#approved-list" role="tab" aria-controls="approved-list" aria-selected="false">Approved List</span>
                            </li>
                            <li class="nav-item cursor-point" role="presentation">
                                <span class="nav-link" id="rejected-list-tab" data-bs-toggle="pill" data-bs-target="#rejected-list" role="tab" aria-controls="rejected-list" aria-selected="false">Rejected List</span>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="request-list" role="tabpanel" aria-labelledby="request-list-tab">
                                <table class="table table-hover table-bordered" id="redeem-req-list-tbl">
                                    <thead class="text-nowrap">
                                        <tr>
                                            <th>#</th>
                                            <th>User Name</th>
                                            <th>Amount (in AED)</th>
                                            {{-- <th>Status</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-nowrap">
        
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade show" id="approved-list" role="tabpanel" aria-labelledby="approved-list-tab">
                                <table class="table table-hover table-bordered" id="redeem-appr-list-tbl">
                                    <thead class="text-nowrap">
                                        <tr>
                                            <th>#</th>
                                            <th>User Name</th>
                                            <th>Amount (in AED)</th>
                                            {{-- <th>Status</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-nowrap">
        
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade show" id="rejected-list" role="tabpanel" aria-labelledby="rejected-list-tab">
                                <table class="table table-hover table-bordered" id="redeem-rej-list-tbl">
                                    <thead class="text-nowrap">
                                        <tr>
                                            <th>#</th>
                                            <th>User Name</th>
                                            <th>Amount (in AED)</th>
                                            {{-- <th>Status</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-nowrap">
        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection
@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#redeem-req-list-tbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: baseUrl + '/redeem-request/list',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', sWidth:'5%' },
                    { data: 'username', name: 'username', sWidth:'25%' },
                    { data: 'amount', name: 'amount', sWidth:'10%', sClass:'text-end' },
                    // { data: 'status', name: 'status', orderable: false, searchable: false, sWidth:'15%' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, sWidth:'20%' },
                ]
            });
            
            var table = $('#redeem-appr-list-tbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: baseUrl + '/redeem-approved/list',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', sWidth:'5%' },
                    { data: 'username', name: 'username', sWidth:'25%' },
                    { data: 'amount', name: 'amount', sWidth:'10%', sClass:'text-end' },
                    // { data: 'status', name: 'status', orderable: false, searchable: false, sWidth:'15%' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, sWidth:'20%' },
                ]
            });
            
            var table = $('#redeem-rej-list-tbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: baseUrl + '/redeem-rejected/list',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', sWidth:'5%' },
                    { data: 'username', name: 'username', sWidth:'25%' },
                    { data: 'amount', name: 'amount', sWidth:'10%', sClass:'text-end' },
                    // { data: 'status', name: 'status', orderable: false, searchable: false, sWidth:'15%' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, sWidth:'20%' },
                ]
            });
        });

        $(document).on('click','.action-request', function () {
            var action = $(this).attr('data-action');
            var actionId = $(this).attr('data-id');
            swal({
                title: "Are you sure, You want to "+action+" this request ?",
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
                        url : baseUrl+'/redeem-request/action',
                        type: 'put',
                        data: {action:action, actionId:actionId},
                        success:function (re) {
                            if (re.status === true) {
                                swal({
                                    title: re.message,
                                    icon: "success",
                                });
                                $('#redeem-req-list-tbl').DataTable().ajax.url(baseUrl+'/redeem-request/list').load();
                                $('#redeem-appr-list-tbl').DataTable().ajax.url(baseUrl+'/redeem-approved/list').load();
                                $('#redeem-rej-list-tbl').DataTable().ajax.url(baseUrl+'/redeem-rejected/list').load();
                            }
                            else {
                                swal(re.message);
                            }
                        }
                    });
                }
            })
        });
    </script>
@endpush
