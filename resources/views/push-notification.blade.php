@extends('layouts.app')
@section('title') Push Notification | {{ config('app.name') }} @endsection

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
                <h3>Push Notification</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{URL::route('/')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Push Notification</li>
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
                                <span class="nav-link active" id="push-notification-tab" data-bs-toggle="pill" data-bs-target="#push-notification" role="tab" aria-controls="push-notification" aria-selected="true">Push Notification List</span>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="push-notification" role="tabpanel" aria-labelledby="push-notification-tab">
                                <table class="table table-hover table-bordered" id="push-notification-tbl">
                                    <thead class="text-nowrap">
                                        <tr>
                                            <th>#</th>
                                            <th>Type</th>
                                            <th>Message</th>
                                            <th>Users</th>
                                            {{-- <th>Status</th> --}}
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
            var table = $('#push-notification-tbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: baseUrl + '/push-notification/list',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'type', name: 'type', sWidth:'15%' },
                    { data: 'msg', name: 'msg', sClass:'text-wrap', sWidth:'50%'},
                    { data: 'users', name: 'users', sWidth:'25%', sClass:'text-wrap', orderable: false, searchable: false },
                    // { data: 'status', name: 'status', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush