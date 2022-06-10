@extends('layouts.app')
@section('title') Users List | {{ config('app.name') }} @endsection

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
                            <li class="breadcrumb-item"><a href="{{URL::route('/')}}">Dashboard</a></li>
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
                    <table class="table table-striped" id="user-list-tbl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Passport</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>

@endsection
@push('script')
<script type="text/javascript">
    $(document).ready(function () {
        var table = $('#user-list-tbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: baseUrl+'/users-list/all',
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name', sClass:'text-wrap'},
                {data: 'email', name: 'email', sClass:'text-wrap'},
                {data: 'mobile_no', name: 'mobile_no', sClass:'text-wrap'},
                {data: 'passport_no', name: 'passport_no', sClass:'text-wrap'},
                {data: 'address', name: 'address', sClass:'text-wrap'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'process', name: 'process', orderable: false, searchable: false},
            ]
        });
    });

    $('body').on('click','.blockUnblockUser', function () {
        var action = $(this).attr('data-action');
        var id = $(this).attr('data-id');

        swal({
            title: "Are you sure, You want to "+action+" this user ?",
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
                    url : baseUrl+'/users-list/'+action,
                    type: 'put',
                    data: {action:action, id:id},
                    success:function (re) {
                        if (re.status === true) {
                            swal(re.message, {
                                icon: "success",
                            });
                            $('#user-list-tbl').DataTable().ajax.url(baseUrl+'/users-list/all').load();
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