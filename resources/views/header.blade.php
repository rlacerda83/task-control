<header class="navbar" id="header-navbar">
    <div class="container">
        <a href="{{ URL::route('dashboard') }}" id="logo" class="navbar-brand">
            {{ Html::image('img/logo.png') }}
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

            <div class="nav-no-collapse pull-right" id="header-nav">
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown profile-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs">{{ Auth::user()->name }}</span> <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a href="{{ URL::route('system.user.edit', array('id' => Auth::user()->id ))  }}">
                                    <i class="fa fa-user"></i>Alterar Senha
                                </a>
                            </li>
                            <li><a href="{{ URL::route('logout') }}" data-title="Confirmação" data-confirm="Deseja realmente sair do sistema?"><i class="fa fa-power-off"></i>Sair</a></li>
                        </ul>
                    </li>
                    <li class="hidden-xxs">
                        <a href="{{ URL::route('logout') }}" class="btn" data-title="Confirmação" data-confirm="Deseja realmente sair do sistema?">
                            <i class="fa fa-power-off"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>