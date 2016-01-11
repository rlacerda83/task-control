<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <title>Task Control</title>

    {{ Html::style('styles/bootstrap/bootstrap.min.css') }}
    {{ Html::style('styles/libs/font-awesome.css') }}
    {{ Html::style('styles/libs/nanoscroller.css') }}
    {{ Html::style('styles/libs/jasny-bootstrap.css') }}
    {{ Html::style('styles/compiled/theme_styles.css') }}
    {{ Html::style('styles/extras.css') }}

    {{ Html::style('styles/libs/jquery.isloading.css') }}
    {{ Html::style('styles/libs/datepicker.css') }}
    {{ Html::style('styles/libs/daterangepicker.css') }}
    {{ Html::style('styles/libs/bootstrap-tagsinput.css') }}
    {{ Html::style('styles/libs/jquery.bootstrap-touchspin.css') }}

    {{ Html::style('styles/libs/ns-default.css') }}
    {{ Html::style('styles/libs/ns-style-growl.css') }}
    {{ Html::style('styles/libs/ns-style-bar.css') }}
    {{ Html::style('styles/libs/ns-style-attached.css') }}
    {{ Html::style('styles/libs/ns-style-other.css') }}
    {{ Html::style('styles/libs/ns-style-theme.css') }}

    {{--<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400">--}}

    @yield('head-css')

            <!-- Global Javascript -->
    {{ Html::script('scripts/jquery.js') }}
    {{ Html::script('scripts/bootstrap.js') }}
    {{ Html::script('scripts/jquery.nanoscroller.min.js') }}
    {{ Html::script('scripts/jquery.maskedinput.min.js') }}
    {{ Html::script('scripts/jquery.maskmoney.js') }}
    {{ Html::script('scripts/validation/jquery.validate.min.js') }}

    <!-- JS Extras -->
    {{ Html::script('scripts/extras.js') }}
    {{ Html::script('scripts/bootstrap-datepicker.js') }}
    {{ Html::script('scripts/moment.min.js') }}
    {{ Html::script('scripts/locales/bootstrap-datepicker.pt-BR.js') }}
    {{ Html::script('scripts/jquery.bootstrap-touchspin.js') }}

    <!-- Grids -->
    {{ Html::script('scripts/table-master/bootstrap-table.js') }}
    {{ Html::style('scripts/table-master/bootstrap-table.css') }}

    {{ Html::script('scripts/table-filter/bootstrap-table-filter.js') }}
    {{ Html::script('scripts/table-filter/ext/bs-table.js') }}
    {{ Html::style('scripts/table-filter/bootstrap-table-filter.css') }}

    {{ Html::script('scripts/modalEffects.js') }}
    {{ Html::script('scripts/modernizr.custom.js') }}
    {{ Html::script('scripts/snap.svg-min.js') }}
    {{ Html::script('scripts/classie.js') }}
    {{ Html::script('scripts/jquery.isloading.js') }}
    {{ Html::script('scripts/bootstrap-tagsinput.min.js') }}

    {{ Html::script('scripts/bootbox.min.js') }}

    <!--[if lt IE 9]>
    {{ Html::script('scripts/html5shiv.js') }}
    {{ Html::script('scripts/respond.min.js') }}
    <![endif]-->

    @yield('head-script')
</head>

<body class="theme-turquoise fixed-header">

    <div id="theme-wrapper">
        @include('header')
        <div id="page-wrapper" class="container">
            <div class="row">
                @include('menu')
                <div id="content-wrapper">
                    <div class="row">
                        @yield('content')
                    </div>
                    @include('footer')
                </div>
            </div>
        </div>
    </div>

    <!-- theme scripts -->
    {{ Html::script('scripts/scripts.js') }}
    {{ Html::script('scripts/pace.min.js') }}
</body>
</html>