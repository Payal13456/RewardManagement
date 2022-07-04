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

    {{-- <div class="modal fade" id="viewDetailsModal" role="dialog" aria-labelledby="viewDetailsModalTitle" data-backdrop="static" data-keyboard="false" aria-hidden="true">
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
    </div> --}}
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
                            url: baseUrl + '/users-list/' + action,
                            type: 'put',
                            data: {
                                action: action,
                                id: id
                            },
                            success: function(re) {
                                if (re.status === true) {
                                    swal(re.message, {
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
    </script>
@endpush
