@extends('layouts.app')
@section('title') Category | {{ config('app.name') }} @endsection

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
                <h3>Category</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{URL::route('/')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Vendor</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Category</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Basic Horizontal form layout section start -->
    <section id="basic-horizontal-layouts">
        <div class="row match-height">
            <div class="col-md-5 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Category</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" action="{{URL::route('category-submit')}}" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <input type="hidden" name="editCategoryId" id="editCategoryId">
                                        <label for="category_name" class="label-control col-md-4">Category Name <span class="text-danger">*</span></label>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="category_name" class="form-control @error('category_name') is-invalid @enderror" name="category_name" placeholder="Category Name" maxlength="50" autocomplete="off">
                                            @error('category_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <span>{{ $message }}</span>
                                                </span>
                                            @enderror
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

            <div class="col-md-7 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Category List</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <table class="table table-striped" id="category-list-tbl">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

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
<script>
    $(document).ready(function () {
        var table = $('#category-list-tbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: baseUrl+'/category-list/all',
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name', sClass:'text-wrap'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });

    @if (Session::has('error'))
        swal('{{ Session::get('error') }}', {
            icon: "error",
        });
        
    @elseif(Session::has('success'))
        swal('{{ Session::get('success') }}', {
            icon: "success",
        });
    @endif

    $('body').on('click','.remove-category', function () {
        var id = $(this).attr('data-id');
        swal({
            title: "Are you sure, You want to delete this category ?",
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
                    url : baseUrl+'/category-list/delete',
                    type: 'delete',
                    data: {id:id},
                    success:function (re) {
                        if (re.status === true) {
                            swal(re.message, {
                                icon: "success",
                            });
                            $('#category-list-tbl').DataTable().ajax.url(baseUrl+'/category-list/all').load();
                        }
                        else {
                            swal(re.message);
                        }
                    }
                });
            }
        })
    });

    $('body').on('click','.edit-category', function () {
        var id = $(this).attr('data-id');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url : baseUrl+'/category-list/edit',
            type: 'get',
            data: {id:id},
            success:function (re) {
                if (re.status === true) {
                    $('#editCategoryId').val(re.data.id);
                    $('#category_name').val(re.data.name);
                }
            }
        });
    });
</script>
@endpush