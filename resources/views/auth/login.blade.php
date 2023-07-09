@extends('layouts.app')

@section('content')
    <div class="container-sm mb-3">
        <ul class="nav nav-underline">
            <li class="nav-item h3">
                <a class="nav-link active" href="{{ route('login') }}">Login</a>
            </li>
            <li class="nav-item h3">
                <a class="nav-link" href="{{ route('register') }}">Register</a>
            </li>
        </ul>
    </div>
    <div class="container p-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-6">
                <form method="GET" action="{{ route('github') }}" class="text-center mb-1">
                    @csrf
                    <button type="submit" class="btn btn-dark fs-4 col-6 mb-3"><span><i class="bi bi-github"></i>
                        </span>Github</button>
                </form>
                <form method="GET" action="{{ route('google') }}" class="text-center">
                    @csrf
                    <button type="submit" class="btn btn-danger fs-4 col-6 mb-3"><span><i class="bi bi-google"></i>
                        </span>Google</button>
                </form>
                <p class="lead mt-3 text-center">Back to <a href="/">HomePage</a></p>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="mt-3 text-center">
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
