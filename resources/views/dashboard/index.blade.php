@extends('layout')

@section('title', 'Dashboard')

@section('head-script')

    <script>
        var s1 = {!! $hoursGraph !!};
        var s2 = {!! $tasksGraph !!};
        var ticks = {!! $labelsGraph !!};
    </script>

    {{ Html::script('scripts/jquery.jqplot.min.js') }}
    {{ Html::script('scripts/jqplot/jqplot.barRenderer.min.js') }}
    {{ Html::script('scripts/jqplot/jqplot.highlighter.min.js') }}
    {{ Html::script('scripts/jqplot/jqplot.categoryAxisRenderer.min.js') }}
    {{ Html::script('scripts/jqplot/jqplot.enhancedLegendRenderer.min.js') }}
    {{ Html::script('scripts/jqplot/jqplot.pointLabels.min.js') }}
    {{ Html::script('scripts/pages/dashboard.js') }}
@stop

@section('head-css')
    {{ Html::style('styles/libs/jquery.jqplot.min.css') }}
@stop

@section('content')
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <div id="content-header" class="clearfix">
                    <div class="pull-left">
                        <h1>Dashboard</h1>
                        <br><br>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="main-box">
                    <header class="main-box-header clearfix">
                        <h2 class="pull-left">Hours appointment of the last year</h2>
                    </header>

                    <div class="main-box-body clearfix">
                        <div class="row">
                            <div class="col-md-9">
                                @if(strlen($hoursGraph) < 3) 
                                    <p>There is no tasks to show<p>
                                @endif
                                <div id="graph-bar" style="height: 240px; padding: 0px; position: relative;"></div>
                            
                            </div>

                            <div class="col-md-3">
                                <ul class="graph-stats">
                                    <li>
                                        <div class="clearfix">
                                            <div class="title pull-left">
                                                Total tasks in last year
                                            </div>
                                            <div class="value pull-right">
                                                {{ $totalsYear->total }}
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div style="width: 100%;" role="progressbar" class="progress-bar"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="clearfix">
                                            <div class="title pull-left">
                                                Tasks processed
                                            </div>
                                            <div class="value pull-right">
                                                {{ $totalsYear->totalProcessed }}
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div style="width: {{ $totalsYear->total > 0 ? round(($totalsYear->totalProcessed/$totalsYear->total) * 100) : 0 }}%;" role="progressbar" class="progress-bar progress-bar-success">
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="clearfix">
                                            <div class="title pull-left">
                                                Tasks pending
                                            </div>
                                            <div class="value pull-right">
                                                {{ $totalsYear->totalPending }}
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div style="width: {{ $totalsYear->total > 0 ? round(($totalsYear->totalPending/$totalsYear->total) * 100) : 0 }}%;" role="progressbar" class="progress-bar progress-bar-warning">
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="clearfix">
                                            <div class="title pull-left">
                                                Tasks with errors
                                            </div>
                                            <div class="value pull-right">
                                                {{ $totalsYear->totalError }}
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div style="width: {{ $totalsYear->total > 0 ? round(($totalsYear->totalError/$totalsYear->total) * 100) : 0 }}%;" role="progressbar" class="progress-bar progress-bar-danger">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="main-box clearfix">
                    <header class="main-box-header clearfix">
                        <h2 class="pull-left">New Task</h2>
                    </header>

                    @include('messages')
                    @include('tasks.form-detail')
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="main-box clearfix" style="height: 538px;">
                    <header class="main-box-header clearfix">
                        <h2 class="pull-left">Last Tasks</h2>
                    </header>

                    <div class="main-box-body clearfix">
                        <ul class="widget-todo">
                            @forelse($lastTasks as $lastTask)
                                <li class="clearfix">
                                    <div class="name">
                                        <b>[{{ $lastTask->task }}]</b> - {{ str_limit($lastTask->description, 30) }}
                                    </div>
                                    <div class="actions">
                                        <span class="label label-{{ $statusLabels[$lastTask->status] }}">
                                            {{ $lastTask->status  }}
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <p>There is no registered tasks</p>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop