@extends('layout_auth')

<!-- Main Content -->
@section('content')
<div id="login-box-inner">
    <form  role="form" method="POST" action="{{ url('/password/email') }}">
        {!! csrf_field() !!}

        @if(session('status'))
            <div class="alert alert-success">
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
            {{ Form::text('email', null, array(
                'class'=>'form-control',
                'maxlength' => 150,
                'placeholder'=>'E-mail',
                'id' => 'email',
            )) }}
        </div>

        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-success col-xs-12">Enviar E-mail</button>
            </div>
        </div>
    </form>
</div>
@endsection
