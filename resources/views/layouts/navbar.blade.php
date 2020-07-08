
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{route('home')}}" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">@yield('page','Dashboard')</a>
        </li>
    </ul>
    <a href="" class="ml-auto" class="btn btn-outline-primary"  onclick="event.preventDefault(), document.getElementById('logoutFrm').submit()"> Cerrar sesión</a>
    <form action="{{route('logout')}}" method="POST" id="logoutFrm" >
        @csrf
    </form>
</nav>
<!-- /.navbar -->
