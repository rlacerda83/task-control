@section('bottom-script')
    <script>
        $(document).ready(function() {
            $.fn.datepicker.defaults.format = "dd/mm/yyyy";
            $('#day').datepicker();

            $('#form').validate({
                ignore: '',
                rules: {
                    day: { required: true},
                    time: { required: true }
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

<div class="main-box-body formulario clearfix">
    {!! Form::model($hourControl, array('route' => array('hours-control.save'), 'id' => 'form')) !!}

    {{ Form::hidden('id', null, array('id' => 'id')) }}
    {{ Form::hidden('redirect', isset($redirect) ? $redirect : null) }}

    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('day', '* Date') }}
            {{ Form::text('day', null, array(
                'class'=>'form-control',
                'maxlength' => 10,
                'placeholder'=>'Date',
                'id' => 'day',
            )) }}

        </div>

        <div class="form-group col-md-6">
            {{ Form::label('time', '* Time Spent') }}
            {{ Form::text('time', null, array(
                'class'=>'form-control',
                'maxlength' => 8,
                'placeholder'=>'Time Spent in hours',
                'id' => 'time'
            )) }}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-12">
            <a href="{{ URL::route('hours-control') }}" class="btn btn-default">Back</a>
            {{ Form::submit('Submit', array('class'=>'btn btn-success pull-right')) }}
        </div>
    </div>
    {!! Form::close() !!}
</div>