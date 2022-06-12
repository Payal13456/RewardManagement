@extends('layouts.app')
@section('title')
    Message To User | {{ config('app.name') }}
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
                <h3>Message To Users</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL::route('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">User</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Message to User</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Send Message</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form class="form form-horizontal" action="{{URL::route('message-to-user-submit')}}" method="POST">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <input type="hidden" name="editCategoryId" id="editCategoryId">
                                <div class="col-md-4 form-group">
                                    <label for="users" class="label-control">Users <span class="text-danger">*</span></label>
                                    <select name="users[]" id="users" class="select2 form-control @error('users') is-invalid @enderror" multiple>
                                        <option value="" disabled >Select User</option>
                                        @if(count($userList) > 0)
                                        @foreach ($userList as $user)
                                        <option value="{{$user->id}}">{{$user->name.' ('.$user->email.')'}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @error('users')
                                        <span class="invalid-feedback" role="alert">
                                            <span>{{ $message }}</span>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="col-md-8 form-group">
                                    <label for="message" class="label-control">Message <span class="text-danger">*</span></label>
                                    <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror"></textarea>
                                    @error('message')
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
    </section>
</div>

@endsection
@push('script')
<script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"></script>
<script>
    $('.select2').select2();

    CKEDITOR.replace( 'message');
</script>
@endpush