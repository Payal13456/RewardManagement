@extends('layouts.app')
@section('title') Offers | {{ config('app.name') }} @endsection

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
                <h3>Offers</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{URL::route('/')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Vendor</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Offers</li>
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
                        <h4 class="card-title">Add Offers</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" action="{{URL::route('offers-submit')}}" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <input type="hidden" name="editOffersId" id="editOffersId">
                                        
                                        <label for="vendor_id" class="col-md-4 label-control">Vendor <span class="text-danger">*</span></label>
                                        <div class="col-md-8 form-group">
                                            <select name="vendor_id" id="vendor_id" class="select2 form-control @error('vendor_id') is-invalid @enderror">
                                                <option value="">Select Vendor </option>
                                                @if(count($vendor) > 0)
                                                @foreach($vendor as $ven)
                                                <option value="{{$ven->id}}">{{$ven->shop_name.' ('.$ven->name.')'}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('vendor_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <span>{{ $message }}</span>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <label for="start_date" class="col-md-4 label-control">Start Date <span class="text-danger">*</span></label>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="start_date" class="calsTotal form-control @error('start_date') is-invalid @enderror" name="start_date" autocomplete="off" readonly />
                                            @error('start_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <span>{{ $message }}</span>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <label for="end_date" class="col-md-4 label-control">End Date <span class="text-danger">*</span></label>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="end_date" class="calsTotal form-control @error('end_date') is-invalid @enderror" name="end_date" autocomplete="off" readonly />
                                            @error('end_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <span>{{ $message }}</span>
                                                </span>
                                            @enderror
                                        </div>

                                        <label for="offer_description" class="col-md-4 label-control">Description <span class="text-danger">*</span> 
                                            <br><small>(offers short description)</small> </label>
                                        <div class="col-md-8 form-group">
                                            <textarea type="text" id="offer_description" class="form-control @error('offer_description') is-invalid @enderror" rows="8" name="offer_description" placeholder="Write something about offers...!" autocomplete="off" maxlength="200"></textarea>
                                            @error('offer_description')
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Offers List</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="offers-list-tbl">
                                    <thead class="text-nowrap">
                                        <tr>
                                            <th>#</th>
                                            <th>Vendor</th>
                                            <th>Offer Description</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
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
                </div>
            </div>
        </div>
    </section>    
</div>
@endsection
@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#offers-list-tbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: baseUrl + '/offers/list',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'shop_name', name: 'shop_name', sWidth:'15%' },
                    { data: 'offer_desc', name: 'offer_desc', sClass:'text-wrap', sWidth:'50%'},
                    { data: 'start_date', name: 'start_date', sWidth:'10%'},
                    { data: 'end_date', name: 'end_date', sWidth:'10%'},
                    { data: 'status', name: 'status', orderable: false, searchable: false  },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });

        $('body').on('click','.remove-plans', function () {
            var id = $(this).attr('data-id');
            swal({
                title: "Are you sure, You want to delete this Plan ?",
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
                        url : baseUrl+'/subscription-plans/delete',
                        type: 'delete',
                        data: {id:id},
                        success:function (re) {
                            if (re.status === true) {
                                swal(re.message, {
                                    icon: "success",
                                });
                                $('#subscription-plan-tbl').DataTable().ajax.url(baseUrl+'/subscription-plans/list').load();
                            }
                            else {
                                swal({
                                    title: re.message,
                                    icon: "warning",
                                    // buttons: true,
                                    // dangerMode: true,
                                });
                            }
                        }
                    });
                }
            });
        });

        $('body').on('click','.edit-plans', function () {
            var id = $(this).attr('data-id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : baseUrl+'/subscription-plans/edit',
                type: 'get',
                data: {id:id},
                success:function (re) {
                    console.log(re);
                    if (re.status === true) {
                        $('#editPlansId').val(re.data.id);
                        $('#plan_name').val(re.data.name);
                        $('#plan_validity').val(re.data.validity);
                        $('#plan_amount').val(re.data.amount);
                        $('#plan_tax').val(re.data.tax);
                        $('#plan_total').val(re.data.total);
                    }
                }
            });
        });

        $("#start_date").daterangepicker({
            singleDatePicker : true,
            autoApply : true,
            locale : {
                format : "DD-MM-YYYY",
                cancelLabel : 'Clear'
            },
            startDate:moment().add(0, 'days'),
            minDate:moment().add(0, 'days'),
            showDropdowns : true,
        });
        
        $(document).on('change, click','#start_date', function () {
            // var startDate = $(this).val();
            // startDate = new Date(startDate);
            // var currDate = new Date();
            // console.log(startDate);
            // console.log(currDate);
            $("#end_date").daterangepicker({
                singleDatePicker : true,
                autoApply : true,
                locale : {
                    format : "DD-MM-YYYY",
                    cancelLabel : 'Clear'
                },
                startDate:moment().add(0, 'days'),
                minDate:moment().add(0, 'days'),
                showDropdowns : true,
            });
        });

    </script>
@endpush