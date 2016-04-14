@extends('layout')

@section('title', 'Cadastro de Perfil')

@section('head-script')
    {{ Html::script('scripts/bootstrap-editable.min.js') }}
    {{ Html::script('scripts/moment.min.js') }}
@stop

@section('head-css')
    {{ Html::style('styles/libs/bootstrap-editable.css') }}
@stop

@section('bottom-script')
    <script>
        $(document).ready(function() {

            @if ( $profile->id )
                // make all items having class 'edit' editable
                $('.editable').editable({
                    mode: 'inline',
                    showbuttons: false,
                    source: [
                        {value: 1, text: 'Sim'},
                        {value: 0, text: 'Não'}
                    ],
                    display: function(value, sourceData) {
                        var colors = {0: "red", 1: "blue"}, elem = $.grep(sourceData, function(o){return o.value == value;});

                        if(elem.length) {
                            $(this).text(elem[0].text).css("color", colors[value]);
                        } else {
                            $(this).empty();
                        }
                    },
                    type: 'text',
                    url: '{{ URL::route('system.profile.save.permission', ['id' => $profile->id]) }}',
                    ajaxOptions: {
                        dataType: 'json'
                    },
                    success: function(response, newValue) {
                        if(!response) {
                            return "Erro desconhecido!";
                        }

                        if(response.success === false) {
                            return response.message;
                        }
                    }
                });
            @endif;

            $('#form').validate({
                ignore: '',
                rules: {
                    name: { required: true }
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
                    <li><a href="{{ URL::route('system.user.list') }}">Listagem de Perfis</a></li>
                    <li class="active"><span>{{ $profile->id ? 'Edição' : 'Novo' }}</span></li>
                </ol>
                <h1>Cadastro de Perfil</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                @include('messages')

                <div class="main-box">
                    <br>

                    <div class="main-box-body formulario clearfix">
                        {!! Form::model($profile, array('route' => array('system.profile.save'), 'id' => 'form')) !!}

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


                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <a href="{{ URL::route('system.profile.list') }}" class="btn btn-default">Back</a>
                                    {{ Form::submit('Salvar', array('class'=>'btn btn-success pull-right')) }}
                                </div>
                            </div>
                        {!! Form::close() !!}

                        @if ( $profile->id )
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" style="clear: both">
                                    <thead>
                                    <tr>
                                        <th><span>Módulo</span></th>
                                        <th><span>Ação</span></th>
                                        <th><span>Permissão</span></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($actions as $action)
                                            <tr>
                                                <td class="col-md-3">{{ $action['module'] }}</td>
                                                <td class="col-md-4">{{ $action['action'] }}</td>
                                                <td class="col-md-5">
                                                    <a href="#" data-name="acao_{{ $action['id']  }}" data-type="select" data-pk="{{ $action['id'] }}" data-value="{{ $action['permission'] ? '1': '0' }}" class="editable editable-click">{{ $action['permission'] ? 'Sim': 'Não' }}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
