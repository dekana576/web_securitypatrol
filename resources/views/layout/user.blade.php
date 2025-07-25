<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" type="image/x-icon" href="/img/logo_AM_Patrol_no_Text_ICO.ico">

  <!-- Manifest -->
	<link rel="manifest" href="/manifest.json">
	<meta name="theme-color" content="#0d6efd">

	<!-- Icons (for Android) -->
	<link rel="icon" sizes="192x192" href="/icons/icon-192x192.png">
	<link rel="apple-touch-icon" href="/icons/icon-512x512.png">
  
  <title>@yield('title')</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Data tabel -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">
  <!-- My CSS -->
  <link rel="stylesheet" href="{{url("css/user.css")}}">
  <!-- Toastr CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
 
</head>
<body>

  <!-- Sidebar -->
<section class="sidebar" id="sidebar">
    <ul class="sidebar-menu top">
        <li class="active">
            <a href="{{ route('user.home') }}">
                <i class="fa-solid fa-house"></i>
                <span class="text">Home</span>
            </a>
        </li>
        <li>
            <a href="{{ route('schedule.index') }}">
                <i class="fa-solid fa-table"></i>
                <span class="text">Jadwal</span>
            </a>
        </li>
    </ul>

    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('security.change_password') }}">
                <i class="fa-solid fa-key"></i>
                <span class="text">Ubah Password</span>
            </a>
        </li>
        <li>
            <a href="{{ route('logout') }}" class="logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
</section>

   
  <!-- Content -->
  <section id="content">
       <!-- Navbar -->
        <nav >
            <i class="fa-solid fa-bars" id="toggleSidebar"></i>
            <a href="#" class="profile"><img src="{{url("img/user.png")}}"/></a>
        </nav>

        <!-- Main Content -->
        @yield('content')
        <!-- Main Content -->
  </section>
 
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
	<script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	<script src="{{url("js/datatable.js")}}"></script>
  <script src="{{url("js/user.js")}}"></script>   

  @stack('scripts')

  

  <script>
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>

	
</body>
</html>


 
