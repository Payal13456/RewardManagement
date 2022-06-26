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
        <section class="section row">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Request List</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered" id="reedem-req-list-tbl">
                            <thead class="text-nowrap">
                                <tr>
                                    <th>#</th>
                                    <th>User Name</th>
                                    <th>Amount</th>
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
            
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Approved List</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered" id="reedem-appr-list-tbl">
                            <thead class="text-nowrap">
                                <tr>
                                    <th>#</th>
                                    <th>User Name</th>
                                    <th>Amount</th>
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
            
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Rejected List</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered" id="reedem-rej-list-tbl">
                            <thead class="text-nowrap">
                                <tr>
                                    <th>#</th>
                                    <th>User Name</th>
                                    <th>Amount</th>
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
@endsection
@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#reedem-req-list-tbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: baseUrl + '/reedem-request/list',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', sWidth:'5%' },
                    { data: 'username', name: 'username', sWidth:'25%' },
                    { data: 'amount', name: 'amount', sWidth:'10%', sClass:'text-end' },
                    { data: 'status', name: 'status', orderable: false, searchable: false, sWidth:'15%' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, sWidth:'20%' },
                ]
            });
            
            var table = $('#reedem-appr-list-tbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: baseUrl + '/reedem-approved/list',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', sWidth:'5%' },
                    { data: 'username', name: 'username', sWidth:'25%' },
                    { data: 'amount', name: 'amount', sWidth:'10%', sClass:'text-end' },
                    { data: 'status', name: 'status', orderable: false, searchable: false, sWidth:'15%' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, sWidth:'20%' },
                ]
            });
            
            var table = $('#reedem-rej-list-tbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: baseUrl + '/reedem-rejected/list',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', sWidth:'5%' },
                    { data: 'username', name: 'username', sWidth:'25%' },
                    { data: 'amount', name: 'amount', sWidth:'10%', sClass:'text-end' },
                    { data: 'status', name: 'status', orderable: false, searchable: false, sWidth:'15%' },
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
                        url : baseUrl+'/reedem-request/action',
                        type: 'put',
                        data: {action:action, actionId:actionId},
                        success:function (re) {
                            if (re.status === true) {
                                swal({
                                    title: re.message,
                                    icon: "success",
                                });
                                $('#reedem-req-list-tbl').DataTable().ajax.url(baseUrl+'/reedem-request/list').load();
                                $('#reedem-appr-list-tbl').DataTable().ajax.url(baseUrl+'/reedem-approved/list').load();
                                $('#reedem-rej-list-tbl').DataTable().ajax.url(baseUrl+'/reedem-rejected/list').load();
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
