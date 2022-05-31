@extends('layouts.auth-app')

@section('content')
<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <!-- <div class="auth-logo">
                <a href="index.html"><img src="assets/images/logo/logo.png" alt="Logo"></a>
            </div> -->
            <h1 class="auth-title">Log in.</h1>
            <p class="auth-subtitle mb-5">Log in with your data that you entered during registration.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" name="email" class="form-control form-control-xl @error('email') is-invalid @enderror" placeholder="Email Address" value="{{ old('email') }}" autocomplete="off">
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <span>{{ $message }}</span>
                        </span>
                    @enderror
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" name="password" class="form-control form-control-xl @error('password') is-invalid @enderror" placeholder="Password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <span>{{ $message }}</span>
                        </span>
                    @enderror
                </div>
                <div class="form-check form-check-lg d-flex align-items-end">
                    <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label text-gray-600" for="flexCheckDefault">
                        Keep me logged in
                    </label>
                </div>
                <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">{{__('Log in')}}</button>
            </form>
            <div class="text-center mt-5 text-lg fs-4">              
                @if (Route::has('password.request'))
                    <p><a class="font-bold" href="{{ route('password.request') }}">Forgot password?</a>.</p>
                @endif
                
            </div>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right">

        </div>
    </div>
</div>
@endsection
