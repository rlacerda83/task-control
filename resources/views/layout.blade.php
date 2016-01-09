<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <title>Task Control</title>

    <link rel="stylesheet" type="text/css" href="styles/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles/libs/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="styles/libs/nanoscroller.css">
    <link rel="stylesheet" type="text/css" href="styles/libs/jasny-bootstrap.css">
    <link rel="stylesheet" type="text/css" href="styles/compiled/theme_styles.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400">
    <link rel="stylesheet" type="text/css" href="styles/extras.css">

    <!-- CSS Extra -->
    <link rel="stylesheet" type="text/css" href="styles/libs/jquery.isloading.css">
    <link rel="stylesheet" type="text/css" href="styles/libs/datepicker.css">
    <link rel="stylesheet" type="text/css" href="styles/libs/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="styles/libs/bootstrap-tagsinput.css">
    <link rel="stylesheet" type="text/css" href="styles/libs/jquery.bootstrap-touchspin.css">

    <link rel="stylesheet" type="text/css" href="styles/libs/ns-default.css"/>
    <link rel="stylesheet" type="text/css" href="styles/libs/ns-style-growl.css"/>
    <link rel="stylesheet" type="text/css" href="styles/libs/ns-style-bar.css"/>
    <link rel="stylesheet" type="text/css" href="styles/libs/ns-style-attached.css"/>
    <link rel="stylesheet" type="text/css" href="styles/libs/ns-style-other.css"/>
    <link rel="stylesheet" type="text/css" href="styles/libs/ns-style-theme.css"/>


    @yield('head-css')

            <!-- Global Javascript -->
    <script src="scripts/jquery.js"></script>
    <script src="scripts/bootstrap.js"></script>
    <script src="scripts/jasny-bootstrap.js"></script>
    <script src="scripts/jquery.nanoscroller.min.js"></script>
    <script src="scripts/jquery.maskedinput.min.js"></script>
    <script src="scripts/jquery.maskmoney.js"></script>
    <script src="scripts/validation/jquery.validate.min.js"></script>
    <script src="scripts/validation/localization/messages_pt_BR.js"></script>
    <script src="scripts/validation/localization/methods_pt.js"></script>

    <!-- JS Extras -->
    <script src="scripts/extras.js"></script>
    <script src="scripts/bootstrap-datepicker.js"></script>
    <script src="scripts/moment.min.js"></script>
    <script src="scripts/daterangepicker.js"></script>
    <script src="scripts/locales/bootstrap-datepicker.pt-BR.js"></script>
    <script src="scripts/jquery.bootstrap-touchspin.js"></script>

    <!-- Grids -->
    <script src="scripts/table-master/bootstrap-table.js"></script>
    <script src="scripts/table-master/locale/bootstrap-table-pt-BR.min.js"></script>
    <link rel="stylesheet" type="text/css" href="scripts/table-master/bootstrap-table.css">

    <script src="scripts/table-filter/bootstrap-table-filter.js"></script>
    <script src="/scripts/table-filter/ext/bs-table.js"></script>
    <link rel="stylesheet" href="scripts/table-filter/bootstrap-table-filter.css">

    {{--<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/bootstrap-table.min.css">--}}
    {{--<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/bootstrap-table.min.js"></script>--}}

    {{--<script src="http://wenzhixin.net.cn/p/bootstrap-table/src/extensions/filter/bootstrap-table-filter.js"></script>--}}
    {{--<script src="http://wenzhixin.net.cn/p/bootstrap-table/docs/assets/table-filter/bootstrap-table-filter.js"></script>--}}
    {{--<script src="http://wenzhixin.net.cn/p/bootstrap-table/docs/assets/table-filter/ext/bs-table.js"></script>--}}

    <script src="scripts/modalEffects.js"></script>
    <script src="scripts/modernizr.custom.js"></script>
    <script src="scripts/snap.svg-min.js"></script>
    <script src="scripts/classie.js"></script>
    <script src="scripts/jquery.isloading.js"></script>
    <script src="scripts/bootstrap-tagsinput.min.js"></script>



    <script src="scripts/bootbox.min.js"></script>

    <!--[if lt IE 9]>
    <script src="scripts/html5shiv.js"></script>
    <script src="scripts/respond.min.js"></script>
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
    <script src="scripts/scripts.js"></script>
    <script src="scripts/pace.min.js"></script>
</body>
</html>