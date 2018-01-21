<nav class="navbar navbar-default navbar-static-top">
    <div class="container">

        <div class="navbar-header">

            <!-- Collapse Hamburger -->
            <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#navbar-collapse">
                <span class="sr-only">{{ trans('common.toggle_navigation') }}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding -->
            <a href="{{ url('/') }}" class="navbar-brand">
                {{ config('app.name') }}
            </a>

        </div>

        <div id="navbar-collapse" class="collapse navbar-collapse">
            <!-- left Side Of Navbar -->
            <ul class="nav navbar-nav">

                <!-- -->

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">

                @auth
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                            {{ Auth::user()->username }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ url(trans('routes.logout')) }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ url(trans('routes.logout')) }}" method="POST" style="display:none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li><a href="{{ url(trans('routes.login')) }}">Login</a></li>
                    <li><a href="{{ url(trans('routes.register')) }}">Register</a></li>
                @endauth

            </ul>
        </div>

    </div>
</nav>
