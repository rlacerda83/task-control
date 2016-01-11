@extends('layout')

@section('title', 'Tasks')

@section('head-script')
    <script>
        $(document).ready(function() {
            $.fn.datepicker.defaults.format = "dd/mm/yyyy";
            $('#date').datepicker({
                startDate: '-3d'
            });

            // Validação
            $('#form_cadastro').validate({
                ignore: '',
                rules: {
                    task: { required: true },
                    date: { required: true},
                    time: { required: true },
                    description: { required: true }
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
                $("#form_cadastro").validate().showErrors({{ Session::get('validationErrros') }});
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
                    <li><a href="{{ URL::route('tasks') }}">Tasks</a></li>
                    <li class="active"><span>{{ $task->id ? 'Edit' : 'New' }}</span></li>
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
                        {!! Form::model($task, array('route' => array('tasks.save'))) !!}
                            {{ Form::hidden('id', null, array(
                                'class'=>'form-control',
                                'id' => 'id'
                            )) }}

                            <div class="row">
                                <div class="form-group col-md-4">
                                    {{ Form::label('task', '* Task (MEBLO)') }}
                                    {{ Form::text('task', null, array(
                                        'class'=>'form-control',
                                        'placeholder'=>'Task. Ex: MEBLO-4875',
                                        'maxlength' => 10,
                                        'id' => 'task'
                                    )) }}

                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('date', '* Task Date') }}
                                    {{ Form::text('date', null, array(
                                        'class'=>'form-control',
                                        'maxlength' => 10,
                                        'placeholder'=>'Task Date',
                                        'id' => 'date',
                                    )) }}

                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('time', '* Time Spent') }}
                                    {{ Form::text('time', null, array(
                                        'class'=>'form-control',
                                        'maxlength' => 2,
                                        'placeholder'=>'Time Spent in hours',
                                        'id' => 'time'
                                    )) }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    {{ Form::label('description', '* Description') }}
                                    {{ Form::textarea('description', null, array(
                                        'class'=>'form-control',
                                        'maxlength' => 150,
                                        'placeholder'=>'Short description of the task',
                                        'id' => 'description'
                                    )) }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <a href="{{ URL::route('tasks') }}" class="btn btn-default">Back</a>
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
