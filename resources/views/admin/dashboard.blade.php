<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Dashboard</title>

		<!-- Favicon -->
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
		@include('layouts.navbar.admin.navbar')
	</div>
	<div class="left-side-bar">
		@include('layouts.sidebar.admin.sidebar')
	</div>

		
	<div class="main-container">
		<div class="xs-pd-20-10 pd-ltr-20">
			<div class="title pb-20">
				<h2 class="h3 mb-0">
					Hello, {{ Auth::user()->name }}!
				</h2>
			</div>

			

			<!-- Row for widgets -->
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

				<!-- Student Card -->
				<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
					<div class="card-box height-100-p widget-style3">
						<div class="d-flex flex-wrap">
							<div class="widget-data">
								<div id="student-count" class="weight-700 font-24 text-dark">{{ $studentCount }}</div>

								<div class="font-14 text-secondary weight-500">
									Student Profile
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

				<!-- Time & Date Card -->
				<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
					<div class="card-box height-100-p widget-style3">
						<div class="d-flex flex-wrap justify-content-between align-items-center">
							<div class="widget-data">
								<div id="current-time" class="weight-700 font-24 text-dark"></div>
								<div id="current-date" class="font-14 text-secondary weight-500"></div>
							</div>
							<div class="widget-icon" style="background-color:gold;">
								<div class="icon" data-color="#ffffff">
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
								<a href="{{ route('admin.student-profile') }}" class="btn btn-primary btn-block">
									<i class="fa fa-users"></i> View Students
								</a>
							</div>
							<div class="col-md-3 col-sm-6 mb-3">
								<a href="{{ route('admin.test-results') }}" class="btn btn-success btn-block">
									<i class="fa fa-clipboard-check"></i> Test Results
								</a>
							</div>
							<div class="col-md-3 col-sm-6 mb-3">
								<a href="{{ route('admin.activity-log') }}" class="btn btn-warning btn-block">
									<i class="fa fa-history"></i> Activity Log
								</a>
							</div>
							<div class="col-md-3 col-sm-6 mb-3">
								<a href="{{ route('admin.archived-files-data') }}" class="btn btn-info btn-block">
									<i class="fa fa-archive"></i> Archived Files
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>	







			<!-- Student Chart -->

			<div class="row pb-10">

					<div class="col-md-8 mb-20">
						<div class="card-box height-100-p pd-20">
							<h4 class="h4 text-blue">Student Enrollment by Gender per School Year</h4>
							<div id="chart3" style="min-height: 365px;">
								
							</div>
							<div id="total-students" class="pt-10 font-16 text-dark fw-bold">

							</div>
						</div>
					</div>
					<div class="col-md-4 mb-20">
						<div
							class="card-box min-height-200px pd-20 mb-20"
							data-bgcolor="#455a64"
						>
							<div class="d-flex justify-content-between pb-20 text-white">
								<div class="icon h1 text-white">
									<i class="bi bi-clipboard-check" aria-hidden="true"></i>
									<!-- <i class="icon-copy fa fa-stethoscope" aria-hidden="true"></i> -->
								</div>
								<!-- <div class="font-14 text-right">
									<div><i class="icon-copy ion-arrow-up-c"></i> 2.69%</div>
									<div class="font-12">Since last month</div>
								</div> -->
							</div>
							<div class="d-flex justify-content-between align-items-end">
								<div class="text-white">
									<div class="font-14">Riasec total test</div>
									<div class="font-24 weight-500" id="riasecTotal">0</div>
								</div>
								<div class="max-width-150">
									<!-- <div id="appointment-chart"></div> -->
								</div>
							</div>
						</div>
						<div class="card-box min-height-200px pd-20" data-bgcolor="#265ed7">
							<div class="d-flex justify-content-between pb-20 text-white">
								<div class="bi bi-heart-pulse-fill">
									<i class="fa fa-stethoscope" aria-hidden="true"></i>
								</div>
								<!-- <div class="font-14 text-right">
									<div><i class="icon-copy ion-arrow-down-c"></i> 3.69%</div>
									<div class="font-12">Since last month</div>
								</div> -->
							</div>
							<div class="d-flex justify-content-between align-items-end">
								<div class="text-white">
									<div class="font-14">Life values total test</div>
									<div class="font-24 weight-500" id="lifeValuesTotal"></div>
								</div>
								<div class="max-width-150">
									<!-- <div id="surgery-chart"></div> -->
								</div>
							</div>
						</div>
					</div>
			</div>








			







			

			<!-- Pie Chart Section -->
			<div class="bg-white pd-20 card-box mb-30">
				<h4 class="h4 text-blue">Student Distribution by Curriculum</h4>
				<div id="curriculumChart" style="min-height: 365px;"></div>
				<div id="curriculum-total" class="pt-10 font-16 text-dark fw-bold"></div>
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
		<!-- Google Tag Manager (noscript) -->
			
		<script>	

		// ApexCharts Dynamic Curriculum Pie Chart
		fetch('{{ route("admin.students.curriculum-chart-data") }}')
