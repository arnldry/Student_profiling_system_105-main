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
					$latestRiasecResult = \App\Models\RiasecResult::where('user_id', Auth::id())->latest()->first();
					$canTakeRiasec = true;
					if ($latestRiasecResult) {
						$oneYearAgo = now()->subYear();
						$canTakeRiasec = $latestRiasecResult->created_at < $oneYearAgo || $latestRiasecResult->admin_reopened;
					}
				@endphp

				@if(\Cache::get('test_riasec_enabled', true))
				<div class="col-xl-3 col-lg-4 col-md-6 mb-30">
					@if($canTakeRiasec)
					<a href="{{ route('testing.riasec')}}" class="card-box d-block height-100-p text-center shadow riasec-test-link" data-test-type="riasec">
					@else
					<div class="card-box d-block height-100-p text-center shadow bg-light">
					@endif
					<div class="icon mb-3">
						<i class="bi bi-journal-text" style="font-size: 40px; color: {{ $canTakeRiasec ? '#007bff' : '#6c757d' }};"></i>
					</div>
					<h5 class="h5 mb-0 {{ $canTakeRiasec ? '' : 'text-muted' }}">RIASEC TEST</h5>
					<p class="text-muted">
						@if(!$canTakeRiasec)
							@if($latestRiasecResult)
								You can take this test again next year. Or ask guidance for permission to take it sooner.
							@else
								Test completed
							@endif
						@elseif($latestRiasecResult)
							Click to retake the Riasec test
						@else
							Click to start the Riasec test
						@endif
					</p>
					@if($latestRiasecResult)
						<span class="badge {{ $canTakeRiasec ? 'badge-info' : 'badge-secondary' }}">
							{{ $canTakeRiasec ? 'Retake Available' : 'Completed' }}
						</span>
					@else
						<span class="badge badge-success">Available</span>
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
					$latestLifeValuesResult = \App\Models\LifeValuesResult::where('user_id', Auth::id())->latest()->first();
					$canTakeLifeValues = true;
					if ($latestLifeValuesResult) {
						$sixMonthsAgo = now()->subMonths(6);
						$canTakeLifeValues = $latestLifeValuesResult->created_at < $sixMonthsAgo || $latestLifeValuesResult->admin_reopened;
					}
				@endphp

				@if(\Cache::get('test_life_values_enabled', true))
				<div class="col-xl-3 col-lg-4 col-md-6 mb-30">
					@if($canTakeLifeValues)
					<a href="{{ route('testing.life-values-inventory')}}" class="card-box d-block height-100-p text-center shadow life-values-test-link" data-test-type="life-values">
					@else
					<div class="card-box d-block height-100-p text-center shadow bg-light">
					@endif
					<div class="icon mb-3">
						<i class="bi bi-clipboard-check" style="font-size: 40px; color: {{ $canTakeLifeValues ? '#28a745' : '#6c757d' }};"></i>
					</div>
					<h5 class="h5 mb-0 {{ $canTakeLifeValues ? '' : 'text-muted' }}">LIFE VALUES INVENTORY</h5>
					<p class="text-muted">
						@if(!$canTakeLifeValues)
							@if($latestLifeValuesResult)
								You can take this test again next year. Or ask guidance for permission to take it sooner.
							@else
								Test completed
							@endif
						@elseif($latestLifeValuesResult)
							Click to retake the Life Values test
						@else
							Click to start the Life Values test
						@endif
					</p>
					@if($latestLifeValuesResult)
						<span class="badge {{ $canTakeLifeValues ? 'badge-info' : 'badge-secondary' }}">
							{{ $canTakeLifeValues ? 'Retake Available' : 'Completed' }}
						</span>
					@else
						<span class="badge badge-success">Available</span>
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

		<!-- SweetAlert2 -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

		<script>
			document.addEventListener('DOMContentLoaded', function() {
				// Handle RIASEC test link click
				document.querySelectorAll('.riasec-test-link').forEach(link => {
					link.addEventListener('click', function(e) {
						e.preventDefault();
						const href = this.getAttribute('href');

						Swal.fire({
							title: '📊 RIASEC Career Test',
							html: `
								<div style="text-align: left; line-height: 1.6; font-size: 16px; color: #333; background: #fafbfc; padding: 20px; border-radius: 6px; border-left: 4px solid #4a90e2;">
									<p style="margin-bottom: 15px;"><strong>What is the RIASEC Career Test?</strong></p>
									<p style="margin-bottom: 15px;">The RIASEC test helps you discover your career interests and preferences. It identifies which types of careers might suit you best based on your personality and interests.</p>

									<p style="margin-bottom: 10px;"><strong>RIASEC stands for:</strong></p>
									<ul style="margin-bottom: 15px; padding-left: 20px;">
										<li><strong>R - Realistic:</strong> Practical, hands-on work with tools and machines</li>
										<li><strong>I - Investigative:</strong> Research, analysis, and problem-solving</li>
										<li><strong>A - Artistic:</strong> Creative expression and original thinking</li>
										<li><strong>S - Social:</strong> Helping and working with others</li>
										<li><strong>E - Enterprising:</strong> Leadership and business activities</li>
										<li><strong>C - Conventional:</strong> Organized, detail-oriented work</li>
									</ul>

									<p style="margin-bottom: 15px;"><strong>What will you do?</strong></p>
									<p style="margin-bottom: 15px;">You will answer questions about activities you enjoy and subjects you like. The test will show your top career interest areas from the six RIASEC types.</p>

									<div style="background: #f1f8ff; padding: 12px; border-radius: 4px; margin-top: 15px; border-left: 3px solid #4a90e2;">
										<p style="margin: 0; font-size: 14px; color: #2c5282;"><strong>💡 Tip:</strong> <em>Answer honestly for the most accurate results!</em></p>
									</div>
								</div>
							`,
							icon: false,
							showCancelButton: true,
							confirmButtonColor: '#007bff',
							cancelButtonColor: '#6c757d',
							confirmButtonText: 'Proceed to Test',
							cancelButtonText: 'Cancel',
							width: '550px',
							padding: '10px'
						}).then((result) => {
							if (result.isConfirmed) {
								window.location.href = href;
							}
						});
					});
				});

				// Handle Life Values test link click
				document.querySelectorAll('.life-values-test-link').forEach(link => {
					link.addEventListener('click', function(e) {
						e.preventDefault();
						const href = this.getAttribute('href');

						Swal.fire({
							title: '💭 Life Values Inventory',
							html: `
								<div style="text-align: left; line-height: 1.6; font-size: 16px; color: #333; background: #fafbfc; padding: 20px; border-radius: 6px; border-left: 4px solid #48bb78;">
									<p style="margin-bottom: 15px;"><strong>What is the Life Values Inventory?</strong></p>
									<p style="margin-bottom: 15px;">This test helps you understand your personal values and what matters most to you in life. It explores what you believe is important for making decisions and living a meaningful life.</p>

									<p style="margin-bottom: 15px;"><strong>What will you do?</strong></p>
									<p style="margin-bottom: 15px;">You will rank different values and situations to show what you prioritize. The results will help you understand your core values and life priorities.</p>

									<div style="background: #f0fff4; padding: 12px; border-radius: 4px; margin-top: 15px; border-left: 3px solid #48bb78;">
										<p style="margin: 0; font-size: 14px; color: #22543d;"><strong>💡 Tip:</strong> <em>Think about what truly matters to you for the best results!</em></p>
									</div>
								</div>
							`,
							icon: false,
							showCancelButton: true,
							confirmButtonColor: '#28a745',
							cancelButtonColor: '#6c757d',
							confirmButtonText: 'Proceed to Test',
							cancelButtonText: 'Cancel',
							width: '550px',
							padding: '10px'
						}).then((result) => {
							if (result.isConfirmed) {
								window.location.href = href;
							}
						});
					});
				});
			});
		</script>
	</body>
</html>

