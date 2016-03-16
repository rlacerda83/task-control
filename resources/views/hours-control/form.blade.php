@extends('layout')

@section('title', 'Hours control')

@section('content')

    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{ URL::route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ URL::route('hours-control') }}">Hours Control</a></li>
                    <li class="active"><span>{{ $hourControl->id ? 'Edit' : 'New' }}</span></li>
                </ol>
                <h1>Registration form</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                @include('messages')

                <div class="main-box">
                    <br>
                    @include('hours-control.form-detail')
                </div>
            </div>
        </div>
    </div>
@stop
