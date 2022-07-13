@extends('layouts.app')
@section('title')
    Report / Feedback | {{ config('app.name') }}
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
                    <h3>Report / Feedback</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL::route('/') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Report / Feedback</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="feedback-list-tbl">
                            <thead class="text-nowrap">
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Report Date</th>
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
@endsection
@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#feedback-list-tbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: baseUrl + '/reports-and-feedback/list',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'username', name: 'username', sClass:'text-wrap', sWidth:'15%' },
                    { data: 'title', name: 'title', sClass:'text-wrap', sWidth:'25%'},
                    { data: 'description', name: 'description', sClass:'text-wrap', sWidth:'40%' },
                    { data: 'reportDate', name: 'reportDate', sWidth:'15%' },
                ]
            });
        });
    </script>
@endpush
