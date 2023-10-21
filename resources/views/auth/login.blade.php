@extends('layouts.front.app')

@section('content')

    <div class="container">
        
        <div class="bg-white">
            
            <div class="row justify-content-center py-5">

                <div class="col-md-6 mb-5" id="login-container">

                    <div class="p-5 border rounded bg-lighter">

                        <h1 class="text-center mb-4 h3">Login to your account</h1>

                        <form action="{{ route('login') }}" method="post" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                                    placeholder="Email" autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif

                            </div>
                            <div class="form-group">

                                <label for="password">Password</label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    value="{{ old('password') }}"
                                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                                    placeholder="******">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif

                            </div>
                            <div class="row justify-content-end px-3">
                                <button autofocus class="btn btn-tomato ml-2" type="submit">Login</button>
                            </div>
                        </form>
                        <div class="d-flex justify-content-center align-items-center flex-row flex-wrap mt-4 mb-2">
                            <a class="btn btn-light mx-1" href="{{route('password.request')}}">I forgot my password</a><br>
                            <button class="btn btn-light mx-1 jq-create-account">Create new account</button>
                        </div>

                    </div>
                </div>

                <div class="col-md-7 mb-5" id="register-container">

                    <div class="p-5 border rounded bg-lighter">

                        <h1 class="text-center mb-4 h3">Create your account</h1>

                        <form action="{{ route('register') }}" method="post" role="form" class="form-horizontal">
                            
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="register_name">Name</label>
                                <input type="text" id="register_name" name="name" value="{{ old('name') }}" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Name" autofocus>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="new_email">Email</label>
                                <input type="email" id="new_email" name="email" value="{{ old('email') }}" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="example@gmail.com">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone number</label>
                                <input 
                                    type="phone" 
                                    id="phone" 
                                    name="phone" 
                                    value="{{ old('phone') }}" 
                                    class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" 
                                    placeholder="Ex: 3105551212">

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif

                            </div>
                            <div class="form-group">
                                <label for="new_password">Password</label>
                                <input id="new_password" type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" placeholder="******">
                                 @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="new_password-confirm" class="control-label">Confirm Password</label>
                                <input id="new_password-confirm" type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password_confirmation" placeholder="******">
                                @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="row justify-content-end px-3">
                                <button class="btn btn-tomato" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>

@endsection


@section('css')

    <style>
        @if(request()->has('register'))
            #login-container{
                display: none;
            }
        @else
            #register-container{
                display: none;
            }
        @endif
    </style>

@endsection

@push('scripts')
    
    <script>
        $(function(){
            $('body').on('click', '.jq-create-account', function(event) {
                event.preventDefault();
                $('#register-container').fadeOut(500, function() {
                    $('#register-container').addClass('was-validated')
                });
                $(this).hide()
                window.location.search = 'register';
            });
        })
    </script>

@endpush
