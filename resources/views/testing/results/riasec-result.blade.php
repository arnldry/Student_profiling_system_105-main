<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>RIASEC Result</title>
    
    <link rel="stylesheet" href="/vendors/styles/core.css">
    <link rel="stylesheet" href="/vendors/styles/style.css">

    <style>
      body {
      font-family: "Inter", "Segoe UI", Arial, sans-serif;
      background: linear-gradient(135deg, #1cc2f2, #004d7a);
      min-height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 40px 10px;
      overflow-y: auto;
    }

    #results {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 950px;
      padding: 40px 50px;
      box-sizing: border-box;
      animation: fadeIn 0.6s ease;

      
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    #pathway_title {
      text-align: center;
      font-size: 26px;
      font-weight: 700;
      color: #004d7a;
      letter-spacing: 0.5px;
    }

    #riasec_title {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 18px;
      color: #555;
      margin-top: 10px;
    }

    .riasec_hr {
      border: none;
      border-top: 3px solid #1cc2f2;
      width: 70%;
      margin: 15px auto 25px;
    }

    #result_3_block table {
      width: 100%;
      border-collapse: collapse;
    }

    #result_3_block td {
      padding: 10px 8px;
      font-size: 15px;
      border-bottom: 1px solid #eee;
    }

    .riasecResult_chars {
      font-weight: bold;
      font-size: 18px;
      color: #1cc2f2;
      width: 30px;
      text-align: center;
    }

    #myInterest_code {
      font-size: 18px;
      font-weight: bold;
      margin-top: 25px;
      color: #004d7a;
      text-align: center;
    }

    #myCodes {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 12px;
      margin: 15px 0 20px;
    }

    .myCode {
      background: #1cc2f2;
      color: #fff;
      font-weight: bold;
      font-size: 18px;
      padding: 10px 18px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      transition: transform 0.3s ease;
    }

    .myCode:hover {
      transform: scale(1.1);
    }

    #result_print {
      text-align: center;
      margin-top: 25px;
    }

    .btn-print,
    .btn-back {
      background: #1cc2f2;
      color: #fff;
      border: none;
      padding: 12px 25px;
      font-size: 16px;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      margin: 5px;
      text-decoration: none;
      display: inline-block;
      transition: background 0.3s, transform 0.2s;
    }

    .btn-print:hover,
    .btn-back:hover {
      background: #009ec3;
      transform: scale(1.05);
    }

    .nav-link {
      color: #1cc2f2;
      text-decoration: none;
      font-weight: 500;
      font-size: 14px;
      padding: 4px 8px;
      border-radius: 4px;
      transition: all 0.2s;
    }

    .nav-link:hover {
      background: #f0f8ff;
      color: #009ec3;
    }

    .nav-link.disabled {
      color: #ccc;
      cursor: not-allowed;
    }

    #text_analysis {
      font-size: 16px;
      margin: 30px 0;
      padding: 15px 20px;
      background: #f7f9fb;
      border-left: 4px solid #1cc2f2;
      border-radius: 8px;
      color: #333;
    }

    .results-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }

    .careerCard, .related-box {
        background: #fafafa;
        border: 1px solid #e0e0e0;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
    }

    .careerCard.highlighted, .related-box.highlighted {
        background: #e8f4fd;
        border: 2px solid #1cc2f2;
        box-shadow: 0 6px 16px rgba(28, 194, 242, 0.2);
    }

    .careerCard.dimmed, .related-box.dimmed {
        background: #f5f5f5;
        border: 1px solid #ccc;
        opacity: 0.6;
    }

    .careerCard:hover, .related-box:hover {
        transform: translateY(-5px);
    }

    .careerCard h3, .related-box h3 {
      color: #004d7a;
      font-size: 18px;
      margin-bottom: 10px;
    }

    .careerCard p, .related-box p {
      color: #555;
      margin-bottom: 10px;
    }

    .careerCard ul, .related-box ul {
        margin: 0;
        padding-left: 15px;
        list-style-type: disc;
        columns: 2;
        column-gap: 20px;
        font-size: 13px;
        line-height: 1.4;
    }

    .careerCard li, .related-box li {
        margin-bottom: 3px;
        break-inside: avoid;
    }

    .career-content {
        display: flex;
        gap: 20px;
        margin-top: 10px;
    }

    .career-options {
        flex: 1;
    }

    .career-pathways {
        flex: 1;
    }

    .career-options ul,
    .career-pathways ul {
        columns: 1 !important;
        padding-left: 15px;
        margin: 0;
    }

    .career-pathways p {
        margin: 0 0 5px 0;
        font-size: 13px;
    }

    .career-pathways {
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 10px;
    }

    @media (max-width: 600px) {
      #results {
        padding: 25px;
      }
      .careerCard ul, .related-box ul {
        columns: 1;
      }
      .btn-print, .btn-back {
        width: 100%;
      }
    }

    /* 🖨️ PRINT-FRIENDLY LAYOUT */
    @media print {
    body {
        background: #fff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        margin: 0;
        padding: 0;
        min-height: auto !important;
        height: auto !important;
    }

    #results {
        width: 100%;
        max-width: 100%;
        margin: 0;
        padding: 10px 15px;
        border: none !important;
        box-shadow: none !important;
        page-break-inside: avoid;
        font-size: 11px;
        line-height: 1.2;
    }

    /* RESULT BUBBLE CONTAINER - Ultra compact for print */
    #results > div:first-of-type {
        margin: 5px 0 !important;
        padding: 15px !important;
        border-radius: 10px !important;
    }

    /* Hide navigation buttons and other non-essential elements */
    .nav-link,
    .btn-print,
    .btn-back {
        display: none !important;
    }

    /* Ultra compact title */
    #riasec_title {
        margin-bottom: 10px !important;
        font-size: 14px !important;
    }

    /* Compact scores table */
    #result_3_block {
        margin: 10px 0 !important;
    }

    #result_3_block table {
        font-size: 10px !important;
    }

    #result_3_block td {
        padding: 4px 3px !important;
    }

    /* Compact interest code section */
    #result_4_block {
        margin: 10px 0 !important;
    }

    #myInterest_code {
        font-size: 12px !important;
        margin-bottom: 5px !important;
    }

    #myCodes {
        gap: 6px !important;
        margin-bottom: 10px !important;
    }

    .myCode {
        font-size: 14px !important;
        padding: 6px 12px !important;
        font-weight: bold !important;
    }

    /* Compact analysis text */
    #text_analysis {
        font-size: 11px !important;
        margin: 15px 0 !important;
        padding: 8px 12px !important;
    }

    /* Optimized career grid - 2 columns for print for better text fit */
    .results-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 12px !important;
        margin-top: 15px !important;
    }

    .careerCard, .related-box {
        padding: 8px !important;
        margin: 0 0 15px 0 !important;
    }

    .careerCard h3, .related-box h3 {
        font-size: 13px !important;
        margin-bottom: 6px !important;
        font-weight: bold !important;
    }

    .careerCard p, .related-box p {
        font-size: 11px !important;
        margin-bottom: 6px !important;
        line-height: 1.3 !important;
    }

    .career-content {
        gap: 12px !important;
        margin-top: 6px !important;
    }

    .career-options ul,
    .career-pathways ul {
        font-size: 10px !important;
        padding-left: 15px !important;
        margin: 0 !important;
        line-height: 1.4 !important;
    }

    .career-pathways p {
        font-size: 11px !important;
        margin: 0 0 4px 0 !important;
        font-weight: bold !important;
    }

    .career-pathways {
        padding: 8px !important;
    }

    /* 🩵 Keep colors visible */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* 🩵 My Interest Code colored boxes */
    .myCode {
        background: #1cc2f2 !important;
        color: #fff !important;
    }

    /* 🧾 Full page coverage - fill entire page */
    @page {
        size: A4;
        margin: 0.5cm 0.5cm 0.1cm 0.5cm;
    }

    /* Force single page - prevent any breaks */
    .results-grid,
    .careerCard,
    .related-box,
    #results > div {
        page-break-inside: avoid !important;
    }
    }

    
  </style>
