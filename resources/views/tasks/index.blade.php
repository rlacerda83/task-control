@extends('layout')

@section('title', 'Tasks')

@section('head-script')
    {{ Html::script('scripts/pages/tasks/index.js') }}
@stop

@section('content')
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{ URL::route('dashboard') }}">Dashboard</a></li>
                    <li class="active"><span>Tasks</span></li>
                </ol>
                <div class="clearfix">
                    <h1>Tasks List</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @include('messages')

                <div class="main-box clearfix">
                    <div class="main-box-body listagem clearfix">

                        <div id="toolbar" class="btn-group" style="margin-top: 10px">
                            <a href="{{ URL::route('tasks.new') }}" class="btn btn-success">
                                <i class="glyphicon glyphicon-plus"></i> New Task
                            </a>

                            <a href="javascript:startProcess();" id="btn-process-tasks" class="btn btn-success">
                                <i class="glyphicon glyphicon-plus"></i> Process synchronous
                            </a>
                            <div id="filter-bar"></div>
                        </div>

                        <div id="no-more-tables">
                            <table id="table-tasks"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal-process" class="modal fade" role="dialog" style="display: none">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Process Tasks</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" >
                        <p id="process-msg"></p>
                    </div>
                    {{ Form::password('password', array(
                        'class'=>'form-control',
                        'maxlength' => 20,
                        'placeholder'=>'Password',
                        'id' => 'process-password'
                    )) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="startProcess();" data-dismiss="modal">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@stop