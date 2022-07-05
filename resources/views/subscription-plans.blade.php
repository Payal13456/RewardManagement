@extends('layouts.app')
@section('title') Subscription Plan | {{ config('app.name') }} @endsection

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
                <h3>Plans</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{URL::route('/')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Plans</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Basic Horizontal form layout section start -->
    <section id="basic-horizontal-layouts">
        <div class="row match-height">
            {{-- <div class="offset-md-1 col-md-11 col-12">
                <div class="card">
                    <div class="card-header d-flex">
                        <h4 class="card-title col-md-10">Plans List</h4>
                        <button class="btn btn-primary btn-sm col-md-2 add-subscription-plan">Add Plans/Subscription</button>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="subscription-plan-tbl">
                                    <thead class="text-nowrap">
                                        <tr>
                                            <th>#</th>
                                            <th>Plan Name</th>
                                            <th>Plan Validity</th>
                                            <th>Plan Amount</th>
                                            <th>Plan Tax</th>
                                            <th>Total Plan Amount</th>
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
            </div> --}}

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item cursor-point" role="presentation">
                                <span class="nav-link active" id="plan-subscription-list-tab" data-bs-toggle="pill" data-bs-target="#plan-subscription-list" role="tab" aria-controls="plan-subscription-list" aria-selected="true">Plan/Subscription List</span>
                            </li>
                            <li class="nav-item cursor-point" role="presentation">
                                <span class="nav-link" id="plan-subsrcription-add-tab" data-bs-toggle="pill" data-bs-target="#plan-subsrcription-add" role="tab" aria-controls="plan-subsrcription-add" aria-selected="false">Add Plan/Subscription</span>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="plan-subscription-list" role="tabpanel" aria-labelledby="plan-subscription-list-tab">
                                <table class="table table-hover table-bordered" id="subscription-plan-tbl">
                                    <thead class="text-nowrap">
                                        <tr>
                                            <th>#</th>
                                            <th>Plan Name</th>
                                            <th>Plan Validity</th>
                                            <th>Plan Amount</th>
                                            <th>Plan Tax</th>
                                            <th>Total Plan Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-nowrap">
            
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="plan-subsrcription-add" role="tabpanel" aria-labelledby="plan-subsrcription-add-tab">
                                <div class="col-md-12 col-12">
                                    <div class="card">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <form class="form form-horizontal" action="{{URL::route('subscription-plans-submit')}}" method="POST">
                                                    @csrf
                                                    <div class="form-body">
                                                        <div class="row">
                                                            <input type="hidden" name="editPlansId" id="editPlansId">
                                                            
                                                            <div class="mb-4 col-md-6 form-group">
                                                                <label for="category_id" class="label-control">Category <span class="text-danger">*</span></label>
                                                                <select name="category_id[]" id="category_id" class="select2-full select2 form-control @error('category_id') is-invalid @enderror" multiple >
                                                                    @if(count($category) > 0)
                                                                    @foreach ($category as $ct)
                                                                    <option value="{{$ct->id}}">{{$ct->name}}</option>
                                                                    @endforeach
                                                                    @endif
                                                                </select>
                                                                @error('category_id')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <span>{{ $message }}</span>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            
                                                            <div class="col-md-6 form-group mb-4">
                                                                <label for="plan_name" class="label-control">Plan Name <span class="text-danger">*</span></label>
                                                                <input type="text" id="plan_name" class="form-control @error('plan_name') is-invalid @enderror" name="plan_name" placeholder="Plan Name" maxlength="50" autocomplete="off">
                                                                @error('plan_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <span>{{ $message }}</span>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                                
                                                            <div class="col-md-6 form-group mb-4">
                                                                <label for="plan_validity" class="label-control">Plan Validity <span class="text-danger">*</span> <small>(in days)</small> </label>
                                                                <input type="text" id="plan_validity" class="form-control @error('plan_validity') is-invalid @enderror" name="plan_validity" placeholder="Plan Validity (in days)" maxlength="15" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                                @error('plan_validity')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <span>{{ $message }}</span>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            
                                                            <div class="col-md-6 form-group mb-4">
                                                                <label for="plan_amount" class="label-control">Amount <span class="text-danger">*</span></label>
                                                                <input type="text" id="plan_amount" class="calsTotal form-control @error('plan_amount') is-invalid @enderror" name="plan_amount" placeholder="Plan Amount" maxlength="15" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="0">
                                                                @error('plan_amount')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <span>{{ $message }}</span>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            
                                                            <div class="col-md-6 form-group mb-4">
                                                                <label for="plan_tax" class="label-control">Tax <span class="text-danger">*</span> <small>(in %)</small> </label>
                                                                <input type="text" id="plan_tax" class="calsTotal form-control @error('plan_tax') is-invalid @enderror" name="plan_tax" placeholder="Tax (in digit)" maxlength="15" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" value="0">
                                                                @error('plan_tax')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <span>{{ $message }}</span>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            
                                                            <div class="col-md-6 form-group mb-4">
                                                                <label for="plan_total" class="label-control">Total <span class="text-danger">*</span></label>
                                                                <input type="text" id="plan_total" class="form-control @error('plan_total') is-invalid @enderror" name="plan_total" placeholder="Plan Total" maxlength="15" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                                @error('plan_total')
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

@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            @if($errors->count() > 0)
                $('#pills-tab').find('#plan-subscription-list-tab').removeClass('active');
                $('#pills-tab').find('#plan-subsrcription-add-tab').addClass('active');

                $('#pills-tabContent').find('.tab-pane').removeClass('show active');
                $('#pills-tabContent').find('#plan-subsrcription-add').addClass('show active');
            @endif

            var table = $('#subscription-plan-tbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: baseUrl + '/subscription-plans/list',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'name', name: 'name' },
                    { data: 'validity', name: 'validity' },
                    { data: 'amount', name: 'amount' },
                    { data: 'tax', name: 'tax' },
                    { data: 'total', name: 'total' },
                    { data: 'status', name: 'status', orderable: false, searchable: false  },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });

        $(document).on('keyup','.calsTotal',function () {
            var totalAmt = 0;
            var planAmt = $('#plan_amount').val();
            var planTax = $('#plan_tax').val();
            if(planAmt > 0) {
                totalCalc = (parseInt(planAmt * planTax) / 100);
                totalAmt = (parseInt(planAmt) + parseInt(totalCalc));
            }
            $('#plan_total').val(totalAmt).attr('readonly','readonly');
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
                    if (re.status === true) {
                        $('#pills-tab').find('#plan-subscription-list-tab').removeClass('active');
                        $('#pills-tab').find('#plan-subsrcription-add-tab').addClass('active');

                        $('#pills-tabContent').find('.tab-pane').removeClass('show active');
                        $('#pills-tabContent').find('#plan-subsrcription-add').addClass('show active');
                        
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
    </script>
@endpush
