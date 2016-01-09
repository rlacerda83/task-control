<header class="navbar" id="header-navbar">
    <div class="container">
        <a href="{{ URL::route('dashboard') }}" id="logo" class="navbar-brand">
            <img src="img/logo.png" alt="" class="normal-logo logo-white">
            <img src="img/logo-black.png" alt="" class="normal-logo logo-black">
            <img src="img/logo-small.png" alt="" class="small-logo hidden-xs hidden-sm hidden">
        </a>
        <div class="clearfix">
            <button class="navbar-toggle" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="fa fa-bars"></span>
            </button>
            <div class="nav-no-collapse navbar-left pull-left hidden-sm hidden-xs">
                <ul class="nav navbar-nav pull-left">
                    <li>
                        <a class="btn" id="make-small-nav">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>