<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Archived Student Data</title>

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

        <style>
            .student-table-wrapper {
                margin-top: 20px;
            }
            .btn.active-year {
                background-color: #007bff;
                color: #fff;
                border-color: #007bff;
            }
        </style>
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

            <!-- Archived School Years Buttons -->
            <div class="title pb-20">
                <h2 class="h3 mb-0">Archived Student Data by School Year</h2>
            </div>
            <div class="card-box mb-30">
                <div class="pb-20 p-3">
                    @forelse($archivedSchoolYears as $sy)
                        <button class="btn btn-outline-primary btn-lg m-2 show-students" data-id="{{ $sy->id }}">
                            {{ $sy->school_year }}
                        </button>
                    @empty
                        <p class="text-center text-muted">No archived school years found.</p>
                    @endforelse
                </div>
            </div>

            <!-- Archived Students Table -->
            <div class="card-box mb-30 student-table-wrapper" style="display:none;">
                <div class="pd-20">
                    <h4 class="text-blue h4">Archived Students</h4>
                    <p class="mb-0 text-muted">List of students under the selected school year</p>
                </div>

                <div class="pb-20 px-3">
                    <table class="data-table table stripe hover nowrap" id="archivedStudentsTable" style="width:100%;">
                        <thead>
                            <tr>
                                <th>LRN</th>
                                <th>Student Name</th>
                                <th>Grade Level</th>
                                <th>Section</th>
                                <th>Curriculum</th>
                                <th class>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- JS -->
    <script src="/vendors/scripts/core.js"></script>
    <script src="/vendors/scripts/script.min.js"></script>
    <script src="/vendors/scripts/process.js"></script>
    <script src="/vendors/scripts/layout-settings.js"></script>
    <script src="/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            let table = $('#archivedStudentsTable').DataTable({
                responsive: true,
                columns: [
                    { data: 'lrn' },
                    { data: 'name' },
                    { data: 'grade' },
                    { data: 'section' },
                    { data: 'curriculum' },
                    { data: 'action', orderable: false, searchable: false }
                ]
            });

            let currentActiveYear = null;

            $('.show-students').click(function () {
                let schoolYearId = $(this).data('id');

                $('.show-students').removeClass('active-year');
                $(this).addClass('active-year');

                if(currentActiveYear === schoolYearId){
                    $('.student-table-wrapper').slideUp();
                    currentActiveYear = null;
                    return;
                }
                currentActiveYear = schoolYearId;
                $('.student-table-wrapper').slideDown();

                $.ajax({
                    url: `/admin/archived-students/school-year/${schoolYearId}`,
                    type: 'GET',
                    success: function (data) {
                        table.clear();
                        if(data.length === 0){
                            Swal.fire('No students found', 'There are no archived students for this school year.', 'info');
                            return;
                        }

                        data.forEach(student => {
                            table.row.add({
                                lrn: student.lrn,
                                name: student.user ? student.user.name : 'N/A',
                                grade: student.grade,
                                section: student.section,
                                curriculum: student.curriculum,
                                action: `<button class="btn btn-info btn-sm" onclick="viewArchivedInfo(${student.id})">
                                            <i class="dw dw-eye"></i> View
                                         </button>`
                            });
                        });

                        table.draw();
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to fetch archived students', 'error');
                    }
                });
            });
        });

        function viewArchivedInfo(studentId, studentName) {

              // Format the student name
              let learnerFullName = '-';
            if (studentName) {
                let parts = studentName.trim().split(' ');
                if (parts.length >= 3) {
                    let lastName = parts[parts.length - 1];
                    let firstName = parts[0];
                    let middleName = parts.slice(1, parts.length - 1).join(' ');
                    learnerFullName = `${lastName}, ${firstName} ${middleName}`;
                } else if (parts.length === 2) {
                    learnerFullName = `${parts[1]}, ${parts[0]}`; 
                } else {
                    learnerFullName = studentName; 
                }
            }


            fetch(`/admin/archived-students/${studentId}`)
                .then(response => response.json())
               .then(data => {
                if (data.error) {
                    Swal.fire({
                        icon: 'info',
                        title: 'No Information',
                        text: data.error,
                        confirmButtonText: 'OK'
                    });
                } else {
                    // Format Birthday and Compute Age
                    let formattedBirthday = '-';
                    let formattedAge = '-';
                    
                    if (data.birthday) {
                        const birthDate = new Date(data.birthday);
                        const options = { year: 'numeric', month: 'short', day: 'numeric' };
                        formattedBirthday = birthDate.toLocaleDateString('en-US', options); 

                        // Compute age
                        const today = new Date();
                        let age = today.getFullYear() - birthDate.getFullYear();
                        const m = today.getMonth() - birthDate.getMonth();
                        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                            age--;
                        }
                        formattedAge = `${age} yrs old`;
                    }

                    // Front side - Learner's Individual Inventory Record
                    const frontContent = `
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
                                <td colspan="3">School Year: ${data.school_year_name || '-'}</td>
                                <td colspan="3">Curriculum/Program: ${data.curriculum || '-'}</td>
                                <td colspan="4">Grade & Section: ${data.grade || '-'}/${data.section || '-'}</td>
                                <td colspan="2">Sex: ${data.sex || '-'}</td>
                            </tr>

                            <tr>
                                <td colspan="1" rowspan="2" class="section-title">LEARNER'S NAME</td>
                                <td colspan="5">${learnerFullName}</td>
                                <td colspan="2">Mode of Living:</td>
                                <td colspan="4">${data.living_mode ? data.living_mode.join(', ') : '-'}</td>
                            </tr>
                            <tr>
                            <td colspan="2"> <span>Family Name</span></td>
                            <td colspan="1">  <span>First Name</span>             </td>
                            <td colspan="2" > <span>Middle Name</span></td>
                
                                <td colspan="2">Disability(if any):</td><td colspan="3"></td>
                            </tr>

                            <tr>
                                <td colspan="2">Complete Address:</td><td colspan="10">${data.address || '-'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Mobile Number:</td><td colspan="4">${data.contact_number || '-'}</td>
                                <td colspan="2">FB/Messenger:</td>
                                <td colspan="4">${data.fb_messenger || 'N/A'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Birthday & Age:</td><td colspan="2">${formattedBirthday} (${formattedAge})</td>
                                <td colspan="1">Religion:</td><td colspan="2">${data.religion || '-'}</td>
                                <td colspan="1">Nationality:</td>
                                <td colspan="4">${data.nationality || '-'}</td>
                            </tr>
                            
                            <tr>
                                <td class="section-title" colspan="2">Father's Name</td><td colspan="4">${data.father_name || '-'}</td>
                                <td colspan="1">Age:</td><td colspan="5">${data.father_age || '-'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Occupation/Work:</td><td colspan="4">${data.father_occupation || 'N/A'}</td>
                                <td colspan="2">Mobile Number:</td><td colspan="4">${data.father_contact || 'N/A'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">FB/Messenger:</td><td colspan="4">${data.father_fb || 'N/A'}</td>
                                <td colspan="2">Place of Work:</td><td colspan="4">${data.father_place_work || 'N/A'}</td>
                            </tr>
                            
                            <tr>
                                <td class="section-title" colspan="2">Mother's Name</td><td colspan="4">${data.mother_name || '-'}</td>
                                <td colspan="1">Age:</td><td colspan="5">${data.mother_age || '-'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Occupation/Work:</td><td colspan="4">${data.mother_occupation || 'N/A'}</td>
                                <td colspan="2">Mobile Number:</td><td colspan="4">${data.mother_contact || 'N/A'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">FB/Messenger:</td><td colspan="4">${data.mother_fb || 'N/A'}</td>
                                <td colspan="2">Place of Work:</td><td colspan="4">${data.mother_place_work || 'N/A'}</td>
                            </tr>
                        </table>

                        <!-- Append School Rules and Commitment to the front side -->
                        <table class="info-table">
                            <tr>
                                <td style="width: 85%; vertical-align: top;">
                                
                                <p style="text-align: center; font-size: 12px;"><strong>MGA ALITUNTUNIN DAPAT SUNDIN NG MAG-AARAL NG OCNHS</strong></p>
                                  
                                <ol style="text-align: left; padding-left: 10px; margin: 0; font-size: 11px; list-style-position: inside;">
                                    <li>Maging responsable, palaging dumalo sa takdang araw at oras ng pag-aaral, ‘wag lumiban/manhuli/umabs sa klase.</li>
                                    <li>Aktibong makilahok sa buong panahon ng pag-aaral, kumilos ng maayos at nang may tamang pag-uugali.</li>
                                    <li>Maging mabuti at magalang sa lahat ng panahon; igalang ang mga guro, mga kawani ng paaralan at kapwa mag-aaral.</li>
                                    <li>Bawal ang pagmumura, pakikipag-away, rambulan, maglakal, vandalismo, panggugulo at paninira sa paaralan.</li>
                                    <li>Ipinagbabawal din ang pagdala, paggamit, pagbili o pagbenta ng sigarilyo, vape, alak, droga, baril, banibalnan, kutsilyo o anumang bagay na nakasasakit, pornograpiya at mga katulad ng mga ito.</li>
                                    <li>Sumunod sa pangkalusugang protocol ng pamahalaan, lalo na sa panahon ng pisikal (face-to-face) na pagpasok sa paaralan (face mask, distancing, handwashing, sanitation), na laging may suot na school ID at tamang uniporme o kasuotan, at magkaroon ng maayos (simpleng) ayos tulad sa babae, “student/crew cut” sa lalaki) at bawal din ang may kulay ang buhok, may make-up, may gata sa kilay at mga accessories. Bawal sa mga lalaki ang magsuot ng hikaw. Maaaring magsuot ang babae ng isang pares at simpleng hikaw lamang sa magkabilang tainga na kaaya-aya.</li>
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
                                </td>
                            </tr>
                       
                    
                            <tr>
                                <td colspan="12" style="text-align: left;">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                                        <span style="white-space: nowrap;">Nilagdaan ngayong araw:</span>
                                        <div style="flex-grow: 1; display: flex; justify-content: space-around; margin-left: 10px;">
                                            <div style="text-align: center; flex-basis: 45%;">
                                                <div style="border-bottom: 1px solid #000; height: 1em;"></div>
                                                <div style="margin-top: 5px;">(Petsa)</div>
                                            </div>
                                            <div style="text-align: center; flex-basis: 45%;">
                                                <div style="border-bottom: 1px solid #000; height: 1em;"></div>
                                                <div style="margin-top: 5px;">Lagda ng mag-aaral</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>`;

                    // Back side - School Rules and Commitment
                    const backContent = `
                    <div class="record-form back-side">
                        <table class="info-table">
                          
                    
                            </tr>
                            <tr colspan="12">
                                <td style="width: 85%; vertical-align">
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
                                                <div style="border-bottom: 1px solid #000; height: 1em;"></div>
                                                <div style="margin-top: 5px;">(Petsa)</div>
                                            </div>
                                            <div style="text-align: center; flex-basis: 45%;">
                                                <div style="border-bottom: 1px solid #000; height: 1em;"></div>
                                                <div style="margin-top: 5px;">Lagda ng mag-aaral</div>
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
                                </td>
                            </tr>
                        </table>
                        <table class="info-table">
                            <tr>
                                <td colspan="12">COUNSELOR\'S NOTES:________________________________
                                ____________________________________________________________________
                                    <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                                    
                                </td>
                            </tr>
                        </table>
                    </div>`;

                    const flipCardContent = `
                    <div class="flip-card-container">
                        <div class="flip-card" id="flipCard">
                            <div class="flip-card-inner">
                                <div class="flip-card-front">
                                    ${frontContent}
                                </div>
                                <div class="flip-card-back">
                                    ${backContent}
                                </div>
                            </div>
                        </div>
                        <div class="flip-controls">
                            <button class="flip-btn">🔄 Flip Card</button>
                            <button class="print-btn">🖨️ Print</button>
                        </div>
                    </div>`;

                    Swal.fire({
                        html: flipCardContent,
                        width: '60%',
                        heightAuto: true,   
                        showCloseButton: true,
                        confirmButtonText: 'Close',
                        customClass: { 
                            popup: 'swal-form-popup',
                            actions: 'swal-actions-custom'
                        },
                        showConfirmButton: false,
                        didOpen: () => {
                            // Attach event listeners after modal opens
                            setTimeout(() => {
                                const flipBtn = document.querySelector('.swal2-popup .flip-btn');
                                const printBtn = document.querySelector('.swal2-popup .print-btn');
                                
                                if (flipBtn) {
                                    flipBtn.onclick = function(e) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        const flipCard = document.querySelector('.swal2-popup .flip-card');
                                        if (flipCard) {
                                            flipCard.classList.toggle('flipped');
                                        }
                                        return false;
                                    };
                                }
                                
                                if (printBtn) {
                                    printBtn.onclick = function(e) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        printStudentProfile(frontContent, backContent);
                                        return false;
                                    };
                                }
                            }, 100);
                        }
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong while fetching data.' });
            });
        }

        function printStudentProfile(frontContent, backContent) {
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
                    <title>Student Profile - Print</title>
                    <style>
                        @media print {
                            @page {
                                size: letter; /* You can use A4 as well */
                                margin: 0.25in;
                            }
                            body {
                                margin: 0;
                                padding: 0;
                                -webkit-print-color-adjust: exact !important;
                                print-color-adjust: exact !important;
                            }
                            .print-page {
                                /* page-break-after: always; */
                            }
                            .print-page:last-child {
                                /* page-break-after: auto; */
                                margin-bottom: 0;
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
                            /* Space between front and back content */
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
                            line-height: 1.0; /* Reduced from 1.1 */
                        }
                        .header-text h3, .header-text h4, .header-text h5, .header-text h6 {
                            margin: 1px 0;
                        }
                        .info-table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 3px; /* Reduced from 5px */
                            font-size: 10px;
                        }
                        .info-table td {
                            border: 1px solid #000;
                            padding: 2px 3px; /* Reduced from 3px 4px */
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
                        /* Remove the generic rules for .rules and .commitment as they are not used */
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
                            ${frontContent}
                        </div>
                        <div class="print-page">
                            ${backContent}
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

<style>
        .swal-form-popup {
            background: #fff;
            font-family: 'Arial', sans-serif;
            color: #000;
        }

        /* Flip Card Styles */
        .flip-card-container {
            perspective: 1000px;
            width: 100%;
            height: auto;
            min-height: 500px;
        }

        .flip-card {
            position: relative;
            width: 100%;
            height: 550px;
            margin: 0 auto;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }

        .flip-card.flipped {
            transform: rotateY(180deg);
        }

        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }

        .flip-card-front,
        .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            overflow-y: auto;
            padding: 10px;
            background: #fff;
        }

        .flip-card-back {
            transform: rotateY(180deg);
        }

        .record-form {
            padding: 10px 20px;
            height: auto;
            overflow: visible;
        }

        /* Flip Controls */
        .flip-controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .flip-btn,
        .print-btn {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.3s;
        }

        .flip-btn:hover,
        .print-btn:hover {
            background: #0056b3;
        }

        .print-btn {
            background: #28a745;
        }

        .print-btn:hover {
            background: #1e7e34;
        }

        .form-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 10px;
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

        .header-text h3, .header-text h4, .header-text h5, .header-text h6 {
            margin: 2px 0;
        }

        .header-text h6 {
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
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
    </style>

</body>
</html>
