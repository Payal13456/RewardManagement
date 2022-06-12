@extends('layouts.app')
@section('title') Plan | {{ config('app.name') }} @endsection

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
            <div class="col-md-4 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Plans</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" action="{{URL::route('category-submit')}}" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <input type="hidden" name="editPlansId" id="editPlansId">
                                        
                                        <label for="plan_name" class="col-md-4 label-control">Plan Name <span class="text-danger">*</span></label>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="plan_name" class="form-control @error('plan_name') is-invalid @enderror" name="plan_name" placeholder="Plan Name" maxlength="50" autocomplete="off">
                                            @error('plan_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <span>{{ $message }}</span>
                                                </span>
                                            @enderror
                                        </div>
                                            
                                        <label for="plan_validity" class="col-md-4 label-control">Plan Validity <span class="text-danger">*</span></label>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="plan_validity" class="form-control @error('plan_validity') is-invalid @enderror" name="plan_validity" placeholder="Plan Validity" maxlength="15" autocomplete="off">
                                            @error('plan_validity')
                                                <span class="invalid-feedback" role="alert">
                                                    <span>{{ $message }}</span>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <label for="plan_amount" class="col-md-4 label-control">Amount <span class="text-danger">*</span></label>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="plan_amount" class="form-control @error('plan_amount') is-invalid @enderror" name="plan_amount" placeholder="Plan Amount" maxlength="15" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                            @error('plan_amount')
                                                <span class="invalid-feedback" role="alert">
                                                    <span>{{ $message }}</span>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <label for="plan_tax" class="col-md-4 label-control">Tax (in digit) <span class="text-danger">*</span></label>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="plan_tax" class="form-control @error('plan_tax') is-invalid @enderror" name="plan_tax" placeholder="Tax (in digit)" maxlength="15" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                            @error('plan_tax')
                                                <span class="invalid-feedback" role="alert">
                                                    <span>{{ $message }}</span>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <label for="plan_total" class="col-md-4 label-control">Total <span class="text-danger">*</span></label>
                                        <div class="col-md-8 form-group">
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

            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Plans List</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="user-list-tbl">
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
            </div>
        </div>
    </section>    
</div>
@endsection
@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#user-list-tbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: baseUrl + '/plan-list/all',
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
    </script>
@endpush