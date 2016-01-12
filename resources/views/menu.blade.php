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
                            <i class="fa fa-money"></i>
                            <span>Tasks</span>
                        </a>
                    </li>

                    <li @if (strpos(\Route::currentRouteAction(), 'Configuration@index') !== false) class="active" @endif>
                        <a href="{{ URL::route('configuration') }}">
                            <i class="fa fa-home"></i>
                            <span>Configurations</span>
                        </a>
                    </li>

                    <li @if (strpos(\Route::currentRouteAction(), 'Reports@index') !== false) class="active" @endif>
                        <a href="">
                            <i class="fa fa-user"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <div id="nav-col-submenu"></div>
</div>