.then(res => res.json())
.then(res => {
	const curriculumLabels = res.labels;
	const curriculumData = res.data;
	const total = curriculumData.reduce((a, b) => a + b, 0);

	var curriculumOptions = {
		chart: { type: 'donut', height: 365 },
		series: curriculumData,
		labels: curriculumLabels,
		legend: { position: 'bottom' },
		colors: [
			'#1E90FF', '#FF7F50', '#32CD32', '#FFD700',
			'#FF69B4', '#8A2BE2', '#FF8C00', '#20B2AA'
		],
		dataLabels: {
			formatter: function(val, opts) {
				return opts.w.globals.series[opts.seriesIndex];
			}
		},
		tooltip: {
			y: { formatter: val => val + " Students" }
		},
		plotOptions: {
			pie: {
				donut: {
					size: '65%',
					labels: {
						show: true,
						name: {
							show: true,
							offsetY: 10,
							fontSize: '16px'
						},
						value: {
							show: true,
							fontSize: '20px',
							fontWeight: 600,
							color: '#000',
							formatter: () => total
						},
						total: {
							show: true,
							label: 'Total Students',
							fontSize: '14px',
							color: '#555',
							formatter: () => total
						}
					}
				}
			}
		}
	};

	var curriculumChart = new ApexCharts(document.querySelector("#curriculumChart"), curriculumOptions);
	curriculumChart.render();
})
.catch(err => console.error(err));

		
		// ApexCharts Dynamic Student Chart by School Year

		fetch('{{ route("admin.students.chart-data") }}')
			.then(res => res.json())
			.then(data => {
				const totalData = data.maleData.map((m, i) => m + data.femaleData[i]);

				var options = {
					chart: { type: 'bar', height: 350 },
					series: [
						{ name: 'Male', data: data.maleData },
						{ name: 'Female', data: data.femaleData },
						{ name: 'Total', data: totalData }
					],
					xaxis: { categories: data.labels },
					legend: { position: 'bottom' },
					plotOptions: { bar: { columnWidth: '50%' } }
				};

				var chart = new ApexCharts(document.querySelector("#chart3"), options);
				chart.render();

				//Display total students per year
				// let totalText = 'Total Students per Year: ' + 
				// 				data.labels.map((year, i) => `${year}: ${totalData[i]}`).join(', ');
				// document.getElementById('total-students').innerText = totalText;
			})
			.catch(err => console.error(err));


fetch('{{ route("admin.dashboard.stats") }}')
.then(res => res.json())
.then(data => {
	// Set student count into the specific element so we don't overwrite the Active School Year widget
	// document.getElementById('student-count').textContent = data.students;
	document.getElementById("riasecTotal").innerText = data.riasec;
	document.getElementById("lifeValuesTotal").innerText = data.lifeValues;
})
.catch(err => console.error("Error loading dashboard stats:", err));




		
		</script>
	</body>
</html>
