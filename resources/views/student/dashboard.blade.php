<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Dashboard</title>

		<!-- Site favicon -->
		<link rel="icon" type="image/png" sizes="16x16" href="/vendors/images/logo-ocnhs.png"/>

		<!-- Mobile Specific Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>

		<!-- Google Font -->
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="/vendors/styles/core.css" />
		<link rel="stylesheet" type="text/css" href="/vendors/styles/icon-font.min.css"/>
		<link rel="stylesheet" type="text/css" href="/src/plugins/datatables/css/dataTables.bootstrap4.min.css"/>
		<link rel="stylesheet" type="text/css" href="/src/plugins/datatables/css/responsive.bootstrap4.min.css"/>
		<link rel="stylesheet" type="text/css" href="/vendors/styles/style.css" />

		<script>
        function updateTime() {
            const now = new Date();
            const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const optionsTime = { hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true };
            
            document.getElementById('current-time').textContent = now.toLocaleTimeString([], optionsTime);
            document.getElementById('current-date').textContent = now.toLocaleDateString([], optionsDate);
        }




		
	

        // Update every second
        setInterval(updateTime, 1000);
        updateTime();
        </script>

	</head>
<body>	
	<div class="header">
		@include('layouts.navbar.student.navbar')
	</div>
	<div class="left-side-bar">
		@include('layouts.sidebar.student.sidebar')
	</div>


		<div class="main-container">
			<div class="xs-pd-20-10 pd-ltr-20">
				<div class="card-box pd-20 height-100-p mb-30">
					<div class="row align-items-center">
						<div class="col-md-4">
							<img src="../vendors/images/banner-img.png" alt="">
						</div>
						<div class="col-md-8">
							<h4 class="font-20 weight-500 mb-10 text-capitalize">
									Hello
								<div class="weight-600 font-30 text-blue">{{ Auth::user()->name }}!</div>
							</h4>
							<p class="font-18 max-width-600">
								Welcome to your dashboard! Here you can take assessments/test, view your test
								results.
							</p>
						</div>
					</div>
				</div>

				{{-- Test Results Section --}}
				@if(count($completedTests) > 0)
				<div class="card-box pd-20 height-100-p mb-30">
					<h4 class="h4 text-blue mb-20">Your Test Results</h4>
					<div class="row">
						@foreach($completedTests as $test)
						<div class="col-xl-4 col-lg-6 col-md-6 mb-20">
							<div class="card-box height-100-p p-20 bg-light text-dark" style="background-color:rgb(185, 185, 185) !important;">
								<div class="d-flex align-items-center mb-15">
									<div class="icon mr-15">
										<i class="bi {{ $test['icon'] }}" style="font-size: 40px; color: {{ $test['color'] == 'primary' ? '#007bff' : ($test['color'] == 'success' ? '#28a745' : '#6c757d') }};"></i>
									</div>
									<div>
										<h5 class="mb-5">{{ $test['name'] }}</h5>
										<div class="font-16 weight-600 text-muted">{{ $test['date'] }}</div>
									</div>
								</div>
								<div class="text-center">
									<a href="{{ route($test['route']) }}" class="btn btn-{{ $test['color'] }} btn-sm">
										<i class="bi bi-eye"></i> View Results
									</a>
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
				@endif
			</div>
		</div>

		<script src="/vendors/scripts/core.js"></script>
		<script src="/vendors/scripts/script.min.js"></script>
		<script src="/vendors/scripts/process.js"></script>
		<script src="/vendors/scripts/layout-settings.js"></script>
		<script src="/src/plugins/apexcharts/apexcharts.min.js"></script>
		<script src="/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		<script src="/vendors/scripts/dashboard3.js"></script>


		<!-- SweetAlert2 -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

		@if(session('success'))
		<script>
			Swal.fire({
				icon: 'success',
				title: 'Success!',
				text: '{{ session('success') }}',
				timer: 1000,
				showConfirmButton: false
			});
		</script>
		@endif

	</body>
</html>
