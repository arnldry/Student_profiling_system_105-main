<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Life Values Inventory Results</title>

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

    .summary-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .summary-table td {
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
        font-size: 18px;
    }

    /* VALUES PROFILE table - smaller font for print fit */
    #values_profile_table td {
        font-size: 8px !important;
        line-height: 1.2 !important;
        padding: 2px 4px !important;
    }

    .summary-table tr:nth-child(even) {
        background: #f8f9fa;
    }

    .summary-table b {
        color: #004d7a;
        font-size: 18px;
    }

    .summary-table small {
        color: #666;
        font-size: 15px;
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

    /* Show all content in print - match screen display */

    /* RESULT BUBBLE CONTAINER - Ultra compact for print */
    #results > div:first-of-type {
        margin: 5px 0 !important;
        padding: 15px !important;
        border-radius: 10px !important;
    }

    /* Hide navigation buttons and print button in print */
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

    /* � Full page coverage - fill entire page */
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

       <div id="pathway_title">Life Values Inventory Results</div>
       <hr class="riasec_hr">
   {{-- PAGE 1: SCORING SUMMARY --}}
   <h3 style="color: #004d7a; font-weight: 700; margin-top: 40px; margin-bottom: 20px; text-align: center; padding-bottom: 10px; font-size: 20px;">SCORING SUMMARY</h3>
   <div id="riasec_title" style="display: flex; justify-content: space-between; align-items: center; font-size: 18px; color: #555; margin-bottom: 20px;">
       <span>{{ $is_latest ? 'LATEST RESULTS OF THE LIFE VALUES TEST' : 'RESULTS OF THE LIFE VALUES TEST' }}</span>
       @if($allResults->count() > 1)
           <div style="display: flex; gap: 8px;">
               @if(isset($is_admin) && $is_admin)
                   @if($prevResult)
                       <a href="{{ route('admin.student-life-values', [$student->id, $prevResult->id]) }}" class="nav-link">Previous</a>
                   @else
                       <span class="nav-link disabled">Previous</span>
                   @endif
                   @if($nextResult)
                       <a href="{{ route('admin.student-life-values', [$student->id, $nextResult->id]) }}" class="nav-link">Next</a>
                   @else
                       <span class="nav-link disabled">Next</span>
                   @endif
               @else
                   @if($prevResult)
                       <a href="{{ route('testing.results.life-values-results', $prevResult->id) }}" class="nav-link">Previous</a>
                   @else
                       <span class="nav-link disabled">Previous</span>
                   @endif
                   @if($nextResult)
                       <a href="{{ route('testing.results.life-values-results', $nextResult->id) }}" class="nav-link">Next</a>
                   @else
                       <span class="nav-link disabled">Next</span>
                   @endif
               @endif
           </div>
       @endif
   </div>
   <p style="color: #444; text-align: center; font-size: 15px; margin-bottom: 25px; line-height: 1.6;">Add up the ratings from pages 2, 3, and 4 for each question. Record the total scores for
   each letter below. This will give you your scores for the 14 major life values identified by this inventory.</p>

    <div class="summary-table-container">
      <table class="summary-table" id="values_profile_table">
        @php
            $valueMap = [
                'A' => 'Questions 1 + 15 + 29',
                'B' => 'Questions 2 + 16 + 30',
                'C' => 'Questions 3 + 17 + 31',
                'D' => 'Questions 4 + 18 + 32',
                'E' => 'Questions 5 + 19 + 33',
                'F' => 'Questions 6 + 20 + 34',
                'G' => 'Questions 7 + 21 + 35',
                'H' => 'Questions 8 + 22 + 36',
                'I' => 'Questions 9 + 23 + 37',
                'J' => 'Questions 10 + 24 + 38',
                'K' => 'Questions 11 + 25 + 39',
                'L' => 'Questions 12 + 26 + 40',
                'M' => 'Questions 13 + 27 + 41',
                'N' => 'Questions 14 + 28 + 42',
            ];
            $letters = array_keys($valueMap);
        @endphp

        @for($i = 0; $i < count($letters); $i+=2)
        <tr>
            <td>
                <b>{{ $letters[$i] }}:</b> <span style="font-size: 15px;">total = {{ $scores[$letters[$i]] ?? '0' }}</span><br>
                <small>{{ $valueMap[$letters[$i]] }}</small>
            </td>
            @if(isset($letters[$i+1]))
                <td>
                    <b>{{ $letters[$i+1] }}:</b> <span style="font-size: 15px;">total = {{ $scores[$letters[$i+1]] ?? '0' }}</span><br>
                    <small>{{ $valueMap[$letters[$i+1]] }}</small>
                </td>
            @else
                <td></td>
            @endif
        </tr>
        @endfor
      </table>
    </div>

    {{-- PAGE 2: VALUES PROFILE --}}
    <h3 style="color: #004d7a; font-weight: 700; margin-top: 40px; margin-bottom: 20px; text-align: center; border-bottom: 3px solid #1cc2f2; padding-bottom: 10px; font-size: 20px;">VALUES PROFILE</h3>
    <p style="color: #444; text-align: center; font-size: 15px; margin-bottom: 25px; line-height: 1.6;">LIFE VALUES INVENTORY VALUES PROFILE</p>
    <table class="summary-table">
      @php
          $valueDescriptions = [
              'A' => ['name'=>'ACHIEVEMENT', 'desc'=>'It is important to challenge yourself and work hard to improve.'],
              'B' => ['name'=>'BELONGING', 'desc'=>'It is important to be accepted by others and to feel included.'],
              'C' => ['name'=>'CONCERN FOR THE ENVIRONMENT', 'desc'=>'It is important to protect and preserve the environment.'],
              'D' => ['name'=>'CONCERN FOR OTHERS', 'desc'=>'The well-being of others is important.'],
              'E' => ['name'=>'CREATIVITY', 'desc'=>'It is important to have new ideas or to create new things.'],
              'F' => ['name'=>'FINANCIAL PROSPERITY', 'desc'=>'It is important to be successful at making money or buying property.'],
              'G' => ['name'=>'HEALTH AND ACTIVITY', 'desc'=>'It is important to be healthy and physically active.'],
              'H' => ['name'=>'HUMILITY', 'desc'=>'It is important to be humble and modest about your accomplishments.'],
              'I' => ['name'=>'INDEPENDENCE', 'desc'=>'It is important to make your own decisions and do things your way.'],
              'J' => ['name'=>'LOYALTY TO FAMILY OR GROUP', 'desc'=>'It is important to follow the traditions and expectations of your family or group.'],
              'K' => ['name'=>'PRIVACY', 'desc'=>'It is important to have time alone.'],
              'L' => ['name'=>'RESPONSIBILITY', 'desc'=>'It is important to be dependable and trustworthy.'],
              'M' => ['name'=>'SCIENTIFIC UNDERSTANDING', 'desc'=>'It is important to use scientific principles to understand and solve problems.'],
              'N' => ['name'=>'SPIRITUALITY', 'desc'=>'It is important to have spiritual beliefs and to believe that you are part of something greater than yourself.'],
          ];
      @endphp

      @foreach($valueDescriptions as $letter => $data)
          <tr>
              <td><b>{{ $letter }} {{ $data['name'] }}</b></td>
              <td>{{ $scores[$letter] ?? '0' }}</td>
          </tr>
          <tr>
              <td colspan="2">{{ $data['desc'] }}</td>
          </tr>
      @endforeach
    </table>

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
  </div>
</body>
</html>
