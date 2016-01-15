@extends('layout')

@section('title', 'Tasks')

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
                    @include('tasks.form-detail')
                </div>
            </div>
        </div>
    </div>
@stop
