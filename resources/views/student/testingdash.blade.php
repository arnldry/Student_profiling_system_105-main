<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Testing</title>

		 <link rel="icon" type="image/png" sizes="16x16" href="/vendors/images/logo-ocnhs.png"/>
        

        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1" />

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
			<div class="title pb-20">
				<h2 class="h3 mb-0">
				Hello, {{ Auth::user()->name }}!
				</h2>
			</div>

			<div class="row">
				<!-- RIASEC Test Box -->
				@php
					$currentSchoolYear = \App\Models\SchoolYear::where('is_active', 1)->first();
					if (!$currentSchoolYear) {
						$currentSchoolYear = \App\Models\SchoolYear::where('archived', 0)
							->orderByRaw("CAST(SUBSTRING_INDEX(school_year, '-', 1) AS UNSIGNED) DESC")
							->first();
					}
					$hasAdditionalInfo = $currentSchoolYear ? \App\Models\AdditionalInformation::where('learner_id', Auth::id())
						->where('school_year_id', $currentSchoolYear->id)
						->exists() : false;
					$hasRiasecResult = \App\Models\RiasecResult::where('user_id', Auth::id())->exists();
					$canTakeRiasec = !$hasAdditionalInfo || !$hasRiasecResult;
				@endphp

				@if(\Cache::get('test_riasec_enabled', true))
				<div class="col-xl-3 col-lg-4 col-md-6 mb-30">
					@if($canTakeRiasec)
					<a href="{{ route('testing.riasec')}}" class="card-box d-block height-100-p text-center shadow">
					@else
					<div class="card-box d-block height-100-p text-center shadow bg-light">
					@endif
					<div class="icon mb-3">
						<i class="bi bi-journal-text" style="font-size: 40px; color: {{ $canTakeRiasec ? '#007bff' : '#6c757d' }};"></i>
					</div>
					<h5 class="h5 mb-0 {{ $canTakeRiasec ? '' : 'text-muted' }}">RIASEC TEST</h5>
					<p class="text-muted">
						@if(!$canTakeRiasec)
							Already taken this school year
						@elseif($hasRiasecResult)
							Take test again (next school year)
						@else
							Click to start the Riasec test
						@endif
					</p>
					@if($hasRiasecResult)
						<span class="badge {{ $canTakeRiasec ? 'badge-info' : 'badge-secondary' }}">
							{{ $canTakeRiasec ? 'Available' : 'Completed' }}
						</span>
					@endif
					@if($canTakeRiasec)
					</a>
					@else
					</div>
					@endif
				</div>
				@else
				<div class="col-xl-3 col-lg-4 col-md-6 mb-30">
					<div class="card-box d-block height-100-p text-center shadow bg-light">
					<div class="icon mb-3">
						<i class="bi bi-journal-text" style="font-size: 40px; color: #6c757d;"></i>
					</div>
					<h5 class="h5 mb-0 text-muted">RIASEC TEST</h5>
					<p class="text-muted">This test is currently disabled</p>
					<span class="badge badge-secondary">Disabled</span>
					</div>
				</div>
				@endif

				<!-- Life Values Test Box -->
				@php
					$hasLifeValuesResult = \App\Models\LifeValuesResult::where('user_id', Auth::id())->exists();
					$canTakeLifeValues = !$hasAdditionalInfo || !$hasLifeValuesResult;
				@endphp

				@if(\Cache::get('test_life_values_enabled', true))
				<div class="col-xl-3 col-lg-4 col-md-6 mb-30">
					@if($canTakeLifeValues)
					<a href="{{ route('testing.life-values-inventory')}}" class="card-box d-block height-100-p text-center shadow">
					@else
					<div class="card-box d-block height-100-p text-center shadow bg-light">
					@endif
					<div class="icon mb-3">
						<i class="bi bi-clipboard-check" style="font-size: 40px; color: {{ $canTakeLifeValues ? '#28a745' : '#6c757d' }};"></i>
					</div>
					<h5 class="h5 mb-0 {{ $canTakeLifeValues ? '' : 'text-muted' }}">LIFE VALUES INVENTORY</h5>
					<p class="text-muted">
						@if(!$canTakeLifeValues)
							Already taken this school year
						@elseif($hasLifeValuesResult)
							Take test again (next school year)
						@else
							Click to start the Life values inventory test
						@endif
					</p>
					@if($hasLifeValuesResult)
						<span class="badge {{ $canTakeLifeValues ? 'badge-info' : 'badge-secondary' }}">
							{{ $canTakeLifeValues ? 'Available' : 'Completed' }}
						</span>
					@endif
					@if($canTakeLifeValues)
					</a>
					@else
					</div>
					@endif
				</div>
				@else
				<div class="col-xl-3 col-lg-4 col-md-6 mb-30">
					<div class="card-box d-block height-100-p text-center shadow bg-light">
					<div class="icon mb-3">
						<i class="bi bi-clipboard-check" style="font-size: 40px; color: #6c757d;"></i>
					</div>
					<h5 class="h5 mb-0 text-muted">LIFE VALUES INVENTORY</h5>
					<p class="text-muted">This test is currently disabled</p>
					<span class="badge badge-secondary">Disabled</span>
					</div>
				</div>
				@endif
			</div>
			</div>

		<footer>
			<!-- <div class="footer-wrap pd-20 mb-20 card-box">
				@include('layouts.footer.footer')
			</div> -->
						
		</footer>

		
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
	</body>
</html>
