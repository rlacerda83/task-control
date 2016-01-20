@section('bottom-script')
    <script>
        $(document).ready(function() {
            $.fn.datepicker.defaults.format = "dd/mm/yyyy";
            $('#date').datepicker();

            $('#form').validate({
                ignore: '',
                rules: {
                    task: { required: true },
                    date: { required: true},
                    time: { required: true },
                    description: { required: true },
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

<div class="main-box-body formulario clearfix">
    {!! Form::model($task, array('route' => array('tasks.save'), 'id' => 'form')) !!}

    {{ Form::hidden('id', null, array('id' => 'id')) }}
    {{ Form::hidden('redirect', isset($redirect) ? $redirect : null) }}

    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('task', '* Task (MEBLO)') }}
            {{ Form::text('task', null, array(
                'class'=>'form-control',
                'placeholder'=>'Task. Ex: MEBLO-4875',
                'maxlength' => 10,
                'id' => 'task'
            )) }}

        </div>

        <div class="form-group col-md-6">
            {{ Form::label('date', '* Task Date') }}
            {{ Form::text('date', null, array(
                'class'=>'form-control',
                'maxlength' => 10,
                'placeholder'=>'Task Date',
                'id' => 'date',
            )) }}

        </div>

        <div class="form-group col-md-6">
            {{ Form::label('time', '* Time Spent') }}
            {{ Form::text('time', null, array(
                'class'=>'form-control',
                'maxlength' => 4,
                'placeholder'=>'Time Spent in hours',
                'id' => 'time'
            )) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('status', '* Status') }}
            {{ Form::select('status', array(
                'pending' => 'Pending',
                'processed' => 'Processed',
                'error' => 'Error'
            ), null, array(
                'class'=>'form-control',
                'id' => 'status'
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