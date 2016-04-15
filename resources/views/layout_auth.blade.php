<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <title>Mobly Manager - Login</title>

    {{ Html::style('styles/bootstrap/bootstrap.min.css') }}
    {{ Html::style('styles/libs/font-awesome.css') }}
    {{ Html::style('styles/compiled/theme_styles.css') }}
    {{ Html::style('styles/extras.css') }}
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400">

    {{ Html::script('scripts/jquery.js') }}
    {{ Html::script('scripts/bootstrap.js') }}
    {{ Html::script('scripts/scripts.js') }}
    {{ Html::script('scripts/validation/jquery.validate.min.js') }}
    {{ Html::script('scripts/validation/localization/messages_pt_BR.js') }}

</head>
<body id="login-page-full" class="theme-blue-gradient">
    <div id="login-full-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div id="login-box" style="max-width: 500px;">
                        <div id="login-box-holder">
                            <div class="row">
                                <div class="col-xs-12">
                                    <header id="login-header">
                                        <div id="login-logo">
                                            {{ Html::image('img/logo.png') }}
                                        </div>
                                    </header>
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>