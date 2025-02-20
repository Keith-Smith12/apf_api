<!doctype html>
<html lang="pt-br">
@include('layouts._includes.admin.head')
<body class="vertical  light  ">
    <div class="wrapper">
        <nav class="topnav navbar navbar-light">
        <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
          <i class="fe fe-menu navbar-toggler-icon"></i>
        </button>
        <form class="form-inline mr-auto searchform text-muted">
          <input class="form-control mr-sm-2 bg-transparent border-0 pl-4 text-muted" type="search" placeholder="Type something..." aria-label="Search">
        </form>
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link text-muted my-2" href="#" id="modeSwitcher" data-mode="light">
              <i class="fe fe-sun fe-16"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-muted my-2" href="./#" data-toggle="modal" data-target=".modal-shortcut">
              <span class="fe fe-grid fe-16"></span>
            </a>
          </li>
          <li class="nav-item nav-notif">
            <a class="nav-link text-muted my-2" href="./#" data-toggle="modal" data-target=".modal-notif">
              <span class="fe fe-bell fe-16"></span>
              <span class="dot dot-md bg-success"></span>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="avatar avatar-sm mt-2">
                <img src="./assets/avatars/face-1.jpg" alt="..." class="avatar-img rounded-circle">
              </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="#">Profile</a>
              <a class="dropdown-item" href="#">Settings</a>
              <a class="dropdown-item" href="/">Loja</a>
              {{-- <a class="dropdown-item" href="#">Activities</a> --}}
            </div>
          </li>
        </ul>
        </nav>
        @include('layouts._includes.admin.menu')
        <main role="main" class="main-content">
            @yield('conteudo')
        </main>
        @include('layouts.alert.index')
    </div> <!-- .wrapper -->
    <script src="{{asset('painel/js/jquery.min.js')}}"></script>
    <script src="{{asset('painel/js/popper.min.js')}}"></script>
    <script src="{{asset('painel/js/moment.min.js')}}"></script>
    <script src="{{asset('painel/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('painel/js/simplebar.min.js')}}"></script>
    <script src="{{asset('painel/js/daterangepicker.js')}}"></script>
    <script src="{{asset('painel/js/jquery.stickOnScroll.js')}}"></script>
    <script src="{{asset('painel/js/tinycolor-min.js')}}"></script>
    <script src="{{asset('painel/js/config.js')}}"></script>
    <script src="{{asset('painel/js/d3.min.js')}}"></script>
    <script src="{{asset('painel/js/topojson.min.js')}}"></script>
    <script src="{{asset('painel/js/datamaps.all.min.js')}}"></script>
    <script src="{{asset('painel/js/datamaps-zoomto.js')}}"></script>
    <script src="{{asset('painel/js/datamaps.custom.js')}}"></script>
    <script src="{{asset('painel/js/Chart.min.js')}}"></script>
    <script>
    /* defind global options */
    Chart.defaults.global.defaultFontFamily = base.defaultFontFamily;
    Chart.defaults.global.defaultFontColor = colors.mutedColor;
    </script>
    <script src="{{asset('painel/js/gauge.min.js')}}"></script>
    <script src="{{asset('painel/js/jquery.sparkline.min.js')}}"></script>
    <script src="{{asset('painel/js/apexcharts.min.js')}}"></script>
    <script src="{{asset('painel/js/apexcharts.custom.js')}}"></script>
    <script src="{{asset('painel/js/apps.js')}}"></script>
    <!--Select2-->
    <script src="{{asset('painel/js/select2.min.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag()
      {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      gtag('config', 'UA-56159088-1');
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @if (session('feedback'))
    {{-- @dump(session('feedback')); --}}

    @if (isset(session('feedback')['type']))
        <script>
            Swal.fire(
                '{{ session('feedback')['sms'] }}',
                '',
                '{{ session('feedback')['type'] }}'
            )
        </script>
    @endif
@endif
  </body>
