@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row align-items-center justify-content-center">
        <div class="col-xl-6 col-lg-7 col-sm-12 col-12 fxt-bg-color">
            <div class="fxt-content">
                <h3>{{ __('Login') }} Maleser</h3>
                <div class="fxt-form">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <label for="email" class="input-label">Email Address</label>
                            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror"" name=" email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="demo@maleser.com" required="required">
                        </div>
                        <div class="form-group">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <label for="password" class="input-label">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" name="password" required autocomplete="current-password" placeholder="********" required="required">
                            <i toggle="#password" class="fa fa-fw fa-eye toggle-password field-icon"></i>
                        </div>
                        <div class="form-group">
                            <div class="fxt-checkbox-area">
                                <div class="checkbox">
                                    <input id="checkbox1" type="checkbox">
                                    <label for="checkbox1">Keep me logged in</label>
                                </div>
                                <a href="forgot-password-13.html" class="switcher-text">Forgot Password</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" id="andrologin" class="fxt-btn-fill">Log in</button>
                        </div>
                    </form>
                </div>
                <div class="fxt-footer">
                    <p>Don't have an account?<a href="register-13.html" class="switcher-text2 inline-text">Register</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection