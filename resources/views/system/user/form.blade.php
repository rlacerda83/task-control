@extends('layout')

@section('title', 'Cadastro de Usuário')

@section('bottom-script')
    <script>
        $(document).ready(function() {
            $('#form').validate({
                ignore: '',
                rules: {
                    name: { required: true },
                    email: { required: true, email: true},
                    password: { required: true },
                    profile_id: { required: true },
                    status: {required: true}
                },
                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error');
                },
                errorElement: 'span',
                errorClass: 'help-block',
                errorPlacement: function(error, element) {
                    if(element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            @if (session('validationErros'))
                $("#form").validate().showErrors({!! session('validationErros') !!});
            @endif
        });
    </script>
@stop

@section('content')

    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{ URL::route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ URL::route('system.user.list') }}">Listagem de Usuários</a></li>
                    <li class="active"><span>{{ $user->id ? 'Edição' : 'Novo' }}</span></li>
                </ol>
                <h1>Cadastro de Usuário</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                @include('messages')

                <div class="main-box">
                    <br>

                    <div class="main-box-body formulario clearfix">
                        {!! Form::model($user, array('route' => array('system.user.save'), 'id' => 'form')) !!}

                            {{ Form::hidden('id', null, array('id' => 'id')) }}

                            <div class="row">
                                <div class="form-group col-md-12">
                                    {{ Form::label('name', '* Nome') }}
                                    {{ Form::text('name', null, array(
                                        'class'=>'form-control',
                                        'placeholder'=>'Nome do usuário',
                                        'maxlength' => 100,
                                        'id' => 'name'
                                    )) }}
                                </div>

                                <div class="form-group col-md-12">
                                    {{ Form::label('profile_id', '* Perfil de Acesso') }}
                                    {{ Form::select('profile_id', $profiles
                                    , null, array(
                                        'class'=>'form-control',
                                        'id' => 'profile_id'
                                    )) }}
                                </div>

                                <div class="form-group col-md-12">
                                    {{ Form::label('email', '* E-mail') }}
                                    {{ Form::text('email', null, array(
                                        'class'=>'form-control',
                                        'maxlength' => 150,
                                        'placeholder'=> 'E-mail do usuário',
                                        'id' => 'email',
                                    )) }}
                                </div>

                                <div class="form-group col-md-12">
                                    {{ Form::label('password', 'Password') }}
                                    {{ Form::input('password', 'password', null, array(
                                        'class'=>'form-control',
                                        'maxlength' => 20,
                                        'placeholder'=>'Password',
                                        'id' => 'password'
                                    )) }}
                                </div>

                                <div class="form-group col-md-12">
                                    {{ Form::label('status', '* Status') }}
                                    {{ Form::select('status', \App\Helpers\Labels::$status
                                    , null, array(
                                        'class'=>'form-control',
                                        'id' => 'status'
                                    )) }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <a href="{{ URL::route('system.user.list') }}" class="btn btn-default">Back</a>
                                    {{ Form::submit('Salvar', array('class'=>'btn btn-success pull-right')) }}
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
