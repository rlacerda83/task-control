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
@stop