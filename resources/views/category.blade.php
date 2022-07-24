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

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item cursor-point" role="presentation">
                                <span class="nav-link active" id="category-list-tab" data-bs-toggle="pill" data-bs-target="#category-list" role="tab" aria-controls="category-list" aria-selected="true">Category List</span>
                            </li>
                            <li class="nav-item cursor-point" role="presentation">
                                <span class="nav-link" id="category-add-tab" data-bs-toggle="pill" data-bs-target="#category-add" role="tab" aria-controls="category-add" aria-selected="false">Add Category</span>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="category-list" role="tabpanel" aria-labelledby="category-list-tab">
                                <table class="table table-hover table-bordered" id="category-list-tbl">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Category Name</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="category-add" role="tabpanel" aria-labelledby="category-add-tab">
                                <div class="col-md-12 col-12">
                                    <div class="card">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <form class="form form-horizontal" id="category-form" action="{{URL::route('category-submit')}}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-body">
                                                        <div class="row">
                                                            <input type="hidden" name="editCategoryId" id="editCategoryId">
                                                            <input type="hidden" name="editCategoryImg" id="editCategoryImg">
                                                            <label for="category_name" class="label-control col-md-4">Category Name <span class="text-danger">*</span></label>
                                                            <div class="col-md-8 form-group">
                                                                <input type="text" id="category_name" class="form-control @error('category_name') is-invalid @enderror" name="category_name" placeholder="Category Name" maxlength="50" autocomplete="off">
                                                                @error('category_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <span>{{ $message }}</span>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            
                                                            <label for="category_img" class="label-control col-md-4">Category Image <span class="text-danger">*</span></label>
                                                            <div class="col-md-8 form-group">
                                                                <input type="file" id="category_img" class="form-control @error('category_img') is-invalid @enderror" name="category_img" autocomplete="off">
                                                                @error('category_img')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <span>{{ $message }}</span>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="offset-md-4 input-group image-area d-none" id="categoryImgDiv">
                                                                {{-- <span class="remove-image remove-server-coverImg" data-id="'+re.data.id+'">
                                                                    <i class="fa fa-times"></i>
                                                                </span> --}}
                                                                <img id="categoryImgId">
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
        @if($errors->count() > 0)
            $('#pills-tab').find('#category-list-tab').removeClass('active');
            $('#pills-tab').find('#category-add-tab').addClass('active');

            $('#pills-tabContent').find('.tab-pane').removeClass('show active');
            $('#pills-tabContent').find('#category-add').addClass('show active');
        @endif
        
        var table = $('#category-list-tbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: baseUrl+'/category-list/all',
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name', sClass:'text-wrap'},
                {data: 'image', name: 'image', sClass:'text-wrap'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });

    $(document).on('click','#category-list-tab', function () {
        $('#category-add-tab').text('Add Category');
        $('#category-form').trigger('reset');
        $('#categoryImgDiv').addClass('d-none');
    });

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
                            swal({
                                title:re.message, 
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

    $('body').on('click','.activeDeactiveCategory', function () {
        var id = $(this).attr('data-id');
        var action = $(this).attr('data-action');

        swal({
            title: "Are you sure, You want to "+action+" this category ?",
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
                    url : baseUrl+'/category-list/active-deactive',
                    type: 'put',
                    data: {id:id,action:action},
                    success:function (re) {
                        if (re.status === true) {
                            swal({
                                title:re.message, 
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
                    $('#pills-tab').find('#category-list-tab').removeClass('active');
                    $('#pills-tab').find('#category-add-tab').addClass('active').text('Edit Category');

                    $('#pills-tabContent').find('.tab-pane').removeClass('show active');
                    $('#pills-tabContent').find('#category-add').addClass('show active');

                    $('#editCategoryId').val(re.data.id);
                    $('#editCategoryImg').val(re.data.image);
                    $('#category_name').val(re.data.name);
                    $('#categoryImgDiv').removeClass('d-none');
                    $('#categoryImgId').attr('src', baseUrl+'/public/uploads/category/'+re.data.image).attr('alt', re.data.image);
                }
            }
        });
    });
</script>
@endpush