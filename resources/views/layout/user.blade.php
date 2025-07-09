<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title')</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Data tabel -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">
  <!-- My CSS -->
  <link rel="stylesheet" href="{{url("css/user.css")}}">
 
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
          <a href="#">
            <i class="fa-solid fa-table"></i>
            <span class="text">Jadwal</span>
          </a>
        </li>
    </ul>
     <ul class="sidebar-menu">
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
	<script src="{{url("js/datatable.js")}}"></script>
  <script src="{{url("js/user.js")}}"></script>   
  @stack('scripts')

	
</body>
</html>


 
