@extends('layout_auth')

@section('content')
    <div id="login-box-inner">
        <form role="form" method="POST" action="{{ url('/password/email') }}">
            {!! csrf_field() !!}

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Email</label>

                <div class="col-md-8">
                    <input type="email" class="form-control" name="email" value="{{ $email or old('email') }}">

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Password</label>

                <div class="col-md-8">
                    <input type="password" class="form-control" name="password">

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Confirm Password</label>
                <div class="col-md-8">
                    <input type="password" class="form-control" name="password_confirmation">

                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-success col-xs-12">Enviar</button>
                </div>
            </div>
        </form>
    </div>
@endsection
