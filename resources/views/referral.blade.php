@extends('layouts.app')
@section('title') Referral | {{ config('app.name') }} @endsection

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
                <h3>Referral</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{URL::route('/')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">User</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Referral</li>
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
                                <span class="nav-link active" id="referral-list-tab" data-bs-toggle="pill" data-bs-target="#referral-list" role="tab" aria-controls="referral-list" aria-selected="true">Referral List</span>
                            </li>
                            <li class="nav-item cursor-point" role="presentation">
                                <span class="nav-link" id="referral-add-tab" data-bs-toggle="pill" data-bs-target="#referral-add" role="tab" aria-controls="referral-add" aria-selected="false">Add Referral</span>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="referral-list" role="tabpanel" aria-labelledby="referral-list-tab">
                                <table class="table table-hover table-bordered" id="referral-list-tbl">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Amount (in AED)</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="referral-add" role="tabpanel" aria-labelledby="referral-add-tab">
                                <div class="col-md-12 col-12">
                                    <div class="card">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <form class="form form-horizontal" action="{{URL::route('referral-submit')}}" method="POST">
                                                    @csrf
                                                    <div class="form-body">
                                                        <div class="row">
                                                            <input type="hidden" name="editReferral" id="editReferral">
                                                            <label for="referral_amt" class="label-control col-md-4">Referral Amount (in AED) <span class="text-danger">*</span></label>
                                                            <div class="col-md-8 form-group">
                                                                <input type="text" id="referral_amt" class="form-control @error('referral_amt') is-invalid @enderror" name="referral_amt" placeholder="Referral Amount" maxlength="10" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                                @error('referral_amt')
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
            $('#pills-tab').find('#referral-list-tab').removeClass('active');
            $('#pills-tab').find('#referral-add-tab').addClass('active');

            $('#pills-tabContent').find('.tab-pane').removeClass('show active');
            $('#pills-tabContent').find('#referral-add').addClass('show active');
        @endif
        
        var table = $('#referral-list-tbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: baseUrl+'/referral-list/all',
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', sWidth:'10%'},
                {data: 'referral_amt', name: 'referral_amt', sClass:'text-wrap', sWidth:'50%'},
                {data: 'status', name: 'status', orderable: false, searchable: false, sWidth:'10%'},
                {data: 'action', name: 'action', orderable: false, searchable: false, sWidth:'30%'},
            ]
        });
    });

    $('body').on('click','.removeReferral', function () {
        var id = $(this).attr('data-id');
        swal({
            title: "Are you sure, You want to delete this Referral ?",
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
                    url : baseUrl+'/referral-list/delete',
                    type: 'delete',
                    data: {id:id},
                    success:function (re) {
                        if (re.status === true) {
                            swal({
                                title:re.message, 
                                icon: "success",
                            });
                            $('#referral-list-tbl').DataTable().ajax.url(baseUrl+'/referral-list/all').load();
                        }
                        else {
                            swal({
                                title:re.message, 
                                icon: "error",
                            });
                        }
                    }
                });
            }
        })
    });

    $('body').on('click','.activeDeactiveReferral', function () {
        var id = $(this).attr('data-id');
        var action = $(this).attr('data-action');

        swal({
            title: "Are you sure, You want to "+action+" this Referral ?",
            text: "Once you "+action+" this Referral, Othen will be Deactive.",
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
                    url : baseUrl+'/referral-list/active-deactive',
                    type: 'patch',
                    data: {id:id,action:action},
                    success:function (re) {
                        if (re.status === true) {
                            swal({
                                title:re.message, 
                                icon: "success",
                            });
                            $('#referral-list-tbl').DataTable().ajax.url(baseUrl+'/referral-list/all').load();
                        }
                        else {
                            swal({
                                title:re.message, 
                                icon: "error",
                            });
                        }
                    }
                });
            }
        })
    });

</script>
@endpush