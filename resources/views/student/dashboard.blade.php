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
							@if($additionalInfo && $additionalInfo->profile_picture)
								<img src="{{ asset($additionalInfo->profile_picture) }}" alt="Profile Picture" class="img-fluid rounded-circle" style="max-width: 200px; max-height: 200px;">
							@else
								<img src="../vendors/images/banner-img.png" alt="">
							@endif
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

				{{-- RIASEC Test Results --}}
				@if($riasecResults && $riasecResults->count() > 0)
				<div class="card-box pd-20 height-100-p mb-30">
					<div class="d-flex justify-content-between align-items-center mb-20">
						<h4 class="h4 text-blue mb-0">Your RIASEC Test Results</h4>
						<div class="btn-group btn-group-toggle" data-toggle="buttons">
							<label class="btn btn-outline-primary btn-sm active" id="tableViewBtn">
								<input type="radio" name="viewMode" value="table" checked> <i class="bi bi-table"></i> Table
							</label>
							<label class="btn btn-outline-primary btn-sm" id="chartViewBtn">
								<input type="radio" name="viewMode" value="chart"> <i class="bi bi-bar-chart"></i> Chart
							</label>
						</div>
					</div>

					{{-- Table View --}}
					<div id="riasecTableView">
						<div class="table-responsive">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Take #</th>
										<th>Date Taken</th>
										<th>R</th>
										<th>I</th>
										<th>A</th>
										<th>S</th>
										<th>E</th>
										<th>C</th>
										<th>Top Code</th>
										<th>Result</th>
									</tr>
								</thead>
								<tbody>
									@foreach($riasecResults as $result)
									<tr>
										<td>{{ $result->take_number }}</td>
										<td>{{ $result->created_at->format('M Y') }}</td>
										<td>{{ $result->decoded_scores['R'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['I'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['A'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['S'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['E'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['C'] ?? 0 }}</td>
										<td>{{ $result->top3 }}</td>
										<td>
											<a href="{{ route('testing.results.riasec-result', $result->id) }}" class="btn btn-primary btn-sm">View</a>
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>

					{{-- Chart View --}}
					<div id="riasecChartView" style="display: none;">
						<div class="row">
							@foreach($riasecResults as $result)
							<div class="col-lg-6 col-md-12 mb-20">
								<div class="card-box p-20">
									<h5 class="mt-2 mb-20" style="background-color: #fff3cd; padding: 8px 12px; border: 1px solid #ffeaa7; border-radius: 4px; font-weight: bold; color: #856404;">Test Result #{{ $result->take_number }} - {{ $result->created_at->format('F j, Y') }}</h5>
									<div class="row">
										<div class="col-md-4">
											<div class="text-center mb-15">

												<div class="top-codes-list">
													@php
														$topCodes = str_split($result->top3);
														$descriptions = [
															'R' => 'Realistic (Doers)',
															'I' => 'Investigative (Thinkers)',
															'A' => 'Artistic (Creators)',
															'S' => 'Social (Helpers)',
															'E' => 'Enterprising (Persuaders)',
															'C' => 'Conventional (Organizers)'
														];
														$riasecDetails = [
															'R' => ['description' => 'These people are often good at mechanical or athletic jobs.'],
															'I' => ['description' => 'These people like to watch, learn, analyze, and solve problems.'],
															'A' => ['description' => 'These people like to work in unstructured situations where they can use their creativity.'],
															'S' => ['description' => 'These people like to work with other people, rather than things.'],
															'E' => ['description' => 'These people like to work with others and enjoy persuading and performing.'],
															'C' => ['description' => 'These people are very detail-oriented, organized, and like to work with data.']
														];
														$codeColors = [
															'R' => '#FF6B6B',
															'I' => '#4ECDC4',
															'A' => '#45B7D1',
															'S' => '#FFA07A',
															'E' => '#98D8C8',
															'C' => '#F7DC6F'
														];
													@endphp
													@foreach($topCodes as $code)
													<div class="code-item mb-10">
														<strong style="color: {{ $codeColors[$code] }};">{{ $code }} = {{ explode(' (', $descriptions[$code])[0] }}</strong>
														<p class="small">{{ $riasecDetails[$code]['description'] }}</p>
													</div>
													@endforeach
												</div>
												<div class="mt-20">
													<a href="{{ route('testing.results.riasec-result', $result->id) }}" class="btn btn-primary btn-sm" style="transition: all 0.3s ease;" onmouseover="this.style.background='#0056b3'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)';" onmouseout="this.style.background='#007bff'; this.style.transform='scale(1)'; this.style.boxShadow='none';">View Full Results</a>
												</div>
											</div>
										</div>
										<div class="col-md-8" style="position: relative;">
											<div id="riasecChart{{ $result->id }}" style="height: 400px; transition: opacity 0.5s ease, transform 0.5s ease; position: relative; z-index: 5; background: white;"></div>
											<div id="riasecDetails{{ $result->id }}" style="display: none; transition: opacity 0.5s ease, transform 0.5s ease; position: absolute; top: 0; left: 0; width: 100%; height: 400px; background: white; z-index: 10; transform: translateY(-20px); opacity: 0; padding: 20px; box-sizing: border-box; overflow-y: auto;">
												<!-- RIASEC details will be populated here -->
											</div>
										</div>
									</div>
								</div>
							</div>
							@endforeach
						</div>
					</div>
				</div>
				@endif

				{{-- RIASEC Total Scores Summary --}}
				@if(isset($totalScores) && !empty($totalScores))
				<div class="card-box pd-20 height-100-p mb-30">
					<div class="d-flex justify-content-between align-items-center mb-20">
						<h4 class="h4 text-blue mb-0">Your Total RIASEC Scores (Across All Tests)</h4>
					</div>
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>R</th>
									<th>I</th>
									<th>A</th>
									<th>S</th>
									<th>E</th>
									<th>C</th>
									<th>Total</th>
									<th>Average</th>
									<th>Top Code</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>{{ $totalScores['R'] ?? 0 }}</td>
									<td>{{ $totalScores['I'] ?? 0 }}</td>
									<td>{{ $totalScores['A'] ?? 0 }}</td>
									<td>{{ $totalScores['S'] ?? 0 }}</td>
									<td>{{ $totalScores['E'] ?? 0 }}</td>
									<td>{{ $totalScores['C'] ?? 0 }}</td>
									<td>{{ $totalScores['total'] ?? 0 }}</td>
									<td>{{ $totalScores['average'] ?? 0 }}</td>
									<td>{{ $totalScores['top3'] ?? '' }}</td>
									<td>
										<button class="btn btn-primary btn-sm" onclick="viewTotalScores()">View</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				@endif

				{{-- Life Values Total Scores Summary --}}
				@if(isset($totalLifeValuesScores) && !empty($totalLifeValuesScores))
				<div class="card-box pd-20 height-100-p mb-30">
					<div class="d-flex justify-content-between align-items-center mb-20">
						<h4 class="h4 text-blue mb-0">Your Total Life Values Scores (Across All Tests)</h4>
					</div>
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>A</th>
									<th>B</th>
									<th>C</th>
									<th>D</th>
									<th>E</th>
									<th>F</th>
									<th>G</th>
									<th>H</th>
									<th>I</th>
									<th>J</th>
									<th>K</th>
									<th>L</th>
									<th>M</th>
									<th>N</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>{{ $totalLifeValuesScores['A'] ?? 0 }}</td>
									<td>{{ $totalLifeValuesScores['B'] ?? 0 }}</td>
									<td>{{ $totalLifeValuesScores['C'] ?? 0 }}</td>
									<td>{{ $totalLifeValuesScores['D'] ?? 0 }}</td>
									<td>{{ $totalLifeValuesScores['E'] ?? 0 }}</td>
									<td>{{ $totalLifeValuesScores['F'] ?? 0 }}</td>
									<td>{{ $totalLifeValuesScores['G'] ?? 0 }}</td>
									<td>{{ $totalLifeValuesScores['H'] ?? 0 }}</td>
									<td>{{ $totalLifeValuesScores['I'] ?? 0 }}</td>
									<td>{{ $totalLifeValuesScores['J'] ?? 0 }}</td>
									<td>{{ $totalLifeValuesScores['K'] ?? 0 }}</td>
									<td>{{ $totalLifeValuesScores['L'] ?? 0 }}</td>
									<td>{{ $totalLifeValuesScores['M'] ?? 0 }}</td>
									<td>{{ $totalLifeValuesScores['N'] ?? 0 }}</td>
									<td>
										<button class="btn btn-success btn-sm" onclick="viewTotalLifeValuesScores()">View</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				@endif

				{{-- Life Values Test Results --}}
				@if($lifeValuesResults && $lifeValuesResults->count() > 0)
				<div class="card-box pd-20 height-100-p mb-30">
					<div class="d-flex justify-content-between align-items-center mb-20">
						<h4 class="h4 text-blue mb-0">Your Life Values Test Results</h4>
						<div class="btn-group btn-group-toggle" data-toggle="buttons">
							<label class="btn btn-outline-success btn-sm active" id="lifeValuesTableViewBtn">
								<input type="radio" name="lifeValuesViewMode" value="table" checked> <i class="bi bi-table"></i> Table
							</label>
							<label class="btn btn-outline-success btn-sm" id="lifeValuesChartViewBtn">
								<input type="radio" name="lifeValuesViewMode" value="chart"> <i class="bi bi-bar-chart"></i> Chart
							</label>
						</div>
					</div>

					{{-- Table View --}}
					<div id="lifeValuesTableView">
						<div class="table-responsive">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Take #</th>
										<th>Date Taken</th>
										<th>A</th>
										<th>B</th>
										<th>C</th>
										<th>D</th>
										<th>E</th>
										<th>F</th>
										<th>G</th>
										<th>H</th>
										<th>I</th>
										<th>J</th>
										<th>K</th>
										<th>L</th>
										<th>M</th>
										<th>N</th>
										<th>Result</th>
									</tr>
								</thead>
								<tbody>
									@foreach($lifeValuesResults as $result)
									<tr>
										<td>{{ $result->take_number }}</td>
										<td>{{ $result->created_at->format('M Y') }}</td>
										<td>{{ $result->decoded_scores['A'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['B'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['C'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['D'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['E'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['F'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['G'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['H'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['I'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['J'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['K'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['L'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['M'] ?? 0 }}</td>
										<td>{{ $result->decoded_scores['N'] ?? 0 }}</td>
										<td>
											<a href="{{ route('testing.results.life-values-results', $result->id) }}" class="btn btn-success btn-sm">View</a>
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>

					{{-- Chart View --}}
					<div id="lifeValuesChartView" style="display: none;">
						<div class="row">
							@foreach($lifeValuesResults as $result)
							<div class="col-lg-12 col-md-12 mb-20">
								<div class="card-box p-20">
									<h5 class="mt-2 mb-20" style="background-color: #d4edda; padding: 8px 12px; border: 1px solid #c3e6cb; border-radius: 4px; font-weight: bold; color: #155724;">Test Result #{{ $result->take_number }} - {{ $result->created_at->format('F j, Y') }}</h5>
									<div class="row">
										<div class="col-md-5">
											<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
												@php
													$valueDescriptions = [
														'A' => ['name'=>'ACHIEVEMENT', 'desc'=>'It is important to challenge yourself and work hard to improve.'],
														'B' => ['name'=>'BELONGING', 'desc'=>'It is important to be accepted by others and feel included.'],
														'C' => ['name'=>'CONCERN FOR THE ENVIRONMENT', 'desc'=>'It is important to protect and preserve the environment.'],
														'D' => ['name'=>'CONCERN FOR OTHERS', 'desc'=>'The well-being of others is important.'],
														'E' => ['name'=>'CREATIVITY', 'desc'=>'It is important to have new ideas or create new things.'],
														'F' => ['name'=>'FINANCIAL PROSPERITY', 'desc'=>'It is important to be successful at making money or buying property.'],
														'G' => ['name'=>'HEALTH AND ACTIVITY', 'desc'=>'It is important to be healthy and physically active.'],
														'H' => ['name'=>'HUMILITY', 'desc'=>'It is important to be humble and modest about accomplishments.'],
														'I' => ['name'=>'INDEPENDENCE', 'desc'=>'It is important to make your own decisions and do things your way.'],
														'J' => ['name'=>'LOYALTY TO FAMILY OR GROUP', 'desc'=>'It is important to follow traditions and expectations of family or group.'],
														'K' => ['name'=>'PRIVACY', 'desc'=>'It is important to have time alone.'],
														'L' => ['name'=>'RESPONSIBILITY', 'desc'=>'It is important to be dependable and trustworthy.'],
														'M' => ['name'=>'SCIENTIFIC UNDERSTANDING', 'desc'=>'It is important to use scientific principles to understand and solve problems.'],
														'N' => ['name'=>'SPIRITUALITY', 'desc'=>'It is important to have spiritual beliefs and believe in something greater than yourself.']
													];
													$valueColors = [
														'A' => '#28a745', 'B' => '#20c997', 'C' => '#17a2b8', 'D' => '#6f42c1',
														'E' => '#e83e8c', 'F' => '#fd7e14', 'G' => '#ffc107', 'H' => '#dc3545',
														'I' => '#6c757d', 'J' => '#007bff', 'K' => '#6610f2', 'L' => '#6f42c1',
														'M' => '#e83e8c', 'N' => '#fd7e14'
													];

													// Display in original order (A, B, C, D, etc.)
													$sortedValues = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];
												@endphp
												@foreach($sortedValues as $letter)
													@php $data = $valueDescriptions[$letter]; @endphp
												<div class="value-item p-3 text-center" style="border: 1px solid #dee2e6; border-radius: 8px; background: #f8f9fa; min-height: 160px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: transform 0.2s ease; display: flex; flex-direction: column; justify-content: flex-start;">
													<div style="margin-bottom: 8px;">
														<div style="background: {{ $valueColors[$letter] }}; color: white; border-radius: 50%; width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px; margin-bottom: 4px;">
															{{ $letter }}
														</div>
														<strong style="color: #495057; font-size: 10px; display: block; margin-bottom: 6px;">{{ $data['name'] }}</strong>
													</div>
													<p class="small mb-0" style="color: #6c757d; font-size: 11px; line-height: 1.4;">{{ $data['desc'] }}</p>
												</div>
												@endforeach
											</div>
										</div>
										<div class="col-md-7">
											<div id="lifeValuesChart{{ $result->id }}" style="height: 400px; transition: opacity 0.5s ease, transform 0.5s ease; position: relative; z-index: 5; background: white;"></div>
											<div class="mt-4">
												<h6 class="text-center mb-3" style="color: #000000; font-weight: bold;">Your Scores</h6>
												<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 10px;">
													@foreach($sortedValues as $letter)
														@php $score = $result->decoded_scores[$letter] ?? 0; @endphp
													<div style="padding: 8px 12px; border-radius: 6px; text-align: center; font-weight: 600; font-size: 14px; background: #f8f9fa; border: 1px solid #dee2e6; color: #495057;">
														{{ $letter }}: {{ $score }}
													</div>
													@endforeach
													<div style="padding: 8px 12px; border-radius: 6px; text-align: center; font-weight: 600; font-size: 14px; background: #28a745; border: 1px solid #28a745; color: white; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.background='#218838'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)';" onmouseout="this.style.background='#28a745'; this.style.transform='scale(1)'; this.style.boxShadow='none';">
														<a href="{{ route('testing.results.life-values-results', ['result_id' => $result->id]) }}" style="color: white; text-decoration: none;">View Full Result</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							@endforeach
						</div>
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

		<style>
			.top-codes-list .badge {
				font-size: 18px;
				padding: 8px 12px;
				margin-bottom: 5px;
				display: inline-block;
			}
			.code-item {
				text-align: center;
			}
			.badge-primary {
				background-color: #1cc2f2 !important;
				border: 2px solid #009ec3;
				box-shadow: 0 2px 4px rgba(28, 194, 242, 0.3);
			}
			.badge-lg {
				font-size: 20px !important;
				padding: 10px 15px !important;
			}
			.see-more-btn {
				background: transparent !important;
				border: none !important;
				color: #1cc2f2 !important;
				font-size: 11px !important;
				padding: 0 !important;
				margin-top: 5px !important;
				transition: all 0.3s ease !important;
			}
			.see-more-btn:hover {
				color: #009ec3 !important;
				transform: scale(1.05) !important;
			}
			.arrow {
				display: inline-block;
				transition: transform 0.3s ease !important;
			}
			.see-more-btn:hover .arrow {
				transform: translateX(5px) !important;
			}
			.riasec-detail {
				padding: 15px;
				background: #f8f9fa;
				border-radius: 8px;
				margin-top: 15px;
			}
			.career-list, .pathway-list {
				list-style: disc;
				margin-left: 20px;
				margin-bottom: 10px;
			}
			.career-list li, .pathway-list li {
				margin-bottom: 5px;
			}
		</style>


		{{-- RIASEC Chart Scripts --}}
		@if($riasecResults && $riasecResults->count() > 0)
		<script>
			// RIASEC Chart Data - All 6 codes for pie chart
			const riasecData = [
				@foreach($riasecResults as $result)
				{
					id: {{ $result->id }},
					take_number: {{ $result->take_number }},
					scores: [{{ $result->decoded_scores['R'] ?? 0 }}, {{ $result->decoded_scores['I'] ?? 0 }}, {{ $result->decoded_scores['A'] ?? 0 }}, {{ $result->decoded_scores['S'] ?? 0 }}, {{ $result->decoded_scores['E'] ?? 0 }}, {{ $result->decoded_scores['C'] ?? 0 }}],
					top3: '{{ $result->top3 }}'
				}@if(!$loop->last),@endif
				@endforeach
			];

			// RIASEC descriptions and detailed information
			const riasecDescriptions = {
				'R': 'Realistic (Doers)',
				'I': 'Investigative (Thinkers)',
				'A': 'Artistic (Creators)',
				'S': 'Social (Helpers)',
				'E': 'Enterprising (Persuaders)',
				'C': 'Conventional (Organizers)'
			};

			const riasecDetails = {
				'R': {
					description: 'These people are often good at mechanical or athletic jobs.',
					careers: ['Agriculture', 'Health Assistant', 'Computers', 'Construction', 'Mechanic/Machinist', 'Engineering', 'Food and Hospitality'],
					pathways: ['Agriculture, Food, and Natural Resources', 'Architecture and Construction', 'Manufacturing', 'Transportation, Distribution, and Logistics', 'Health Sciences']
				},
				'I': {
					description: 'These people like to watch, learn, analyze, and solve problems.',
					careers: ['Marine Biology', 'Engineering', 'Chemistry', 'Zoology', 'Medicine/Surgery', 'Consumer Economics', 'Psychology'],
					pathways: ['Health Sciences', 'Science, Technology, Engineering, and Mathematics', 'Agriculture, Food, and Natural Resources']
				},
				'A': {
					description: 'These people like to work in unstructured situations where they can use their creativity.',
					careers: ['Communications', 'Cosmetology', 'Fine and Performing Arts', 'Photography', 'Radio and TV', 'Interior Design', 'Architecture'],
					pathways: ['Arts, Audio/Video Technology, and Communications', 'Architecture and Construction', 'Hospitality and Tourism']
				},
				'S': {
					description: 'These people like to work with other people, rather than things.',
					careers: ['Counseling', 'Nursing', 'Physical Therapy', 'Travel', 'Advertising', 'Public Relations', 'Education'],
					pathways: ['Health Sciences and Human Services', 'Education', 'Law, Government, and Public Safety', 'Culinary, Hospitality, and Tourism']
				},
				'E': {
					description: 'These people like to work with others and enjoy persuading and performing.',
					careers: ['Fashion Merchandising', 'Real Estate', 'Marketing/Sales', 'Law', 'Political Science', 'International Trade', 'Banking/Finance'],
					pathways: ['Business Management and Administration', 'Finance', 'Marketing', 'Law, Government, and Public Safety']
				},
				'C': {
					description: 'These people are very detail-oriented, organized, and like to work with data.',
					careers: ['Accounting', 'Court Reporting', 'Insurance', 'Administration', 'Medical Records', 'Banking', 'Data Processing'],
					pathways: ['Business Management and Administration', 'Finance', 'Information Technology', 'Health Sciences']
				}
			};

			// Initialize charts when page loads
			document.addEventListener('DOMContentLoaded', function() {
				riasecData.forEach(function(result) {
					createRiasecChart(result.id, result.scores);
				});
			});

			function createRiasecChart(resultId, scores) {
				const options = {
					series: scores,
					chart: {
						height: 320,
						type: 'donut',
						toolbar: {
							show: false
						},
						animations: {
							enabled: true,
							easing: 'easeinout',
							speed: 1000,
							animateGradually: {
								enabled: true,
								delay: 150
							},
							dynamicAnimation: {
								enabled: true,
								speed: 350
							}
						}
					},
					plotOptions: {
						pie: {
							donut: {
								size: '70%',
								background: 'transparent',
								labels: {
									show: true,
									name: {
										show: true,
										fontSize: '16px',
										fontWeight: 600,
										color: '#333'
									},
									value: {
										show: true,
										fontSize: '14px',
										fontWeight: 400,
										color: '#666'
									},
									total: {
										show: false
									}
								}
							}
						}
					},
					labels: ['Realistic', 'Investigative', 'Artistic', 'Social', 'Enterprising', 'Conventional'],
					colors: ['#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8', '#F7DC6F'],
					legend: {
						show: false
					},
					tooltip: {
						y: {
							formatter: function(val) {
								return val + ' points';
							}
						}
					},
					dataLabels: {
						enabled: false
					},
					responsive: [{
						breakpoint: 480,
						options: {
							chart: {
								height: 200
							},
							legend: {
								show: false
							}
						}
					}]
				};

				const chart = new ApexCharts(document.querySelector("#riasecChart" + resultId), options);
				chart.render();
			}

			// View toggle functionality
			document.getElementById('tableViewBtn').addEventListener('click', function() {
				document.getElementById('riasecTableView').style.display = 'block';
				document.getElementById('riasecChartView').style.display = 'none';
				this.classList.add('active');
				document.getElementById('chartViewBtn').classList.remove('active');
			});

			document.getElementById('chartViewBtn').addEventListener('click', function() {
				document.getElementById('riasecTableView').style.display = 'none';
				document.getElementById('riasecChartView').style.display = 'block';
				this.classList.add('active');
				document.getElementById('tableViewBtn').classList.remove('active');
			});

			// Function to toggle RIASEC details (replaces chart)
			function toggleRiasecDetails(button) {
				const code = button.getAttribute('data-code');
				const resultId = button.getAttribute('data-result-id');
				const details = riasecDetails[code];
				const chartDiv = document.getElementById('riasecChart' + resultId);
				const detailsDiv = document.getElementById('riasecDetails' + resultId);

				// Close all other details first
				const allButtons = document.querySelectorAll('.see-more-btn');
				allButtons.forEach(btn => {
				    if (btn !== button) {
				        const btnResultId = btn.getAttribute('data-result-id');
				        const btnChartDiv = document.getElementById('riasecChart' + btnResultId);
				        const btnDetailsDiv = document.getElementById('riasecDetails' + btnResultId);
				        if (btnChartDiv) {
				            btnChartDiv.style.opacity = '1';
				            btnChartDiv.style.transform = 'scale(1)';
				        }
				        if (btnDetailsDiv) {
				            btnDetailsDiv.style.opacity = '0';
				            btnDetailsDiv.style.transform = 'translateY(-20px)';
				            setTimeout(() => {
				                btnDetailsDiv.style.display = 'none';
				            }, 500);
				        }
				        btn.innerHTML = 'See More <span class="arrow">-></span>';
				    }
				});

				if (detailsDiv.style.display !== 'block') {
					// Show details, hide chart
					let careersList = details.careers.map(career => `<li>${career}</li>`).join('');
					let pathwaysList = details.pathways.map(pathway => `<li>${pathway}</li>`).join('');

					detailsDiv.innerHTML = `
						<div class="riasec-detail">
							<strong style="font-size: 16px; color: #1cc2f2;">${code}</strong><br>
							<small style="color: #666; font-size: 12px;">${details.description}</small>
							<div style="margin-top: 15px;">
								<strong>Career Options:</strong>
								<ul class="career-list">
									${careersList}
								</ul>
							</div>
							<div style="margin-top: 10px;">
								<strong>Education Pathways:</strong>
								<ul class="pathway-list">
									${pathwaysList}
								</ul>
							</div>
						</div>
					`;
					detailsDiv.style.display = 'block';
					setTimeout(() => {
						detailsDiv.style.opacity = '1';
						detailsDiv.style.transform = 'translateY(0)';
						setTimeout(() => {
							chartDiv.style.opacity = '0';
							chartDiv.style.transform = 'scale(0.9)';
						}, 500);
					}, 10);
					button.innerHTML = 'See Less <span class="arrow">-></span>';
				} else {
					// Hide details, show chart
					detailsDiv.style.opacity = '0';
					detailsDiv.style.transform = 'translateY(-20px)';
					setTimeout(() => {
						chartDiv.style.opacity = '1';
						chartDiv.style.transform = 'scale(1)';
						setTimeout(() => {
							detailsDiv.style.display = 'none';
						}, 500);
					}, 500);
					button.innerHTML = 'See More <span class="arrow">-></span>';
				}
			}
		</script>
		@endif

		{{-- Life Values Chart Scripts --}}
		@if($lifeValuesResults && $lifeValuesResults->count() > 0)
		<script>
			// Life Values Chart Data
			const lifeValuesData = [
				@foreach($lifeValuesResults as $result)
				{
					id: {{ $result->id }},
					take_number: {{ $result->take_number }},
					scores: [{{ $result->decoded_scores['A'] ?? 0 }}, {{ $result->decoded_scores['B'] ?? 0 }}, {{ $result->decoded_scores['C'] ?? 0 }}, {{ $result->decoded_scores['D'] ?? 0 }}, {{ $result->decoded_scores['E'] ?? 0 }}, {{ $result->decoded_scores['F'] ?? 0 }}, {{ $result->decoded_scores['G'] ?? 0 }}, {{ $result->decoded_scores['H'] ?? 0 }}, {{ $result->decoded_scores['I'] ?? 0 }}, {{ $result->decoded_scores['J'] ?? 0 }}, {{ $result->decoded_scores['K'] ?? 0 }}, {{ $result->decoded_scores['L'] ?? 0 }}, {{ $result->decoded_scores['M'] ?? 0 }}, {{ $result->decoded_scores['N'] ?? 0 }}]
				}@if(!$loop->last),@endif
				@endforeach
			];

			// Initialize charts when page loads
			document.addEventListener('DOMContentLoaded', function() {
				lifeValuesData.forEach(function(result) {
					createLifeValuesChart(result.id, result.scores);
				});
			});

			function createLifeValuesChart(resultId, scores) {
				const categories = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];

				const options = {
					series: [{
						name: 'Your Life Values',
						data: scores
					}],
					chart: {
						height: 450,
						type: 'bar',
						toolbar: {
							show: false
						},
						background: 'transparent',
						animations: {
							enabled: true,
							easing: 'easeinout',
							speed: 1200,
							animateGradually: {
								enabled: true,
								delay: 100
							},
							dynamicAnimation: {
								enabled: true,
								speed: 400
							}
						},
						dropShadow: {
							enabled: true,
							top: 2,
							left: 2,
							blur: 4,
							opacity: 0.15
						}
					},
					plotOptions: {
						bar: {
							borderRadius: 8,
							borderRadiusApplication: 'end',
							distributed: true,
							columnWidth: '70%',
							dataLabels: {
								position: 'top'
							}
						}
					},
					colors: ['#28a745', '#20c997', '#17a2b8', '#6f42c1', '#e83e8c', '#fd7e14', '#ffc107', '#dc3545', '#6c757d', '#007bff', '#6610f2', '#6f42c1', '#e83e8c', '#fd7e14'],
					dataLabels: {
						enabled: true,
						formatter: function(val) {
							return val;
						},
						offsetY: -25,
						style: {
							fontSize: '12px',
							fontWeight: 'bold',
							colors: ['#fff']
						},
						background: {
							enabled: true,
							foreColor: '#fff',
							borderRadius: 4,
							opacity: 0.9
						}
					},
					xaxis: {
						categories: categories,
						labels: {
							style: {
								fontSize: '11px',
								fontWeight: '600',
								colors: ['#495057']
							},
							rotate: -45,
							rotateAlways: false,
							hideOverlappingLabels: true
						}
					},
					yaxis: {
						min: 0,
						max: 15,
						tickAmount: 5,
						labels: {
							style: {
								fontSize: '12px',
								fontWeight: '500',
								colors: ['#6c757d']
							},
							formatter: function(val) {
								return val;
							}
						},
						title: {
							text: 'Score (0-15)',
							style: {
								fontSize: '14px',
								fontWeight: '600',
								color: '#000000'
							}
						}
					},
					legend: {
						show: false
					},
					tooltip: {
						theme: 'light',
						y: {
							formatter: function(val) {
								return val + ' points';
							}
						},
						style: {
							fontSize: '13px'
						}
					},
					grid: {
						show: true,
						borderColor: '#e9ecef',
						strokeDashArray: 2,
						xaxis: {
							lines: {
								show: false
							}
						},
						yaxis: {
							lines: {
								show: true
							}
						}
					},
					fill: {
						type: 'gradient',
						gradient: {
							shade: 'light',
							type: 'vertical',
							shadeIntensity: 0.3,
							gradientToColors: undefined,
							inverseColors: false,
							opacityFrom: 0.8,
							opacityTo: 0.4,
							stops: [0, 100]
						}
					},
					responsive: [{
						breakpoint: 768,
						options: {
							chart: {
								height: 400
							},
							xaxis: {
								labels: {
									style: {
										fontSize: '10px'
									},
									rotate: -60
								}
							},
							dataLabels: {
								style: {
									fontSize: '10px'
								},
								offsetY: -20
							}
						}
					}, {
						breakpoint: 480,
						options: {
							chart: {
								height: 350
							},
							xaxis: {
								labels: {
									style: {
										fontSize: '9px'
									},
									rotate: -90
								}
							},
							dataLabels: {
								enabled: false
							}
						}
					}]
				};

				const chart = new ApexCharts(document.querySelector("#lifeValuesChart" + resultId), options);
				chart.render();
			}

			// View toggle functionality for Life Values
			document.getElementById('lifeValuesTableViewBtn').addEventListener('click', function() {
				document.getElementById('lifeValuesTableView').style.display = 'block';
				document.getElementById('lifeValuesChartView').style.display = 'none';
				this.classList.add('active');
				document.getElementById('lifeValuesChartViewBtn').classList.remove('active');
			});

			document.getElementById('lifeValuesChartViewBtn').addEventListener('click', function() {
				document.getElementById('lifeValuesTableView').style.display = 'none';
				document.getElementById('lifeValuesChartView').style.display = 'block';
				this.classList.add('active');
				document.getElementById('lifeValuesTableViewBtn').classList.remove('active');
			});
		</script>
		@endif

		{{-- Modal for Total RIASEC Scores --}}
		<div class="modal fade" id="totalScoresModal" tabindex="-1" role="dialog" aria-labelledby="totalScoresModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document" style="max-width: 90%; margin: 30px auto;">
				<div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
					<div class="modal-header" style="background: linear-gradient(135deg, #1cc2f2, #004d7a); color: white; border-radius: 20px 20px 0 0; border-bottom: none; padding: 20px 30px;">
						<h4 class="modal-title" id="totalScoresModalLabel" style="font-weight: 700; font-size: 24px;">Your Total RIASEC Scores</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
							<span aria-hidden="true" style="font-size: 28px;">&times;</span>
						</button>
					</div>
					<div class="modal-body" style="padding: 30px; background: #f8f9fa;">
						<div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 20px;">
							<h5 style="text-align: center; color: #004d7a; font-weight: 600; margin-bottom: 20px; font-size: 20px;">TOTAL SCORES ACROSS ALL TESTS</h5>

							<!-- SCORES TABLE -->
							<table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">
								@foreach(['R' => 'Realistic', 'I' => 'Investigative', 'A' => 'Artistic', 'S' => 'Social', 'E' => 'Enterprising', 'C' => 'Conventional'] as $code => $name)
									<tr style="border-bottom: 1px solid #eee;">
										<td style="padding: 12px 15px; font-weight: bold; font-size: 20px; color: #1cc2f2; width: 50px; text-align: center;">{{ $code }}</td>
										<td style="padding: 12px 15px; font-size: 16px; color: #555;">= {{ $name }}</td>
										<td style="padding: 12px 15px; font-size: 16px; font-weight: 600; color: #004d7a; text-align: right;">{{ $totalScores[$code] ?? 0 }}</td>
									</tr>
								@endforeach
							</table>

							<!-- INTEREST CODE -->
							<div style="text-align: center; margin: 25px 0;">
								<h6 style="color: #004d7a; font-weight: 600; margin-bottom: 15px; font-size: 18px;">YOUR OVERALL INTEREST CODE</h6>
								<div style="display: inline-flex; gap: 15px;">
									@foreach(str_split($totalScores['top3'] ?? '') as $code)
										<div style="background: #1cc2f2; color: white; font-weight: bold; font-size: 22px; padding: 12px 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(28, 194, 242, 0.3);">{{ $code }}</div>
									@endforeach
								</div>
							</div>
						</div>

						<!-- TEXT ANALYSIS -->
						<div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 20px; border-left: 5px solid #1cc2f2;">
							<p style="font-size: 18px; color: #333; line-height: 1.6; margin: 0; text-align: center;">
								Based on your total scores across all tests, you are
								@php
									$descriptions = [
										'R' => 'Realistic (Doers)',
										'I' => 'Investigative (Thinkers)',
										'A' => 'Artistic (Creators)',
										'S' => 'Social (Helpers)',
										'E' => 'Enterprising (Persuaders)',
										'C' => 'Conventional (Organizers)',
									];
								@endphp
								@foreach(str_split($totalScores['top3'] ?? '') as $code)
									<strong style="color: #004d7a; font-size: 18px;">{{ $descriptions[$code] ?? $code }}</strong>
									<span style="color: #1cc2f2; font-weight: bold; font-size: 16px;">({{ $code }})</span>
									@if(!$loop->last), @endif
								@endforeach.
							</p>
						</div>

						<!-- CAREER PATHWAYS -->
						<div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
							<h5 style="text-align: center; color: #004d7a; font-weight: 600; margin-bottom: 25px; font-size: 20px;">Career Pathways</h5>
							<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
								@php
									$careerData = [
										'R' => ['careers' => ['Agriculture', 'Health Assistant', 'Computers', 'Construction'], 'pathways' => ['Agriculture & Natural Resources', 'Architecture & Construction', 'Manufacturing']],
										'I' => ['careers' => ['Marine Biology', 'Engineering', 'Chemistry', 'Zoology'], 'pathways' => ['Health Sciences', 'STEM', 'Agriculture']],
										'A' => ['careers' => ['Communications', 'Cosmetology', 'Fine Arts', 'Photography'], 'pathways' => ['Arts & Communications', 'Architecture', 'Hospitality']],
										'S' => ['careers' => ['Counseling', 'Nursing', 'Physical Therapy', 'Education'], 'pathways' => ['Health Sciences', 'Education', 'Public Safety']],
										'E' => ['careers' => ['Fashion Merchandising', 'Real Estate', 'Marketing', 'Law'], 'pathways' => ['Business Management', 'Finance', 'Marketing']],
										'C' => ['careers' => ['Accounting', 'Court Reporting', 'Insurance', 'Banking'], 'pathways' => ['Business Management', 'Finance', 'Information Technology']],
									];
								@endphp
								@foreach(str_split($totalScores['top3'] ?? '') as $code)
									@if(isset($careerData[$code]))
										<div style="background: #e8f4fd; border: 2px solid #1cc2f2; border-radius: 12px; padding: 20px;">
											<h6 style="color: #004d7a; font-weight: 600; margin-bottom: 10px; font-size: 16px;">{{ $code }} - Top Careers</h6>
											<ul style="margin: 0; padding-left: 20px; font-size: 14px; line-height: 1.5;">
												@foreach(array_slice($careerData[$code]['careers'], 0, 4) as $career)
													<li>{{ $career }}</li>
												@endforeach
											</ul>
											<h6 style="color: #004d7a; font-weight: 600; margin: 15px 0 10px 0; font-size: 14px;">Related Pathways</h6>
											<ul style="margin: 0; padding-left: 20px; font-size: 14px; line-height: 1.5;">
												@foreach($careerData[$code]['pathways'] as $pathway)
													<li>{{ $pathway }}</li>
												@endforeach
											</ul>
										</div>
									@endif
								@endforeach
							</div>
						</div>
					</div>
					<div class="modal-footer" style="border-top: none; padding: 20px 30px; background: #f8f9fa; border-radius: 0 0 20px 20px;">
						<button type="button" class="btn btn-secondary" data-dismiss="modal" style="background: #6c757d; border: none; padding: 10px 25px; border-radius: 8px; font-weight: 500;">Close</button>
					</div>
				</div>
			</div>
		</div>

		<script>
			function viewTotalScores() {
				$('#totalScoresModal').modal('show');
			}
		</script>

		{{-- Modal for Total Life Values Scores --}}
		<div class="modal fade" id="totalLifeValuesScoresModal" tabindex="-1" role="dialog" aria-labelledby="totalLifeValuesScoresModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document" style="max-width: 90%; margin: 30px auto;">
				<div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
					<div class="modal-header" style="background: linear-gradient(135deg, #28a745, #004d7a); color: white; border-radius: 20px 20px 0 0; border-bottom: none; padding: 20px 30px;">
						<h4 class="modal-title" id="totalLifeValuesScoresModalLabel" style="font-weight: 700; font-size: 24px;">Your Total Life Values Scores</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
							<span aria-hidden="true" style="font-size: 28px;">&times;</span>
						</button>
					</div>
					<div class="modal-body" style="padding: 30px; background: #f8f9fa;">
						<div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 20px;">
							<h5 style="text-align: center; color: #004d7a; font-weight: 600; margin-bottom: 20px; font-size: 20px;">TOTAL SCORES ACROSS ALL TESTS</h5>

							<!-- SCORES TABLE -->
							<table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">
								@php
									$valueDescriptions = [
										'A' => 'ACHIEVEMENT',
										'B' => 'BELONGING',
										'C' => 'CONCERN FOR THE ENVIRONMENT',
										'D' => 'CONCERN FOR OTHERS',
										'E' => 'CREATIVITY',
										'F' => 'FINANCIAL PROSPERITY',
										'G' => 'HEALTH AND ACTIVITY',
										'H' => 'HUMILITY',
										'I' => 'INDEPENDENCE',
										'J' => 'LOYALTY TO FAMILY OR GROUP',
										'K' => 'PRIVACY',
										'L' => 'RESPONSIBILITY',
										'M' => 'SCIENTIFIC UNDERSTANDING',
										'N' => 'SPIRITUALITY'
									];
								@endphp
								@foreach(range('A', 'N') as $letter)
									<tr style="border-bottom: 1px solid #eee;">
										<td style="padding: 12px 15px; font-weight: bold; font-size: 20px; color: #28a745; width: 50px; text-align: center;">{{ $letter }}</td>
										<td style="padding: 12px 15px; font-size: 16px; color: #555;">= {{ $valueDescriptions[$letter] ?? $letter }}</td>
										<td style="padding: 12px 15px; font-size: 16px; font-weight: 600; color: #004d7a; text-align: right;">{{ $totalLifeValuesScores[$letter] ?? 0 }}</td>
									</tr>
								@endforeach
							</table>

						</div>

					</div>
					<div class="modal-footer" style="border-top: none; padding: 20px 30px; background: #f8f9fa; border-radius: 0 0 20px 20px;">
						<button type="button" class="btn btn-secondary" data-dismiss="modal" style="background: #6c757d; border: none; padding: 10px 25px; border-radius: 8px; font-weight: 500;">Close</button>
					</div>
				</div>
			</div>
		</div>

		<script>
			function viewTotalLifeValuesScores() {
				$('#totalLifeValuesScoresModal').modal('show');
			}
		</script>

	</body>
</html>