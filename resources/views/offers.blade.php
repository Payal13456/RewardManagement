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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item cursor-point" role="presentation">
                                <span class="nav-link active" id="offers-list-tab" data-bs-toggle="pill" data-bs-target="#offers-list" role="tab" aria-controls="offers-list" aria-selected="true">Offers List</span>
                            </li>
                            <li class="nav-item cursor-point" role="presentation">
                                <span class="nav-link" id="offers-add-tab" data-bs-toggle="pill" data-bs-target="#offers-add" role="tab" aria-controls="offers-add" aria-selected="false">Add Offers</span>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="offers-list" role="tabpanel" aria-labelledby="offers-list-tab">
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

                            <div class="tab-pane fade" id="offers-add" role="tabpanel" aria-labelledby="offers-add-tab">
                                <div class="col-md-12 col-12">
                                    <div class="card">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <form class="form form-horizontal" id="offers-form" action="{{URL::route('offers-submit')}}" method="POST">
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

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>    
</div>
@endsection
@push('style')
<style>
    .select2.select2-container {
        width: 100% !important;
    }
</style>
@endpush
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

        $(document).on('click','#offers-list-tab', function () {
            $('#offers-add-tab').text('Add Offers');
            $('#offers-form').trigger('reset');
            $('#vendor_id').val('').change();
            CKEDITOR.instances['offer_description'].setData('');
        })

        CKEDITOR.replace( 'offer_description',{
            toolbar: [
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript'] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
            ]
        });

        $('body').on('click','.remove-offers', function () {
            var id = $(this).attr('data-id');
            swal({
                title: "Are you sure, You want to delete this offers ?",
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
                        url : baseUrl+'/offers/delete',
                        type: 'delete',
                        data: {id:id},
                        success:function (re) {
                            if (re.status === true) {
                                swal(re.message, {
                                    icon: "success",
                                });
                                $('#offers-list-tbl').DataTable().ajax.url(baseUrl+'/offers/list').load();
                            }
                            else {
                                swal({
                                    title: re.message,
                                    icon: "warning",
                                });
                            }
                        }
                    });
                }
            });
        });

        $('body').on('click','.activeDeactiveOffers', function () {
            var id = $(this).attr('data-id');
            var action = $(this).attr('data-action');
            swal({
                title: "Are you sure, You want to "+action+" this offers ?",
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
                        url : baseUrl+'/offers/active-deactive',
                        type: 'put',
                        data: {id:id,action:action},
                        success:function (re) {
                            if (re.status === true) {
                                swal({
                                    title:re.message, 
                                    icon: "success",
                                });
                                $('#offers-list-tbl').DataTable().ajax.url(baseUrl+'/offers/list').load();
                            }
                            else {
                                swal({
                                    title: re.message,
                                    icon: "warning",
                                });
                            }
                        }
                    });
                }
            });
        });

        $('body').on('click','.edit-offers', function () {
            var id = $(this).attr('data-id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : baseUrl+'/offers/edit',
                type: 'get',
                data: {id:id},
                success:function (re) {
                    console.log(re);
                    if (re.status === true) {
                        $('#pills-tab').find('#offers-list-tab').removeClass('active');
                        $('#pills-tab').find('#offers-add-tab').addClass('active');

                        $('#pills-tabContent').find('.tab-pane').removeClass('show active');
                        $('#pills-tabContent').find('#offers-add').addClass('show active');

                        $('#editOffersId').val(re.data.id);
                        $('#vendor_id').val(re.data.vendor_id).change();
                        $('#start_date').val(re.data.start_date).change();
                        $('#end_date').val(re.data.end_date).change();
                        CKEDITOR.instances['offer_description'].setData(re.data.offer_desc);

                        $('#offers-add-tab').text('Edit Offers');
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