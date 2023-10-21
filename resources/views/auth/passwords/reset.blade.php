@extends('layouts.front.app')

@section('content')

<div class="container">
    <div class="bg-white">
        <div class="row justify-content-center py-5">
            <div class="col-md-6 mb-5" id="login-container">
                <div class="p-5 border rounded bg-lighter">

                    <h1 class="text-center mb-4 h3">Reset your password</h1>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ route('password.request') }}">

                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' is-invalid' : '' }}">
                            <label for="email">Email Address</label>

                            <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' is-invalid' : '' }}">
                            <label for="password">Password</label>

                            <input id="password" type="password" class="form-control" name="password" required>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}">
                            <label for="password-confirm">Confirm Password</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                            @if ($errors->has('password_confirmation'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-tomato">
                                Reset Password
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>


                


                    
        
@endsection
