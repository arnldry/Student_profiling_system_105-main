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
      text-align: center;
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

    .summary-table-container {
      margin-bottom: 40px;
    }

    .summary-table {
      width: 100%;
      border-collapse: collapse;
    }

    .summary-table td {
      padding: 10px 8px;
      font-size: 15px;
      border-bottom: 1px solid #eee;
    }

    .summary-table tr:nth-child(even) {
      background-color: #f9fcff;
    }

    .summary-table td b {
      color: #004d7a;
      font-size: 16px;
      margin-right: 5px;
    }

    .summary-table small {
      color: #777;
      font-size: 13px;
      display: block;
      margin-top: 4px;
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

    @media (max-width: 600px) {
      #results {
        padding: 25px;
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
    }

    #results {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
        padding: 20px 40px;
        border: none !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }

    /* Hide buttons */
    .btn-print,
    .btn-back {
        display: none !important;
    }

    /* 🩵 Keep colors visible */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* 🧾 Fit to one clean page */
    @page {
        size: A4;
        margin: 1cm;
    }

    /* Optional: shrink font slightly to fit all content neatly */
    body, #results {
        font-size: 14px;
        line-height: 1.5;
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
          <b>Date Taken:</b> {{ $result->created_at->format('F d, Y') }}
       

      </div>
  </div>


    <hr class="riasec_hr">
    <div id="pathway_title">LIFE VALUES INVENTORY</div>
    


    {{-- PAGE 1: SCORING SUMMARY --}}
    <h3 style="color: #004d7a; font-weight: 700; margin-top: 40px; margin-bottom: 20px; text-align: center; padding-bottom: 10px; font-size: 20px;">SCORING SUMMARY</h3>
    <p style="color: #444; text-align: center; font-size: 15px; margin-bottom: 25px; line-height: 1.6;">Add up the ratings from pages 2, 3, and 4 for each question. Record the total scores for
    each letter below. This will give you your scores for the 14 major life values identified by this inventory.</p>

    <div class="summary-table-container">
      <table class="summary-table">
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
            <td><b>{{ $letters[$i] }}:</b> {{ $scores[$letters[$i]] ?? '0' }}<small>{{ $valueMap[$letters[$i]] }}</small></td>
            @if(isset($letters[$i+1]))
                <td><b>{{ $letters[$i+1] }}:</b> {{ $scores[$letters[$i+1]] ?? '0' }}<small>{{ $valueMap[$letters[$i+1]] }}</small></td>
            @else
                <td></td>
            @endif
        </tr>
        @endfor
      </table>
    </div>

    <div style="page-break-before: always;"></div>

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
        <a href="{{ route('student.testingdash') }}" class="btn-back">⬅️ Back to Dashboard</a>
    </div>
  </div>
</body>
</html>
