<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!-- Font Awesome -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
	
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
	
	<!-- DataTables -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
	
	<!-- Custom CSS -->
	<link rel="stylesheet" href="{{ url('css/style.css') }}">

	<title>@yield('title')</title>
</head>
<body>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="{{ url('dashboard') }}" class="brand">
			<img src="{{ url('img/logo_astra.png') }}" width="150" alt="logo">
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
			<li class="{{ request()->is('security_schedule*') ? 'active' : '' }}">
				<a href="{{ url('security_schedule') }}">
					<i class="fa-solid fa-table"></i>
					<span class="text">Jadwal Patrol</span>
				</a>
			</li>

			<!-- Master Data Dropdown -->
		
			<li class="has-submenu">
				<a class="d-flex align-items-center" data-bs-toggle="collapse" href="#masterData">
					<i class="fa-solid fa-database"></i><span class="text"> Master Data</span>
				</a>
				<ul class="collapse list-unstyled ms-4 mt-1 {{ request()->is('region*') || request()->is('sales_office*') || request()->is('checkpoint*') || request()->is('kriteria_checkpoint*') ? 'show' : '' }}" id="masterData">
					<li class="{{ request()->is('region*') ? 'active' : '' }}">
						<a href="{{ url('region') }}"  style="color: grey">
							<i class="fa-solid fa-flag"></i>
							<span class="text">Region</span>
						</a>
					</li>
					<li class="{{ request()->is('sales_office*') ? 'active' : '' }}">
						<a href="{{ url('sales_office') }}" style="color: grey">
							<i class="fa-solid fa-building"></i>
							<span class="text" >Sales Office</span>
						</a>
					</li>
					<li class="{{ request()->is('checkpoint*') ? 'active' : '' }}">
						<a href="{{ url('checkpoint') }}" style="color: grey">
							<i class="fa-solid fa-location-dot"></i>
							<span class="text">Checkpoint</span>
						</a>
					</li>
					{{-- <li class="{{ request()->is('kriteria_checkpoint*') ? 'active' : '' }}">
						<a href="{{ url('kriteria_checkpoint') }}" style="color: grey">
							<i class="fa-solid fa-list-check"></i>
							<span class="text">Kriteria Checkpoint</span>
						</a>
					</li> --}}
				</ul>
			</li>


		<!-- Logout -->
		<ul class="side-menu mt-5">
			<li>
				<a href="{{ route('logout') }}" class="logout">
					<i class="fa-solid fa-arrow-right-from-bracket"></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- END SIDEBAR -->

	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav class="shadow-sm">
			<i class="fa-solid fa-bars"></i>
			<a href="#" class="profile">
				<img src="{{ url('img/user.png') }}" alt="Profile">
			</a>
            <span class="ms-2">Admin</span>
		</nav>

		<!-- MAIN -->
		@yield('content')
	</section>
	<!-- END CONTENT -->

	<!-- Scripts -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- Custom Scripts -->
	<script src="{{ url('js/script.js') }}"></script>
	<script src="{{ url('js/datatable.js') }}"></script>
	<script src="{{ url('js/sweetalert.js') }}"></script>
	<script src="{{ url('js/print.js') }}"></script>
	@stack('scripts')
</body>
</html>
