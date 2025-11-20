<!DOCTYPE html>
<html>
<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>View Additional Information</title>

    <!-- Site favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="/vendors/images/logo-ocnhs.png"/>

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/vendors/styles/core.css"/>
    <link rel="stylesheet" type="text/css" href="/vendors/styles/icon-font.min.css"/>
    <link rel="stylesheet" type="text/css" href="/src/plugins/datatables/css/dataTables.bootstrap4.min.css"/>
    <link rel="stylesheet" type="text/css" href="/src/plugins/datatables/css/responsive.bootstrap4.min.css"/>
    <link rel="stylesheet" type="text/css" href="/vendors/styles/style.css"/>

    <style>
        .record-form-container {
            max-width: 100%;
            margin: 0 auto;
        }

        .record-form {
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            font-family: 'Arial', sans-serif;
            color: #000;
        }

        .form-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .form-header .logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .header-text {
            text-align: center;
            line-height: 1.2;
        }

        .header-text h3, .header-text h5 {
            margin: 2px 0;
            font-size: 16px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .info-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: top;
        }

        .section-title {
            text-align: center;
            font-weight: bold;
            background: #f0f0f0;
        }

        .rules, .commitment {
            border: 1px solid #000;
            padding: 8px;
            margin-top: 10px;
        }

        .rules h5, .commitment h5 {
            text-align: center;
            margin-bottom: 5px;
        }

        .rules ol {
            margin: 0;
            padding-left: 20px;
            font-size: 12px;
        }

        .commitment p {
            font-size: 12px;
            text-align: justify;
        }

        .signature {
            margin-top: 25px;
            text-align: right;
        }

        .sig-label {
            font-size: 12px;
        }

        @media print {
            .record-form {
                padding: 10px 15px;
            }
            .info-table {
                font-size: 11px;
            }
            .info-table td {
                padding: 3px 4px;
            }
        }

        @media (max-width: 768px) {
            .form-header {
                flex-direction: column;
                text-align: center;
            }
            .form-header .logo {
                width: 50px;
                height: 50px;
                margin-bottom: 10px;
            }
            .info-table {
                font-size: 11px;
            }
            .info-table td {
                padding: 3px 4px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        @include('layouts.navbar.student.navbar')
    </div>
    <div class="left-side-bar">
        @include('layouts.sidebar.student.sidebar')
    </div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <div class="record-form-container">
                    @php
                        // Format the student name like in admin view
                        $learnerFullName = '-';
                        if ($user->name) {
                            $parts = explode(' ', trim($user->name));
                            if (count($parts) >= 3) {
                                $lastName = array_pop($parts);
                                $firstName = $parts[0];
                                $middleName = implode(' ', array_slice($parts, 1));
                                $learnerFullName = "{$lastName}, {$firstName} {$middleName}";
                            } elseif (count($parts) === 2) {
                                $learnerFullName = "{$parts[1]}, {$parts[0]}";
                            } else {
                                $learnerFullName = $user->name;
                            }
                        }

                        // Format Birthday and Compute Age
                        $formattedBirthday = '-';
                        $formattedAge = '-';
                        if ($info->birthday) {
                            $birthDate = \Carbon\Carbon::parse($info->birthday);
                            $formattedBirthday = $birthDate->format('M j, Y');
                            $today = \Carbon\Carbon::now();
                            $age = $today->diffInYears($birthDate);
                            $formattedAge = "{$age} yrs old";
                        }
                    @endphp

                    <!-- Front Side Content -->
                    <div class="record-form front-side">
                        <div class="form-header">
                            <img src="/vendors/images/logo-ocnhs.png" class="logo">
                            <div class="header-text">
                                <h5>OLONGAPO CITY NATIONAL HIGH SCHOOL</h5>
                                <h3>Guidance & Counseling Unit</h3>
                            </div>
                            <img src="/vendors/images/logo-ocnhs-2.png" class="logo">
                        </div>

                        <table class="info-table">
                            <tr>
                                <td colspan="12" class="section-title" style="position: relative; text-align: center;">
                                    <span style="position: absolute; left: 5px;">GCU-F1</span>
                                    <span>LEARNER'S INDIVIDUAL INVENTORY RECORD</span>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="3">School Year: {{ $info->school_year_name ?: '-' }}</td>
                                <td colspan="3">Curriculum/Program: {{ $info->curriculum ?: '-' }}</td>
                                <td colspan="4">Grade & Section: {{ $info->grade ?: '-' }}/{{ $info->section ?: '-' }}</td>
                                <td colspan="2">Sex: {{ $info->sex ?: '-' }}</td>
                            </tr>

                            <tr>
                                <td colspan="1" rowspan="2" class="section-title">LEARNER'S NAME</td>
                                <td colspan="5">{{ $learnerFullName }}</td>
                                <td colspan="2">Mode of Living:</td>
                                <td colspan="4">{{ is_array($info->living_mode) ? implode(', ', $info->living_mode) : ($info->living_mode ?: '-') }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"> <span>Family Name</span></td>
                                <td colspan="1">  <span>First Name</span>             </td>
                                <td colspan="2" > <span>Middle Name</span></td>
                                <td colspan="2">Disability(if any):</td><td colspan="3">{{ $info->disability ?: 'None' }}</td>
                            </tr>


                            <tr>
                                <td colspan="2">Complete Address:</td><td colspan="10">{{ $info->address ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Mobile Number:</td><td colspan="4">{{ $info->contact_number ?: '-' }}</td>
                                <td colspan="2">FB/Messenger:</td>
                                <td colspan="4">{{ $info->fb_messenger ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Birthday & Age:</td><td colspan="2">{{ $formattedBirthday }} ({{ $formattedAge }})</td>
                                <td colspan="1">Religion:</td><td colspan="2">{{ $info->religion ?: '-' }}</td>
                                <td colspan="1">Nationality:</td>
                                <td colspan="4">{{ $info->nationality ?: '-' }}</td>
                            </tr>

                            <tr>
                                <td class="section-title" colspan="2">Father's Name</td><td colspan="4">{{ $info->father_name ?: '-' }}</td>
                                <td colspan="1">Age:</td><td colspan="5">{{ $info->father_age ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Occupation/Work:</td><td colspan="4">{{ $info->father_occupation ?: 'N/A' }}</td>
                                <td colspan="2">Mobile Number:</td><td colspan="4">{{ $info->father_contact ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">FB/Messenger:</td><td colspan="4">{{ $info->father_fb ?: 'N/A' }}</td>
                                <td colspan="2">Place of Work:</td><td colspan="4">{{ $info->father_place_work ?: 'N/A' }}</td>
                            </tr>

                            <tr>
                                <td class="section-title" colspan="2">Mother's Name</td><td colspan="4">{{ $info->mother_name ?: '-' }}</td>
                                <td colspan="1">Age:</td><td colspan="5">{{ $info->mother_age ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Occupation/Work:</td><td colspan="4">{{ $info->mother_occupation ?: 'N/A' }}</td>
                                <td colspan="2">Mobile Number:</td><td colspan="4">{{ $info->mother_contact ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">FB/Messenger:</td><td colspan="4">{{ $info->mother_fb ?: 'N/A' }}</td>
                                <td colspan="2">Place of Work:</td><td colspan="4">{{ $info->mother_place_work ?: 'N/A' }}</td>
                            </tr>

                            <tr>
                                <td class="section-title" colspan="2">Guardian's Name</td><td colspan="4">{{ $info->guardian_name ?: '-' }}</td>
                                <td colspan="1">Age:</td><td colspan="5">{{ $info->guardian_age ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Occupation/Work:</td><td colspan="4">{{ $info->guardian_occupation ?: 'N/A' }}</td>
                                <td colspan="2">Mobile Number:</td><td colspan="4">{{ $info->guardian_contact ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">FB/Messenger:</td><td colspan="4">{{ $info->guardian_fb ?: 'N/A' }}</td>
                                <td colspan="2">Place of Work:</td><td colspan="4">{{ $info->guardian_place_work ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Relationship:</td><td colspan="10">{{ $info->guardian_relationship ?: 'N/A' }}</td>
                            </tr>
                        </table>

                        <!-- School Rules and Commitment -->
                        <table class="info-table">
                            <tr>
                                <td style="width: 85%; vertical-align: top;">
                                    <p style="text-align: center; font-size: 12px;"><strong>MGA ALITUNTUNIN DAPAT SUNDIN NG MAG-AARAL NG OCNHS</strong></p>
                                    <ol style="text-align: left; padding-left: 10px; margin: 0; font-size: 11px; list-style-position: inside;">
                                        <li>Maging responsable, palaging dumalo sa takdang araw at oras ng pag-aaral, 'wag lumiban/manhuli/umabs sa klase.</li>
                                        <li>Aktibong makilahok sa buong panahon ng pag-aaral, kumilos ng maayos at nang may tamang pag-uugali.</li>
                                        <li>Maging mabuti at magalang sa lahat ng panahon; igalang ang mga guro, mga kawani ng paaralan at kapwa mag-aaral.</li>
                                        <li>Bawal ang pagmumura, pakikipag-away, rambulan, maglakal, vandalismo, panggugulo at paninira sa paaralan.</li>
                                        <li>Ipinagbabawal din ang pagdala, paggamit, pagbili o pagbenta ng sigarilyo, vape, alak, droga, baril, banibalnan, kutsilyo o anumang bagay na nakasasakit, pornograpiya at mga katulad ng mga ito.</li>
                                        <li>Sumunod sa pangkalusugang protocol ng pamahalaan, lalo na sa panahon ng pisikal (face-to-face) na pagpasok sa paaralan (face mask, distancing, handwashing, sanitation), na laging may suot na school ID at tamang uniporme o kasuotan, at magkaroon ng maayos (simpleng) ayos tulad sa babae, "student/crew cut" sa lalaki) at bawal din ang may kulay ang buhok, may make-up, may gata sa kilay at mga accessories. Bawal sa mga lalaki ang magsuot ng hikaw. Maaaring magsuot ang babae ng isang pares at simpleng hikaw lamang sa magkabilang tainga na kaaya-aya.</li>
                                        <li>Ingatan ang pinahiram na libro, module o anuman gamit ng eskwelahan at isauli din ang mga ito sa takdang panahon.</li>
                                        <li>Huwag sumama/sumali sa mga masasamang grupo o gang at sa mga ilegal na samahan na wala sa paaralan.</li>
                                        <li>Makipag-ugnayan sa inyong guro, tagapayo/adviser o school counselor sa inyong mga katanungan.</li>
                                        <li>Aiming ipatupad ang mga patakaran ng Olongapo City National High School (OCNHS) o ang kurikulum sa pag-aaral at interbensiyon ayon sa mga patakarang pampaaralan at mga batas ng DepEd na:
                                            <ol type="a" style="padding-left: 20px;">
                                                <li>Ipatatawag ang magulang/guardian para sa panayam;</li>
                                                <li>Mabigyan ng karampatang aksyon, interbensiyon o mga paraan mula sa kurikulum/programa; o</li>
                                                <li>Isangguni sa school counselor para sa counseling o coaching services.</li>
                                            </ol>
                                        </li>
                                    </ol>
                                </td>
                                <td style="width: 15%; vertical-align: top;">
                                    <p style="text-align: center; font-size: 12px; "><strong>KOMITMENT SA PAARALAN</strong></p>
                                    <p style="font-size: 10px; text-align: center;">
                                        Akin pong ipinapahayag sa OCNHS sa pamamagitan ng aking pirma ang
                                        aking taos pusong komitment na sumunod sa mga patakaran at alituntuning itinakda
                                        sa akin ayon sa Mga Alituntuning Dapat Sundin ng Mag-aaral ng OCNHS at sa mga batas
                                        ng DepEd ngayong taong panuruan. Kasihan nawa ako ng Maykapal.
                                    </p>
                                    <div style="margin-top: 40px; font-size: 14px; text-align: center; border-top: 1px solid #000; padding-top: 5px;">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="12" style="text-align: left;">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                                        <span style="white-space: nowrap;">Nilagdaan ngayong araw:</span>
                                        <div style="flex-grow: 1; display: flex; justify-content: space-around; margin-left: 10px;">
                                            <div style="text-align: center; flex-basis: 45%;">
                                                <div style="height: 10px;"></div>
                                                <div style="font-size: 14px; margin-bottom: 1px;">{{ $info->current_date_formatted }}</div>
                                                <div style="border-bottom: 1px solid #000; height: 1em;"></div>
                                                <div style="margin-top: 2px;">(Petsa)</div>
                                            </div>
                                            <div style="text-align: center; flex-basis: 45%;">
                                                <div style="height: 5px;"></div>
                                                <div style="margin-bottom: 10px; font-size: 11px;">
                                                    <strong>Agreement Status:</strong>
                                                    <span style="margin-left: 10px;">Student Agreements: {{ $info->student_agreement_1 && $info->student_agreement_2 ? '✓ Accepted' : '✗ Not Accepted' }}</span>
                                                </div>
                                                <div style="border-bottom: 1px solid #000; height: 1em;"></div>
                                                <div style="margin-top: 2px;">Lagda ng mag-aaral</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Back Side Content (Parent Duties) -->
                    <div class="record-form back-side">
                        <table class="info-table">
                            <tr colspan="12">
                                <td style="width: 85%; vertical-align: top;">
                                  <div style="text-align: center; margin-bottom: 12px;">
                                    <strong>MGA TUNGKULIN NG MAGULANG O GUARDIAN</strong><br>(Alinsunod sa Batas Pambansang Blg. 232 at ng DepEd)</div>
                                    <ol style="text-align: left; padding-left: 20px; margin: 0; font-size: 10px; list-style-position: inside;">
                                        <li>Maging responsable at pumasok sa takdang araw at oras ng pag-aaral. Hindi umalis, lumiban, o huli sa klase nang walang matibay na dahilan.</li>
                                        <li>Maging aktibo at makilahok sa klase nang may tamang pag-uugali at asal.</li>
                                        <li>Igalang ang mga guro, kawani, at kapwa mag-aaral.</li>
                                        <li>Iwasan ang pagmumura, pananakit, pambubully, paninira ng ari-arian, paninira ng pagkatao, at iba pang masamang asal.</li>
                                        <li>Ipinagbabawal ang pagdala, paggamit, pagbili, o pagbebenta ng sigarilyo, vape, alak, droga, at sandata (baril, toy gun, kutsilyo, bomba, at iba pang mapanganib na bagay), at pornograpiko o malalaswang materyales.</li>
                                        <li>Sumunod sa mga health protocols: pagsusuot ng face mask, social distancing, paghuhugas ng kamay, at sanitation. Magsuot ng ID at tamang uniporme. Panatilihing maayos ang buhok (nakatali/nakaklip para sa babae, student cut/crew cut para sa lalaki), walang kulay, walang makeup, walang eyebrow slit, walang accessories (hindi pinapayagan ang lalaki na magsuot ng hikaw, pinapayagan ang babae na isang pares lamang na simpleng hikaw).</li>
                                        <li>Ingatan at iingatan ang mga hiniram na libro, module, o ari-arian ng paaralan at ibalik sa takdang oras.</li>
                                        <li>Huwag sumali sa masasamang samahan o gang. Sumali lamang sa mga lehitimong organisasyon ng paaralan.</li>
                                        <li>Makipag-ugnayan agad sa mga guro, adviser, o school counselor kung may mga katanungan o problema.</li>
                                        <li>Lahat ng paglabag ay may kaukulang kaparusahan: mula sa pagtawag sa magulang/guardian, iba't ibang interbensyon mula sa curriculum/program, o pagtulong sa school counselor para sa counseling o coaching services.</li>
                                    </ol>
                                    <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                                        <span style="white-space: nowrap;">Nilagdaan ngayong araw:</span>
                                        <div style="flex-grow: 1; display: flex; justify-content: space-around; margin-left: 10px;">
                                            <div style="text-align: center; flex-basis: 45%;">
                                                <div style="height: 10px;"></div>
                                                <div style="font-size: 14px; margin-bottom: 1px;">{{ $info->current_date_formatted }}</div>
                                                <div style="border-bottom: 1px solid #000; height: 1em;"></div>
                                                <div style="margin-top: 2px;">(Petsa)</div>
                                            </div>
                                            <div style="text-align: center; flex-basis: 45%;">
                                                <div style="height: 5px;"></div>
                                                <div style="margin-bottom: 10px; font-size: 11px;">
                                                    <strong>Agreement Status:</strong>
                                                    <span style="margin-left: 20px;">Parent Agreements: {{ $info->parent_agreement_1 && $info->parent_agreement_2 ? '✓ Accepted' : '✗ Not Accepted' }}</span>
                                                </div>
                                                <div style="border-bottom: 1px solid #000; height: 1em;"></div>
                                                <div style="margin-top: 2px;">Lagda ng mag-aaral</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="width: 15%; vertical-align: top; padding: 10px;">
                                    <div style="text-align: center;">
                                        <p style="margin: 0; text-align: center; font-size: 12px;"><strong>KOMITMENT SA PAARALAN</strong></p>
                                    </div>
                                    <p style="font-size: 10px; text-align: center; margin: 0;">
                                        Akin pong ipinapahayag sa OCNHS sa pamamagitan ng aking pirma
                                        sa ibaba ang aking taos pusong komitment na sumunod
                                        sa mga patakarang itinakda sa akin ayon sa Mga Alituntuning Dapat
                                        Sundin ng Mag-aaral ng OCNHS at sa mga batas ng DepEd ngayong taong panuruan.
                                        Kasihan nawa ako ng Maykapal.
                                    </p>
                                    <div style="margin-top: 40px; font-size: 14px; text-align: center; border-top: 1px solid #000; padding-top: 5px;">
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <table class="info-table">
                            <tr>
                                <td colspan="12">COUNSELOR'S NOTES:________________________________
                                ____________________________________________________________________
                                    <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Print Button -->
                <div class="text-center mt-4 mb-4">
                    <button type="button" class="btn btn-primary btn-lg" onclick="printStudentProfile()">
                        <i class="dw dw-print"></i> Print Profile
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/vendors/scripts/core.js"></script>
    <script src="/vendors/scripts/script.min.js"></script>
    <script src="/vendors/scripts/process.js"></script>
    <script src="/vendors/scripts/layout-settings.js"></script>
    <script src="/src/plugins/apexcharts/apexcharts.min.js"></script>
    <script src="/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <script src="/vendors/scripts/dashboard.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function printStudentProfile() {
            const frontContent = document.querySelector('.front-side').innerHTML;
            const backContent = document.querySelector('.back-side').innerHTML;
            const studentName = '{{ $learnerFullName }}';

            // Create a hidden iframe for printing
            const iframe = document.createElement('iframe');
            iframe.style.position = 'absolute';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = 'none';
            iframe.style.opacity = '0';
            document.body.appendChild(iframe);

            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

            // Write the print content to the iframe
            iframeDoc.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${studentName}_Student_Profile</title>
                    <style>
                        @media print {
                            @page {
                                size: letter;
                                margin: 0.25in;
                            }
                            body {
                                margin: 0;
                                padding: 0;
                                -webkit-print-color-adjust: exact !important;
                                print-color-adjust: exact !important;
                            }
                            .print-page {
                                page-break-after: always;
                            }
                            .print-page:last-child {
                                page-break-after: auto;
                            }
                        }
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            color: #000;
                        }
                        .print-container {
                            width: 100%;
                        }
                        .print-page {
                            margin-bottom: 20px;
                        }
                        .print-page:last-child {
                            margin-bottom: 0;
                        }
                        .form-header {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            border-bottom: 2px solid #000;
                            padding-bottom: 5px;
                            margin-bottom: 8px;
                        }
                        .form-header .logo {
                            width: 60px;
                            height: 60px;
                            object-fit: contain;
                        }
                        .header-text {
                            text-align: center;
                            line-height: 1.0;
                        }
                        .header-text h3, .header-text h4, .header-text h5, .header-text h6 {
                            margin: 1px 0;
                        }
                        .info-table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 3px;
                            font-size: 10px;
                        }
                        .info-table td {
                            border: 1px solid #000;
                            padding: 2px 3px;
                            vertical-align: top;
                        }
                        .section-title {
                            text-align: center;
                            font-weight: bold;
                            background-color: #f0f0f0 !important;
                        }
                        h5 {
                            text-align: center;
                            margin: 8px 0;
                            font-size: 11px;
                        }
                        ol {
                            padding-left: 20px;
                            margin: 0;
                            font-size: 9px;
                        }
                        p {
                           font-size: 9px;
                           text-align: justify;
                           margin: 0;
                        }
                        .signature {
                            margin-top: 20px;
                            text-align: right;
                        }
                        .sig-label {
                            font-size: 10px;
                        }
                    </style>
                </head>
                <body>
                    <div class="print-container">
                        <div class="print-page">
                            <div class="record-form">
                                ${frontContent}
                            </div>
                        </div>
                        <div class="print-page">
                            <div class="record-form">
                                ${backContent}
                            </div>
                        </div>
                    </div>
                </body>
                </html>
            `);

            iframeDoc.close();

            // Wait for content to load then trigger print
            iframe.onload = function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();

                // Remove the iframe after printing
                setTimeout(() => {
                    document.body.removeChild(iframe);
                }, 100);
            };
        }
    </script>
</body>
</html>