@extends('layout')

@section('title', 'Tasks')

@section('head-script')
    <script>
        $(document).ready(function() {
            $('#form').validate({
                ignore: '',
                rules: {
                    email: { required: true, email: true  },
                    url_form: { required: true, url: true }
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

            @if (Session::has('validationErrros'))
                $("#form_cadastro").validate().showErrors({!! Session::get('validationErrros') !!});
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
                    <li class="active"><span>Configuration</span></li>
                </ol>
                <h1>Registration form</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                @include('messages')

                <div class="main-box">
                    <br>
                    <div class="main-box-body formulario clearfix">
                        {!! Form::model($configuration, array('route' => array('configuration.save'), 'id' => 'form')) !!}
                        {{ Form::hidden('id', null, array(
                            'class'=>'form-control',
                            'id' => 'id'
                        )) }}

                        <div class="row">
                            <div class="form-group col-md-12">
                                {{ Form::label('url_form', '*URL google form') }}
                                {{ Form::text('url_form', null, array(
                                    'class'=>'form-control',
                                    'maxlength' => 150,
                                    'placeholder'=>'URL google form',
                                    'id' => 'url_form',
                                )) }}

                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                {{ Form::label('name', 'Name') }}
                                {{ Form::text('name', null, array(
                                    'class'=>'form-control',
                                    'placeholder'=>'Your name',
                                    'id' => 'name'
                                )) }}

                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                {{ Form::label('email', '*E-mail ') }}
                                {{ Form::text('email', null, array(
                                    'class'=>'form-control',
                                    'maxlength' => 150,
                                    'placeholder'=>'Your email account mobly',
                                    'id' => 'email',
                                )) }}

                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                {{ Form::label('password', 'Password') }}
                                {{ Form::input('password', 'password', $password, array(
                                    'class'=>'form-control',
                                    'maxlength' => 20,
                                    'placeholder'=>'Password',
                                    'id' => 'password'
                                )) }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                {{ Form::label('send_email_process', 'Send confirmation email when process') }}
                                {{ Form::select('send_email_process', $choices, null, array(
                                    'class'=>'form-control',
                                    'id' => 'send_email_process'
                                )) }}
                            </div>

                            <div class="form-group col-md-6">
                                {{ Form::label('enable_queue_process', 'Enable process tasks by queue (you need supervisor or cron)') }}
                                {{ Form::select('enable_queue_process', $choices, null, array(
                                    'class'=>'form-control',
                                    'id' => 'enable_queue_process'
                                )) }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <a href="{{ URL::route('dashboard') }}" class="btn btn-default">Back</a>
                                {{ Form::submit('Submit', array('class'=>'btn btn-success pull-right')) }}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
