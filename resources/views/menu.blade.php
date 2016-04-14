<div id="nav-col">
    <section id="col-left" class="col-left-nano">
        <div id="col-left-inner" class="col-left-nano-content">
            <div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">
                <ul class="nav nav-pills nav-stacked">
                    <li class="nav-header nav-header-first hidden-sm hidden-xs">
                        Menu
                    </li>

                    <li @if (strpos(\Route::currentRouteAction(), 'DashboardController@index') !== false) class="active" @endif>
                        <a href="{{ URL::route('dashboard') }}">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li @if (strpos(\Route::currentRouteAction(), 'TaskController') !== false) class="active" @endif>
                        <a href="{{ URL::route('tasks') }}">
                            <i class="fa fa-tasks"></i>
                            <span>Tasks</span>
                        </a>
                    </li>

                    <li @if (strpos(\Route::currentRouteAction(), 'Configuration@index') !== false) class="active" @endif>
                        <a href="{{ URL::route('configuration') }}">
                            <i class="fa fa-gears"></i>
                            <span>Configurations</span>
                        </a>
                    </li>

                    @if (\App\Helpers\Permission::check('system'))
                        <br>
                        <li class="nav-header nav-header-first hidden-sm hidden-xs">
                            <p>Sistema</p>
                        </li>

                        @if (\App\Helpers\Permission::check('system.profile.list'))
                            <li @if (\App\Helpers\Menu::isActive('system.profile')) class="active" @endif>
                                <a href="{{ URL::route('system.profile.list') }}" class="" title="Perfis">
                                    <i class="fa fa-database"></i>
                                    <span>Perfis</span>
                                </a>
                            </li>
                        @endif

                        @if (\App\Helpers\Permission::check('system.user.list'))
                            <li @if (\App\Helpers\Menu::isActive('system.user')) class="active" @endif>
                                <a href="{{ URL::route('system.user.list') }}" class="" title="Usuários">
                                    <i class="fa fa-sitemap"></i>
                                    <span>Usuários</span>
                                </a>
                            </li>
                        @endif
                    @endif
                </ul>
            </div>
        </div>
    </section>
    <div id="nav-col-submenu"></div>
</div>