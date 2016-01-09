@extends('layout')

@section('title', 'Dashboard')

@section('head-script')
    <script src="scripts/jquery.hideseek.js"></script>
    <script src="scripts/jquery.slimscroll.min.js"></script>
    <script src="scripts/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="scripts/jquery-jvectormap-world-merc-en.js"></script>
    <script src="scripts/gdp-data.js"></script>
    <script src="scripts/flot/jquery.flot.min.js"></script>
    <script src="scripts/flot/jquery.flot.resize.min.js"></script>
    <script src="scripts/flot/jquery.flot.time.min.js"></script>
    <script src="scripts/flot/jquery.flot.threshold.js"></script>
    <script src="scripts/flot/jquery.flot.axislabels.js"></script>
    <script src="scripts/jquery.sparkline.min.js"></script>
    <script src="scripts/pages/dashboard.js"></script>
@stop

@section('content')
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <div id="content-header" class="clearfix">
                    <div class="pull-left">
                        <ol class="breadcrumb">
                            <li><a href="">Home</a></li>
                            <li class="active"><span>Dashboard</span></li>
                        </ol>

                        <h1>Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="main-box infographic-box colored green-bg">
                    <i class="fa fa-money"></i>
                    <span class="headline">Anunciantes</span>
                    <span class="value">10</span>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="main-box infographic-box colored purple-bg">
                    <i class="fa fa-home"></i>
                    <span class="headline">Imóveis</span>
                    <span class="value">100</span>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="main-box infographic-box colored red-bg">
                    <i class="fa fa-user"></i>
                    <span class="headline">Cadastros</span>
                    <span class="value">54</span>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="main-box infographic-box colored emerald-bg">
                    <i class="fa fa-envelope"></i>
                    <span class="headline">Contatos</span>
                    <span class="value">454</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="main-box">
                    <header class="main-box-header clearfix">
                        <h2 class="pull-left">Sales &amp; Earnings</h2>
                    </header>

                    <div class="main-box-body clearfix">
                        <div class="row">
                            <div class="col-md-9">
                                <div id="graph-bar" style="height: 240px; padding: 0px; position: relative;"></div>
                            </div>
                            <div class="col-md-3">
                                <ul class="graph-stats">
                                    <li>
                                        <div class="clearfix">
                                            <div class="title pull-left">
                                                Earnings
                                            </div>
                                            <div class="value pull-right" title="10% down" data-toggle="tooltip">
                                                &dollar;94.382 <i class="fa fa-level-down red"></i>
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div style="width: 69%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="69" role="progressbar" class="progress-bar">
                                                <span class="sr-only">69% Complete</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="clearfix">
                                            <div class="title pull-left">
                                                Orders
                                            </div>
                                            <div class="value pull-right" title="24% up" data-toggle="tooltip">
                                                3.930 <i class="fa fa-level-up green"></i>
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div style="width: 42%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="42" role="progressbar" class="progress-bar progress-bar-danger">
                                                <span class="sr-only">42% Complete</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="clearfix">
                                            <div class="title pull-left">
                                                New Clients
                                            </div>
                                            <div class="value pull-right" title="8% up" data-toggle="tooltip">
                                                894 <i class="fa fa-level-up green"></i>
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div style="width: 78%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="78" role="progressbar" class="progress-bar progress-bar-success">
                                                <span class="sr-only">78% Complete</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="clearfix">
                                            <div class="title pull-left">
                                                Visitors
                                            </div>
                                            <div class="value pull-right" title="17% down" data-toggle="tooltip">
                                                824.418 <i class="fa fa-level-down red"></i>
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div style="width: 94%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="94" role="progressbar" class="progress-bar progress-bar-warning">
                                                <span class="sr-only">94% Complete</span>
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

            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <div class="main-box feed">
                    <header class="main-box-header clearfix">
                        <div class="filter-block">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Buscar Anunciante..." data-list=".widget-users" autocomplete="off">
                                <i class="fa fa-search search-icon"></i>
                            </div>
                        </div>
                    </header>

                    <div class="main-box-body clearfix">
                        <ul class="widget-users">
                            @for ($i = 0; $i < 10; $i++)
                                <li class="clearfix">
                                    <div class="img">
                                        <img src="img/samples/emma.png">
                                    </div>
                                    <div class="details">
                                        <div class="name">
                                            Nome
                                        </div>
                                        <div class="time">
                                            <a href="mailto: ">teste#gmaoil.com</a>
                                        </div>
                                        <div class="type">
                                            <span class="label label-default">Telefone 45 45454-4544</span>
                                        </div>
                                    </div>
                                </li>
                            @endfor
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12">
                <div class="main-box clearfix">
                    <header class="main-box-header clearfix">
                        <h2 class="pull-left">Tarefas</h2>

                        <div class="icon-box pull-right">
                            <a href="#" class="btn pull-left">
                                <i class="fa fa-refresh"></i>
                            </a>
                        </div>
                    </header>

                    <div class="main-box-body clearfix">

                        <ul class="widget-todo">
                            <li class="clearfix">
                                <div class="name">
                                    <div class="checkbox-nice">
                                        <input type="checkbox" id="todo-1">
                                        <label for="todo-1">
                                            New products introduction <span class="label label-danger">High Priority</span>
                                        </label>
                                    </div>
                                </div>
                            </li>
                            <li class="clearfix">
                                <div class="name">
                                    <div class="checkbox-nice">
                                        <input type="checkbox" id="todo-2">
                                        <label for="todo-2">
                                            Checking the stock <span class="label label-success">Low Priority</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="actions">
                                    <a href="#" class="table-link">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a href="#" class="table-link danger">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </div>
                            </li>
                            <li class="clearfix">
                                <div class="name">
                                    <div class="checkbox-nice">
                                        <input type="checkbox" id="todo-3" checked="checked">
                                        <label for="todo-3">
                                            Buying coffee <span class="label label-warning">Medium Priority</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="actions">
                                    <a href="#" class="table-link">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a href="#" class="table-link danger">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </div>
                            </li>
                            <li class="clearfix">
                                <div class="name">
                                    <div class="checkbox-nice">
                                        <input type="checkbox" id="todo-4">
                                        <label for="todo-4">
                                            New marketing campaign <span class="label label-success">Low Priority</span>
                                        </label>
                                    </div>
                                </div>
                            </li>
                            <li class="clearfix">
                                <div class="name">
                                    <div class="checkbox-nice">
                                        <input type="checkbox" id="todo-5">
                                        <label for="todo-5">
                                            Calling Mom <span class="label label-warning">Medium Priority</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="actions">
                                    <a href="#" class="table-link badge">
                                        <i class="fa fa-cog"></i>
                                    </a>
                                </div>
                            </li>
                            <li class="clearfix">
                                <div class="name">
                                    <div class="checkbox-nice">
                                        <input type="checkbox" id="todo-6">
                                        <label for="todo-6">
                                            Ryan's birthday <span class="label label-danger">High Priority</span>
                                        </label>
                                    </div>
                                </div>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <div class="main-box weather-box">
                    <header class="main-box-header clearfix">
                        <h2 class="pull-left">Tempo agora</h2>
                    </header>

                    <div class="main-box-body clearfix">
                        <div class="current">
                            <div class="clearfix center-block" style="width: 220px;">
                                <div class="temp-wrapper">
                                    <div class="temperature">
                                        26><span class="sign">º</span>
                                    </div>
                                    <div class="desc">
                                        <i class="fa fa-map-marker"></i> São Paulo
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <div class="main-box">
                    <header class="main-box-header clearfix">
                        <h2>Mercado</h2>
                        <small class="gray">Atualizado em </small>
                    </header>

                    <div class="main-box-body clearfix">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="map-stats">
                                    <div class="table-responsive">
                                        <table class="table table-condensed table-hover">
                                            <thead>
                                                <tr>
                                                    <th><span>Moeda</span></th>
                                                    <th class="text-center"><span>Cotação</span></th>
                                                    <th class="text-center"><span>Variação</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Dólar</td>
                                                    <td class="text-center">R$ 45</td>
                                                    <td class="text-center status">
                                                        <i class="fa fa-level-dollar"></i>
                                                        45,45
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop