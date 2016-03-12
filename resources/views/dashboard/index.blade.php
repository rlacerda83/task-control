@extends('layout')

@section('title', 'Dashboard')

@section('head-script')

    <script>
        var s1 = {!! $hoursGraph !!};
        var s2 = {!! $monthGraph !!};
        var s3 = {!! $percentageGraph !!};
        var ticks = {!! $labelsGraph !!};

        var sp1 = {!! $daysPendingHourGraph !!};
        var sp2 = {!! $daysPendingHourPendingGraph !!};
        var ticksP = {!! $daysPendingLabelsGraph !!};

        var monthHours = {!! $monthHours !!};
        var monthWorkedHours = {!! $monthWorkedHours !!};
    </script>

    {{ Html::script('scripts/jquery.jqplot.min.js') }}
    {{ Html::script('scripts/jqplot/jqplot.barRenderer.min.js') }}
    {{ Html::script('scripts/jqplot/jqplot.highlighter.min.js') }}
    {{ Html::script('scripts/jqplot/jqplot.categoryAxisRenderer.min.js') }}
    {{ Html::script('scripts/jqplot/jqplot.enhancedLegendRenderer.min.js') }}
    {{ Html::script('scripts/jqplot/jqplot.pointLabels.min.js') }}
    {{ Html::script('scripts/jqplot/jqplot.meterGaugeRenderer.js') }}
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
                                @if(strlen($hoursGraph) < 3)
                                    <p>There is no tasks to show<p>
                                @endif
                                <div id="graph-bar-goal" style="height: 240px; padding: 0px; position: relative;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="main-box">
                    <header class="main-box-header clearfix">
                        <h2 class="pull-left">Days with appointment pending hours</h2>
                    </header>

                    <div class="main-box-body clearfix">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="graph-bar-pending" style="height: 240px; padding: 0px; position: relative;"></div>

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