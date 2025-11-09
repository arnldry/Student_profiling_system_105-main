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
		<!-- header -->
        <div class="header">
            @include('layouts.navbar.superadmin.navbar')
		</div>
		<div class="left-side-bar">
            @include('layouts.sidebar.superadmin.sidebar')
        </div>

		
		<div class="main-container">
			<div class="xs-pd-20-10 pd-ltr-20">
				<div class="title pb-20">
					<h2 class="h3 mb-0">
                    Hello, {{ Auth::user()->name }}!
                    </h2>
				</div>
				<div class="row pb-10">
					<!-- Active School Year Card -->
					<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark">
										@if($activeSchoolYear)
											{{ $activeSchoolYear->school_year }}
										@else
											No Active SY
										@endif
									</div>

									<div class="font-14 text-secondary weight-500">
										Active School Year
									</div>
								</div>
								<div class="widget-icon" style="background-color:#007bff;">
									<div class="icon" data-color="#ffffff">
										<i class="icon-copy dw dw-calendar-1"></i>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Admin Card -->
					<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data" >
                                <div class="weight-700 font-24 text-dark">{{ $adminCount }}</div>
                                <div class="font-14 text-secondary weight-500">
                                      Admin
                                </div>
								</div>
								<div class="widget-icon" style="background-color:darkblue;">
									<div class="icon" data-color="#fff">
										<i class="icon-copy fa fa-users" aria-hidden="true"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-3 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark">{{ $studentCount }}</div>
                                    <div class="font-14 text-secondary weight-500">
                                        Student
                                    </div>
								</div>
								<div class="widget-icon" style="background-color:darkgreen;">
									<div class="icon" data-color="#ffffff">
										<i class="icon-copy fa fa-graduation-cap" aria-hidden="true"></i>
									</div>
								</div>
							</div>
						</div>
					</div>


                    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                        <div class="card-box height-100-p widget-style3">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div class="widget-data">
                                    <div id="current-time" class="weight-700 font-24 text-dark"></div>
                                    <div id="current-date" class="font-14 text-secondary weight-500"></div>
                                </div>
                                <div class="widget-icon">
                                    <div class="icon" data-color="#ffd700">
                                        <i class="icon-copy dw dw-clock-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>

				<!-- Quick Actions Section -->
				<div class="row pb-10">
					<div class="col-12">
						<div class="card-box pd-20">
							<h4 class="h4 text-blue mb-3">Quick Actions</h4>
							<div class="row">
								<div class="col-md-3 col-sm-6 mb-3">
									<a href="{{ route('superadmin.admin-accounts') }}" class="btn btn-primary btn-block">
										<i class="bi bi-people"></i> Admin Account
									</a>
								</div>
								<div class="col-md-3 col-sm-6 mb-3">
									<a href="{{ route('superadmin.student-accounts') }}" class="btn btn-success btn-block">
										<i class="fa fa-graduation-cap"></i> Student Account
									</a>
								</div>
								<div class="col-md-3 col-sm-6 mb-3">
									<a href="{{ route('superadmin.backup-restore') }}" class="btn btn-warning btn-block">
										<i class="bi bi-gear-fill"></i> Backup & Restore
									</a>
								</div>
								<div class="col-md-3 col-sm-6 mb-3">
									<a href="{{ route('superadmin.school-year') }}" class="btn btn-danger btn-block">
										<i class="bi bi-calendar"></i> Manage School Year
									</a>
								</div>
								<div class="col-md-3 col-sm-6 mb-3">
									<a href="{{ route('superadmin.curriculum') }}" class="btn btn-info btn-block">
										<i class="fa fa-book"></i> Manage Curriculum
									</a>
								</div>
								<div class="col-md-3 col-sm-6 mb-3">
									<a href="{{ route('superadmin.archived-student-data') }}" class="btn btn-secondary btn-block">
										<i class="bi bi-archive"></i> Archived Student Data
									</a>
								</div>
								<div class="col-md-3 col-sm-6 mb-3">
									<a href="{{ route('superadmin.archived-files') }}" class="btn btn-dark btn-block">
										<i class="bi bi-archive"></i> Archived Files
									</a>
								</div>
								<div class="col-md-3 col-sm-6 mb-3">
									<a href="{{ route('superadmin.update-profile') }}" class="btn btn-light btn-block">
										<i class="bi bi-person"></i> Profile
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- <div class="bg-white pd-20 card-box mb-30">
						<h4 class="h4 text-blue">Student Chart</h4>
						<div id="chart3" style="min-height: 365px;"></div>
				</div>			 -->
		</div>
						
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
		<!-- Google Tag Manager (noscript) -->
			
		<!-- <script>	
			function generateSchoolYears(startYear, count) {
				let years = [];
				for (let i = 0; i < count; i++) {
					let endYear = startYear + 1;
					years.push(`${startYear}-${endYear}`);
					startYear++;
				}
				return years;
			}

			const schoolYears = generateSchoolYears(2025, 5); // 5 years starting 2025

			// Example data (replace with dynamic data if needed)
			const maleData = [50, 45, 60, 55, 70];
			const femaleData = [48, 50, 55, 60, 65];

			// Calculate total students for each year
			const totalData = maleData.map((m, i) => m + femaleData[i]);

			var options = {
				chart: {
					type: 'bar',
					height: 350
				},
				series: [
					{
						name: 'Male',
						data: maleData
					},
					{
						name: 'Female',
						data: femaleData
					},
					{
						name: 'Total',
						data: totalData
					}   
				],
				xaxis: {
					categories: schoolYears
				},
				legend: {
					position: 'bottom'
				},
				plotOptions: {
					bar: {
						columnWidth: '50%',
						distributed: false
					}
				}
			};

			var chart = new ApexCharts(document.querySelector("#chart3"), options);
			chart.render();

			// Display total students below the chart
			let totalText = 'Total Students per Year: ';
			totalText += schoolYears.map((year, i) => `${year}: ${totalData[i]}`).join(', ');
			document.getElementById('total-students').innerText = totalText;
		</script> -->

	</body>
</html>
