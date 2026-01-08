<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>DeskApp - Bootstrap Admin Dashboard HTML Template</title>

		<!-- Site favicon -->
		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="/vendors/images/apple-touch-icon.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="/vendors/images/favicon-32x32.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="/vendors/images/favicon-16x16.png"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="/vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="/vendors/styles/icon-font.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="/src/plugins/datatables/css/dataTables.bootstrap4.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="/src/plugins/datatables/css/responsive.bootstrap4.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="/vendors/styles/style.css" />

	<style>
		html, body {
		overflow-y: auto;
		-ms-overflow-style: none;  /* IE and Edge */
		scrollbar-width: none;  /* Firefox */
		}

		html::-webkit-scrollbar,
		body::-webkit-scrollbar {
		display: none;  /* Chrome, Safari and Opera */
		}

		body {
		font-family: "Inter", sans-serif;
		background: linear-gradient(135deg, #e8f7ff, #ffffff);
		margin: 0;
		padding: 20px 5px;
		display: flex;
		justify-content: center;
		align-items: flex-start;
		min-height: 100vh;
		}

		
		.main {
			background: #fff;
			border-radius: 16px;
			box-shadow: 0 8px 26px rgba(0, 0, 0, 0.08);
			padding: 30px;
			max-width: 700px;
			width: 100%;
		}
				

		h2 {
			text-align: center;
			color: #004d7a;
			font-weight: 700;
			margin-bottom: 35px;
			letter-spacing: 0.5px;
		}

		/* === Grid Layout === */
		.results-grid {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 25px;
		}

		/* === Card Style === */
		.careerCard {
		background: #f9fcff;
		border: 1px solid #d6ebf7;
		border-radius: 12px;
		padding: 20px 25px;
		transition: all 0.3s ease;
		}


		.careerCard:hover {
		transform: translateY(-5px);
		box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
		}

		.careerCard h3 {
		color: #0089b7;
		margin-bottom: 10px;
		font-size: 18px;
		border-bottom: 2px solid #1cc2f2;
		display: inline-block;
		padding-bottom: 3px;
		}

		.careerCard p {
		font-size: 14px;
		color: #444;
		line-height: 1.5;
		margin-bottom: 10px;
		}

		.careerCard ul {
		margin: 0 0 15px 20px;
		color: #333;
		font-size: 14px;
		}

		/* Related Pathways Box */
		.related-box {
		background: #f9fcff;
		border: 1px solid #d6ebf7;
		border-radius: 12px;
		padding: 20px 25px;
		transition: all 0.3s ease;
		}


		.related-box:hover {
		transform: translateY(-5px);
		box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
		}

		.related-box h3 {
		color: #0089b7;
		margin-bottom: 10px;
		font-size: 18px;
		border-bottom: 2px solid #1cc2f2;
		display: inline-block;
		padding-bottom: 3px;
		}

		.related-box p {
		font-size: 14px;
		color: #444;
		line-height: 1.5;
		margin-bottom: 10px;
		}

		.related-box ul {
		margin: 0 0 15px 20px;
		color: #333;
		font-size: 14px;
		}


		.related-box:hover {
		transform: translateY(-5px);
		box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
		}


		/* Responsive for mobile */
		@media (max-width: 768px) {
		.results-grid {
			grid-template-columns: 1fr;
		}
		}

		/* Progress Bar */
		.progress-container {
			width: 100%;
			height: 6px;
			background-color: #e0e7ff;
			border-radius: 10px;
			margin-bottom: 15px;
			overflow: hidden;
		}

		.progress-bar {
			height: 100%;
			background: linear-gradient(90deg, #1cc2f2, #0089b7);
			width: 0%;
			transition: width 0.3s ease-in-out;
			border-radius: 10px;
		}

		/* Navigation */
		.back-to-dashboard {
			position: fixed;
			top: 20px;
			left: 20px;
			z-index: 1000;
		}

		/* Full screen layout */
		.main {
			width: 90%;
			max-width: 1200px;
			margin: 40px auto;
		}

		/* Centered content */
		#instructionSection {
			text-align: center;
			max-width: 800px;
			margin: 0 auto 40px;
			padding: 30px;
		}

		#instructionSection p {
			font-size: 16px;
			line-height: 1.6;
			color: #444;
			margin-bottom: 25px;
		}

		.button-group {
			display: flex;
			gap: 15px;
			justify-content: center;
			margin-top: 25px;
		}

		.btn {
			padding: 10px 25px;
			font-size: 16px;
			border-radius: 8px;
			transition: all 0.3s ease;
		}

		.btn:hover {
			transform: translateY(-2px);
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		}


		
		
    </style>

	</head>
	<body>

		<div class="main">
			<div class="xs-pd-20-10 pd-ltr-20">
				<div id="instructionSection">
					<h2 class="h3 mb-0">THE RIASEC TEST</h2>
					<p>Follow these easy steps to see where your interests are.<br>
					Read each statement. Select YES if you agree with the statement. There are no wrong answers!</p>
					<button type="button" id="startBtn" class="btn btn-primary">Start Test</button>
					<a href="{{ route('student.testingdash') }}" class="btn btn-secondary">Back</a>
				</div>



				<!-- Result Section -->
				<div id="resultSection">
					<h2></h2>
						<div class="results-grid">
							<div class="careerCard">
								<h3>R = Realistic</h3>
								<p>These people are often good at mechanical or athletic jobs. Good college majors for Realistic people are:</p>
								<ul>
									<li>Agriculture</li><li>Health Assistant</li><li>Computers</li>
									<li>Construction</li><li>Mechanic/Machinist</li><li>Engineering</li><li>Food and Hospitality</li>
								</ul>
							</div>

							<div class="related-box">
								<h3>I = Investigative</h3>
								<p>These people like to watch, learn, analyze, and solve problems. Good college majors for Investigative people are:</p>
								<ul>
									<li>Marine Biology</li><li>Engineering</li><li>Chemistry</li>
									<li>Zoology</li><li>Medicine/Surgery</li><li>Consumer Economics</li><li>Psychology</li>
								</ul>
							</div>

							<div class="careerCard">
								<h3>A = Artistic</h3>
								<p>These people like to work in unstructured situations where they can use their creativity. Good majors for Artistic people are:</p>
								<ul>
									<li>Communications</li><li>Cosmetology</li><li>Fine and Performing Arts</li>
									<li>Photography</li><li>Radio and TV</li><li>Interior Design</li><li>Architecture</li>
								</ul>
							</div>
							<div class="related-box">
								<h3>S = Social</h3>
								<p>These people like to work with other people, rather than things. Good college majors for Social people are:</p>
								<ul>
									<li>Counseling</li><li>Nursing</li><li>Physical Therapy</li>
									<li>Travel</li><li>Advertising</li><li>Public Relations</li><li>Education</li>
								</ul>	
							</div>

							<div class="careerCard">
								<h3>E = Enterprising</h3>
								<p>These people like to work with others and enjoy persuading and performing. Good college majors for Enterprising people are:</p>
								<ul>
									<li>Fashion Merchandising</li><li>Real Estate</li><li>Marketing/Sales</li>
									<li>Law</li><li>Political Science</li><li>International Trade</li><li>Banking/Finance</li>
								</ul>
							</div>
							<div class="related-box">
								<h3>C = Conventional</h3>
								<p>These people are very detail-oriented, organized, and like to work with data. Good college majors for Conventional people are:</p>
								<ul>
									<li>Accounting</li><li>Court Reporting</li><li>Insurance</li>
									<li>Administration</li><li>Medical Records</li><li>Banking</li><li>Data Processing</li>
								</ul>
							</div>
						</div>
					</div>
				</div>

				<form id="riasecForm" method="POST" action="{{ route('riasec.save') }}"  style="display:none;"> 
					@csrf
					<div class="scroll-instruction">
							<p>↑ Scroll up to go back to the previous question</p>
						</div>
					<div class="progress-container">
				
						<div class="progress-bar"></div>

					</div>
					<div id="questionContainer"> </div>
					
				</form>
				@include('partials.riasec-script')

	        </div>
		</div>
	</body>
</html>