</head>
<body>
  <div id="results">
  <div id="student_info" style="display: flex; justify-content: space-between; align-items: center;">
     <div>
         <b>Student Name:</b> {{ $student->name ?? Auth::user()->name }}
     </div>
     <div>
         <b>Date Taken:</b> {{ $result->created_at->format('F d, Y')  }}
         @if($result->is_retake)
             @if($currentAttempt > 1)
                 ({{ $currentAttempt }}{{ $currentAttempt == 2 ? 'nd' : ($currentAttempt == 3 ? 'rd' : 'th') }} Retake)
             @else
                 (Retake)
             @endif
         @endif
     </div>
  </div>


<hr class="riasec_hr">

    <div id="pathway_title">Which Career Pathway is right for you?</div>
    <hr class="riasec_hr">
    <!-- RESULT BUBBLE CONTAINER -->
    <div style="background: #f8f9fa; border-radius: 20px; padding: 30px; margin: 20px 0; box-shadow: 0 8px 25px rgba(0,0,0,0.1); border: 2px solid #e9ecef;">
        <div id="riasec_title">
            <span>{{ $result->is_retake ? 'LATEST RESULTS OF THE RIASEC TEST' : 'RESULTS OF THE RIASEC TEST' }}</span>
            @if($allResults->count() > 1)
                <div style="display: flex; gap: 8px; margin-left: 20px;">
                    @if(isset($is_admin) && $is_admin)
                        @if($prevResult)
                            <a href="{{ route('admin.student-riasec', [$student->id, $prevResult->id]) }}" class="nav-link">Previous</a>
                        @else
                            <span class="nav-link disabled">Previous</span>
                        @endif
                        @if($nextResult)
                            <a href="{{ route('admin.student-riasec', [$student->id, $nextResult->id]) }}" class="nav-link">Next</a>
                        @else
                            <span class="nav-link disabled">Next</span>
                        @endif
                    @else
                        @if($prevResult)
                            <a href="{{ route('testing.results.riasec-result', $prevResult->id) }}" class="nav-link">Previous</a>
                        @else
                            <span class="nav-link disabled">Previous</span>
                        @endif
                        @if($nextResult)
                            <a href="{{ route('testing.results.riasec-result', $nextResult->id) }}" class="nav-link">Next</a>
                        @else
                            <span class="nav-link disabled">Next</span>
                        @endif
                    @endif
                </div>
            @endif
        </div>

        <!-- SCORES -->
        <div id="result_3_block">
            <p>Your grand total scores from above has been transferred into the appropriate columns below.</p>
            <table>
                <tr>
                    <td class="riasecResult_chars">R</td><td>= Realistic</td>
                    <td>Total:</td><td><b>{{ $scores['R'] ?? 0 }}</b></td>
                </tr>
                <tr>
                    <td class="riasecResult_chars">I</td><td>= Investigative</td>
                    <td>Total:</td><td><b>{{ $scores['I'] ?? 0 }}</b></td>
                </tr>
                <tr>
                    <td class="riasecResult_chars">A</td><td>= Artistic</td>
                    <td>Total:</td><td><b>{{ $scores['A'] ?? 0 }}</b></td>
                </tr>
                <tr>
                    <td class="riasecResult_chars">S</td><td>= Social</td>
                    <td>Total:</td><td><b>{{ $scores['S'] ?? 0 }}</b></td>
                </tr>
                <tr>
                    <td class="riasecResult_chars">E</td><td>= Enterprising</td>
                    <td>Total:</td><td><b>{{ $scores['E'] ?? 0 }}</b></td>
                </tr>
                <tr>
                    <td class="riasecResult_chars">C</td><td>= Conventional</td>
                    <td>Total:</td><td><b>{{ $scores['C'] ?? 0 }}</b></td>
                </tr>
            </table>
        </div>

        <!-- INTEREST CODE -->
        <div id="result_4_block">
            <div id="myInterest_code">MY INTEREST CODE</div>
            <div id="myCodes">
                @foreach($top3 as $code => $value)
                    <div class="myCode notranslate">{{ $code }}</div>
                @endforeach
            </div>
        </div>
    </div>


    <!-- PRINT BUTTON -->
    <div id="result_print">
        <button onclick="window.print()" class="btn-print">
            🖨️ Print Result
        </button>
        @if(isset($is_admin) && $is_admin)
            <a href="{{ route('admin.test-results') }}" class="btn-back">⬅️ Back to Test Results</a>
        @else
            <a href="{{ route('dashboard') }}" class="btn-back">⬅️ Back to Dashboard</a>
        @endif
    </div>

    
    <!-- TEXT ANALYSIS -->
    <div id="text_analysis">
    Based on your selections, you are
        @foreach($top3 as $code => $value)
            <b>{{ $descriptions[$code] ?? $code }}</b>
            (<span style="color:#1CC2F2; font-size:14px; font-weight:bold;">{{ $code }}</span>)
            @if(!$loop->last), @endif
        @endforeach.
    </div>

    <!-- CAREER PATHWAY INFO SECTIONS -->
   <!-- Result Section -->
		<div id="resultSection">
			<h2></h2>
				<div class="results-grid">
					<div class="careerCard {{ $top3->has('R') ? 'highlighted' : 'dimmed' }}">
						<h3>R = Realistic</h3>
						<p>These people are often good at mechanical or athletic jobs. Good career options for Realistic people are:</p>
						<div class="career-content">
							<div class="career-options">
								<ul>
									<li>Agriculture</li><li>Health Assistant</li><li>Computers</li>
									<li>Construction</li><li>Mechanic/Machinist</li><li>Engineering</li><li>Food and Hospitality</li>
								</ul>
							</div>
							<div class="career-pathways">
								<p><strong>Related Pathways:</strong></p>
								<ul>
									<li>Agriculture, Food, and Natural Resources</li>
									<li>Architecture and Construction</li>
									<li>Manufacturing</li>
									<li>Transportation, Distribution, and Logistics</li>
									<li>Health Sciences</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="related-box {{ $top3->has('I') ? 'highlighted' : 'dimmed' }}">
						<h3>I = Investigative</h3>
						<p>These people like to watch, learn, analyze, and solve problems. Good career options for Investigative people are:</p>
						<div class="career-content">
							<div class="career-options">
								<ul>
									<li>Marine Biology</li><li>Engineering</li><li>Chemistry</li>
									<li>Zoology</li><li>Medicine/Surgery</li><li>Consumer Economics</li><li>Psychology</li>
								</ul>
							</div>
							<div class="career-pathways">
								<p><strong>Related Pathways:</strong></p>
								<ul>
									<li>Health Sciences</li>
									<li>Science, Technology, Engineering, and Mathematics</li>
									<li>Agriculture, Food, and Natural Resources</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="careerCard {{ $top3->has('A') ? 'highlighted' : 'dimmed' }}">
						<h3>A = Artistic</h3>
						<p>These people like to work in unstructured situations where they can use their creativity. Good career options for Artistic people are:</p>
						<div class="career-content">
							<div class="career-options">
								<ul>
									<li>Communications</li><li>Cosmetology</li><li>Fine and Performing Arts</li>
									<li>Photography</li><li>Radio and TV</li><li>Interior Design</li><li>Architecture</li>
								</ul>
							</div>
							<div class="career-pathways">
								<p><strong>Related Pathways:</strong></p>
								<ul>
									<li>Arts, Audio/Video Technology, and Communications</li>
									<li>Architecture and Construction</li>
									<li>Hospitality and Tourism</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="related-box {{ $top3->has('S') ? 'highlighted' : 'dimmed' }}">
						<h3>S = Social</h3>
						<p>These people like to work with other people, rather than things. Good career options for Social people are:</p>
						<div class="career-content">
							<div class="career-options">
								<ul>
									<li>Counseling</li><li>Nursing</li><li>Physical Therapy</li>
									<li>Travel</li><li>Advertising</li><li>Public Relations</li><li>Education</li>
								</ul>
							</div>
							<div class="career-pathways">
								<p><strong>Related Pathways:</strong></p>
								<ul>
									<li>Health Sciences and Human Services</li>
									<li>Education</li>
									<li>Law, Government, and Public Safety</li>
									<li>Culinary, Hospitality, and Tourism</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="careerCard {{ $top3->has('E') ? 'highlighted' : 'dimmed' }}">
						<h3>E = Enterprising</h3>
						<p>These people like to work with others and enjoy persuading and performing. Good career options for Enterprising people are:</p>
						<div class="career-content">
							<div class="career-options">
								<ul>
									<li>Fashion Merchandising</li><li>Real Estate</li><li>Marketing/Sales</li>
									<li>Law</li><li>Political Science</li><li>International Trade</li><li>Banking/Finance</li>
								</ul>
							</div>
							<div class="career-pathways">
								<p><strong>Related Pathways:</strong></p>
								<ul>
									<li>Business Management and Administration</li>
									<li>Finance</li>
									<li>Marketing</li>
									<li>Law, Government, and Public Safety</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="related-box {{ $top3->has('C') ? 'highlighted' : 'dimmed' }}">
						<h3>C = Conventional</h3>
						<p>These people are very detail-oriented, organized, and like to work with data. Good career options for Conventional people are:</p>
						<div class="career-content">
							<div class="career-options">
								<ul>
									<li>Accounting</li><li>Court Reporting</li><li>Insurance</li>
									<li>Administration</li><li>Medical Records</li><li>Banking</li><li>Data Processing</li>
								</ul>
							</div>
							<div class="career-pathways">
								<p><strong>Related Pathways:</strong></p>
								<ul>
									<li>Business Management and Administration</li>
									<li>Finance</li>
									<li>Information Technology</li>
									<li>Health Sciences</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</div>
</body>
</html>
