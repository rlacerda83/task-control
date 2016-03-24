@extends('layout')

@section('title', 'Hours')

@section('bottom-script')
    <script>
        $(document).ready(function() {
            $.fn.datepicker.defaults.format = "dd/mm/yyyy";
            $('#startDate').datepicker();
            $('#endDate').datepicker();

            $('#form').validate({
                ignore: '',
                rules: {
                    startDate: { required: true},
                    endDate: { required: true}
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
                    <li><a href="{{ URL::route('hours-control') }}">Hours Control</a></li>
                    <li class="active"><span>Report</span></li>
                </ol>
                <div class="clearfix">
                    <h1>Hours List</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                @include('messages')

                <div class="main-box clearfix">
                    <br>
                    <div class="main-box-body formulario clearfix">
                        <fieldset>
                            <legend>Period</legend>
                            {!! Form::open(array(
                                'route' => array('hours-control.report'),
                                'id' => 'form',
                                'method' => 'get'
                            )) !!}

                            <div class="row">
                                <div class="form-group col-md-5">
                                    {{ Form::label('startDate', '* Start Date') }}
                                    {{ Form::text('startDate', $startDate, array(
                                        'class'=>'form-control',
                                        'maxlength' => 10,
                                        'placeholder'=>'Start Date',
                                        'id' => 'startDate',
                                    )) }}

                                </div>

                                <div class="form-group col-md-5">
                                    {{ Form::label('endDate', '* End Date') }}
                                    {{ Form::text('endDate', $endDate, array(
                                        'class'=>'form-control',
                                        'maxlength' => 10,
                                        'placeholder'=>'End Date',
                                        'id' => 'endDate',
                                    )) }}

                                </div>

                                <div class="form-group col-md-2">
                                    {{ Form::submit('Submit', array(
                                        'class'=>'btn btn-success',
                                        'style' => 'margin-top:23px'
                                    )) }}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </fieldset>

                    </div>

                    @if(count($hours))
                        <div class="main-box-body clearfix">
                            <legend>Report</legend>

                            <h4><b>Working hours of the period:</b> {{ $hours['workingHours'] }} </h4>
                            <h4><b>Total worked hours:</b> {{ $hours['totalWorkedHours'] }}</h4>

                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>First Period</th>
                                    <th>Second Period</th>
                                    <th>Balance</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($hours['days'] as $day => $arrHours)
                                    <tr class="{{ $hours['days'][$day]['class'] }}">
                                        <td>{{ $day }}</td>

                                        <td>
                                            {{ isset($arrHours['data'][0]['time']) ? $arrHours['data'][0]['time'] : '-' }} |
                                            {{ isset($arrHours['data'][1]['time']) ? $arrHours['data'][1]['time'] : '-' }}
                                        </td>

                                        <td>
                                            {{ isset($arrHours['data'][2]['time']) ? $arrHours['data'][2]['time'] : '-' }} |
                                            {{ isset($arrHours['data'][3]['time']) ? $arrHours['data'][3]['time'] : '-' }}
                                        </td>

                                        <td>{{ isset($hours['days'][$day]['balance']) ? $hours['days'][$day]['balance'] : '-' }}</td>
                                    </tr>
                                @empty
                                    <p>There is no register</p>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop