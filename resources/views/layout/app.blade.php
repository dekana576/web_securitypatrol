<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Font awesome -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
	<!-- DataTables Bootstrap 5 -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
	<!-- My CSS -->
	<link rel="stylesheet" href="{{ url('css/style.css') }}">
	
	<title>@yield('title')</title>
</head>
<body>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<span class="text"></span>
		</a>
		<ul class="side-menu top">
			<li class="{{ request()->is('dashboard') ? 'active' : '' }}">
				<a href="{{ url('dashboard') }}">
					<i class="fa-solid fa-gauge-high"></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li class="{{ request()->is('user*') ? 'active' : '' }}">
				<a href="{{ url('user') }}">
                    <i class="fa-solid fa-user"></i>
					<span class="text">User</span>
				</a>
			</li>
			<li class="{{ request()->is('data_patrol*') ? 'active' : '' }}">
				<a href="{{ url('data_patrol') }}">
					<i class="fa-solid fa-file"></i>
					<span class="text">Data Patrol</span>
				</a>
			</li>
			<li class="{{ request()->is('jadwal_patrol*') ? 'active' : '' }}">
				<a href="{{ url('jadwal_patrol') }}">
					<i class="fa-solid fa-table"></i>
					<span class="text">Jadwal Patrol</span>
				</a>
			</li>
			<li class="{{ request()->is('region*') ? 'active' : '' }}">
				<a href="{{ url('region') }}">
					<i class="fa-solid fa-flag"></i>
					<span class="text">Region</span>
				</a>
			</li>
            <li class="{{ request()->is('sales_office*') ? 'active' : '' }}">
				<a href="{{ url('sales_office') }}">
					<i class="fa-solid fa-building"></i>
					<span class="text">Sales Office</span>
				</a>
			</li>
            <li class="{{ request()->is('checkpoint*') ? 'active' : '' }}">
				<a href="{{ url('checkpoint') }}">
					<i class="fa-solid fa-location-dot"></i>
					<span class="text">Checkpoint</span>
				</a>
			</li>
            <li class="{{ request()->is('kriteria_checkpoint*') ? 'active' : '' }}">
				<a href="{{ url('kriteria_checkpoint') }}">
					<i class="fa-solid fa-list-check"></i>
					<span class="text">Kriteria Checkpoint</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<li>
				<a href="{{route('logout')}}" class="logout">
					<i class="fa-solid fa-arrow-right-from-bracket"></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->

	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class="fa-solid fa-bars"></i>
			<a href="#" class="profile">
				<img src="{{ url('img/user.png') }}">
			</a>
            <span>Admin</span>
		</nav>
		<!-- NAVBAR -->

        {{--  MAIN CONTENT --}}
        @yield('content')
        {{--  MAIN CONTENT --}}
	</section>
	<!-- CONTENT -->

    <!-- Scripts -->
	<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
	<script src="{{ url('js/script.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="{{ url('js/datatable.js') }}"></script>
	<script src="{{ url('js/sweetalert.js') }}"></script>
	<script src="{{ url('js/print.js') }}"></script>
	@stack('scripts')
</body>
</html>
