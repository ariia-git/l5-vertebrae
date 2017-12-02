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

                <!-- -->

            </ul>
        </div>

    </div>
</nav>
