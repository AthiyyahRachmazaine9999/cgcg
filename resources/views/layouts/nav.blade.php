<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-dark fixed-top">
    <div class="navbar-brand">
        <a href="index.html" class="d-inline-block">
            <img src="{{ asset('vendor/global_assets/images/logo_light.png') }}" alt="">
        </a>
    </div>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>

        <span class="navbar-text ml-md-3">
            <span class="badge badge-mark border-orange-300 mr-2"></span>
            Hello,{{ Auth::user()->name }}
        </span>

        <ul class="navbar-nav ml-md-auto">
            <li class="nav-item">
                <router-link to="/product/category" route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="navbar-nav-link">
                    <i class="icon-switch2"></i>
                    <span class="d-md-none ml-2">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->
