<!DOCTYPE html>
<html>
<head>
    <!-- Basic Page Info -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Add Additional Information</title>

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
        .form-step {
            display: none;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0px 3px 6px rgba(0,0,0,0.1);
        }
        .form-step.active {
            display: block;
        }
        .form-group input,
        .form-group select {
            padding: 10px;
        }
        .btn {
            min-width: 120px;
        }
        .card-box {
            border-radius: 15px;
            background-color: #ffffff;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }
        
        /* Tab Styles - UPDATED FOR RESPONSIVE DESIGN */
        .form-tabs {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        /* Desktop - 5 columns */
        @media (min-width: 768px) {
            .form-tabs {
                grid-template-columns: repeat(5, 1fr);
            }
        }
        
        /* Tablet - 2 columns */
        @media (max-width: 767px) and (min-width: 576px) {
            .form-tabs {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        /* Mobile - 2 columns */
        @media (max-width: 575px) {
            .form-tabs {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }
        }
        
        .form-tab {
            text-align: center;
            position: relative;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 5px;
            transition: all 0.3s ease;
        }
        
        .form-tab a {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px 8px;
            color: #6c757d;
            text-decoration: none;
            position: relative;
            font-weight: 500;
            min-height: 70px;
            justify-content: center;
        }
        
        /* Smaller text on mobile */
        @media (max-width: 575px) {
            .form-tab a {
                padding: 8px 5px;
                min-height: 60px;
                font-size: 0.85rem;
            }
        }
        
        .form-tab.current {
            background: #e8f0fe;
            border: 2px solid #1b00ff;
        }
        
        .form-tab.current a {
            color: #1b00ff;
            font-weight: 600;
        }
        
        .form-tab.done {
            background: #f0fff4;
            border: 1px solid #28a745;
        }
        
        .form-tab.done a {
            color: #28a745;
        }
        
        .form-tab .step {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 0.9rem;
        }
        
        /* Smaller step circles on mobile */
        @media (max-width: 575px) {
            .form-tab .step {
                width: 25px;
                height: 25px;
                font-size: 0.8rem;
            }
        }
        
        .form-tab.current .step {
            background: #1b00ff;
            color: white;
        }
        
        .form-tab.done .step {
            background: #28a745;
            color: white;
        }
        
        .form-tab:hover {
            background: #e9ecef;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        label.required::after {
            content: " *";
            color: red;
            font-weight: bold;
        }

        /* Tab text responsiveness */
        .tab-text {
            display: block;
            text-align: center;
            line-height: 1.2;
        }
        
        /* Hide full text on very small screens, show abbreviated */
        @media (max-width: 400px) {
            .full-text {
                display: none;
            }
            .abbr-text {
                display: block;
                font-size: 0.75rem;
            }
        }
        
        @media (min-width: 401px) {
            .full-text {
                display: block;
            }
            .abbr-text {
                display: none;
            }
        }

        /* Denomination styles */
        .denomination-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            max-height: 500px;
            overflow-y: auto;
        }

        .denomination-item {
            padding: 12px 15px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .denomination-item:hover {
            background: #e8f0fe;
            border-color: #007bff;
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.2);
        }

        .denomination-text {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        /* Visual indicator for options with submenus */
        .has-submenu {
            font-weight: 600;
            color: #007bff;
            background: linear-gradient(90deg, #f8f9fa 0%, #e8f0fe 100%);
        }

        .has-submenu::after {
            content: " ▼";
            font-size: 12px;
            color: #007bff;
            font-weight: bold;
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
                <div class="page-header text-center">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="title" style="display: flex; align-items: center; justify-content: center; gap: 20px;">
                                <img src="/vendors/images/logo-ocnhs.png" alt="OCNHS Logo Left" style="max-width: 80px; height: auto;">
                                <h1 style="margin: 0;">Guidance & Counseling Unit</h1>
                                <img src="/vendors/images/logo-ocnhs-2.png" alt="OCNHS Logo Right" style="max-width: 80px; height: auto;">
                            </div>
                            <h4>Learner's Individual Inventory Record</h4>
                        </div>
                    </div>
                </div>

                <div class="card-box mb-30 p-4">
                    <div class="clearfix mb-30">
                        <h4 class="text-blue h4">Student Information Form</h4>
                    </div>

                    @if(!$currentSchoolYear)
                        <div class="alert alert-danger">
                            <strong>No Active School Year</strong><br>
                            No active school year found. Please contact the administrator or guidance counselor.
                        </div>
                    @endif
                    @if($curriculums->isEmpty())
                        <div class="alert alert-danger">
                            <strong>No Active Curriculum</strong><br>
                            No active curriculum found. Please contact the administrator or guidance counselor.
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($currentSchoolYear && !$curriculums->isEmpty())
                    <!-- UPDATED: Responsive Tab Navigation -->
                    <div class="form-tabs">
                        <div class="form-tab first current">
                            <a href="#step-1">
                                <span class="step">1</span>
                                <span class="tab-text">
                                    <span class="full-text">Student Information</span>
                                    <span class="abbr-text">Student Info</span>
                                </span>
                            </a>
                        </div>
                        <div class="form-tab">
                            <a href="#step-2">
                                <span class="step">2</span>
                                <span class="tab-text">
                                    <span class="full-text">Father's Information</span>
                                    <span class="abbr-text">Father Info</span>
                                </span>
                            </a>
                        </div>
                        <div class="form-tab">
                            <a href="#step-3">
                                <span class="step">3</span>
                                <span class="tab-text">
                                    <span class="full-text">Mother's Information</span>
                                    <span class="abbr-text">Mother Info</span>
                                </span>
                            </a>
                        </div>
                        <div class="form-tab">
                            <a href="#step-4">
                                <span class="step">4</span>
                                <span class="tab-text">
                                    <span class="full-text">Guardian's Information</span>
                                    <span class="abbr-text">Guardian Info</span>
                                </span>
                            </a>
                        </div>
                        <div class="form-tab last">
                            <a href="#step-5">
                                <span class="step">5</span>
                                <span class="tab-text">
                                    <span class="full-text">Agreements</span>
                                    <span class="abbr-text">Agreements</span>
                                </span>
                            </a>
                        </div>
                    </div>
                    
                    <form id="multiStepForm" action="{{ route('student.additional-info.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Step 1: Student Info -->
                        <div class="form-step active" id="step-1">
                            <div class="row g-3">

                             <!-- Profile Picture -->
                             <div class="col-md-12 form-group">
                                 <label for="profile_picture" class="font-weight-bold">Profile Picture</label>
                                 <input type="file" name="profile_picture" id="profile_picture" class="form-control" accept="image/*" required onchange="validateFileSize(event); previewImage(event)">
                                 <small class="form-text text-muted">Upload a clear photo of yourself (JPG, PNG, max 10MB)</small>
                                 <div id="image-preview" class="mt-2" style="display: none;">
                                     <img id="preview-img" src="" alt="Image Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                 </div>
                             </div>
                                <!-- School Year -->
                              <div class="col-md-3 form-group">
                                <label for="school_year" class="font-weight-bold">School Year</label>
                                <input type="text" name="school_year_display" id="school_year" class="form-control" 
                                    value="{{ $currentSchoolYear ? $currentSchoolYear->school_year : 'No active school year' }}" 
                                    readonly required>

                                @if($currentSchoolYear)
                                    <input type="hidden" name="school_year" value="{{ $currentSchoolYear->id }}" required>
                                @endif
                            </div>


                                <!-- Learner's Name -->
                                <div class="col-md-3 form-group">
                                    <label for="learner" class="font-weight-bold">Learner's Name</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly required>
                                    <input type="hidden" name="learner" value="{{ Auth::user()->id }}" required>
                                </div>

                                <!-- LRN -->
                                <div class="col-md-3 form-group">
                                    <label for="lrn" class="font-weight-bold">Learner Reference Number</label>
                                    <input type="text" name="lrn" id="lrn" class="form-control" placeholder="Enter Learner Reference Number" required>
                                </div>

                                <!-- Date Today -->
                                <div class="col-md-3 form-group">
                                    <label for="current_date" class="font-weight-bold">Date</label>
                                    <input type="date" name="current_date" id="current_date" class="form-control" readonly required>
                                </div>	

                                <!-- Sex -->
                                <div class="col-md-3 form-group">
                                    <label for="sex" class="font-weight-bold">Sex</label>
                                    <select name="sex" id="sex" class="form-control" required>
                                        <option value="">Select Sex</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>

                                <!-- Grade -->
                                <div class="col-md-3 form-group">
                                    <label for="grade" class="font-weight-bold">Grade</label>
                                    <select name="grade" id="grade" class="form-control" required>
                                        @for($i = 7; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('grade') == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Curriculum / Program -->
                                <div class="col-md-3 form-group">
                                    <label for="curriculum" class="font-weight-bold">Curriculum / Program</label>
                                    <select name="curriculum" id="curriculum" class="form-control" required>
                                        <option value="" disabled selected>Select Curriculum</option>
                                        @foreach($curriculums as $curriculum)
                                            <option value="{{ $curriculum->name }}" {{ old('curriculum') == $curriculum->name ? 'selected' : '' }}>
                                                {{ $curriculum->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Section -->
                                <div class="col-md-3 form-group">
                                    <label for="section" class="font-weight-bold">Section</label>
                                    <input type="text" name="section" id="section" class="form-control" placeholder="Enter Section" required value="{{ old('section') }}">
                                </div>

                                <div class="col-md-3 form-group">
                                    <label for="disability" class="font-weight-bold">Disability (if any)</label>
                                    <input type="text" name="disability" id="disability" class="form-control" placeholder="Enter Disability (if any)"  value="{{ old('disability') }}">
                                </div>

                                <!-- Mode of Living -->
                                <div class="col-md-12 form-group">
                                    <label class="font-weight-bold">Living with</label>
                                    <div class="form-check">
                                        <input type="checkbox" name="living_mode[]" value="Living with Father" class="form-check-input living-mode-checkbox" data-target="father"> Father
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="living_mode[]" value="Living with Mother" class="form-check-input living-mode-checkbox" data-target="mother"> Mother
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="living_mode[]" value="Living with Other Guardians" class="form-check-input living-mode-checkbox" data-target="guardian"> Guardians
                                    </div>
                                </div>

                                <!-- Complete Address -->
                                <div class="col-md-6 form-group">
                                    <label for="address" class="font-weight-bold">Complete Address</label>
                                    <input type="text" name="address" id="address" class="form-control" placeholder="Enter Complete Address" required>
                                </div>

                                <!-- Mobile Number -->
                                <div class="col-md-6 form-group">
                                    <label for="contact_number" class="font-weight-bold">Mobile Number</label>
                                    <input type="text" name="contact_number" id="contact_number" class="form-control" placeholder="09XXXXXXXXX" required>
                                </div>

                                <!-- Birthday -->
                                <div class="col-md-6 form-group">
                                    <label for="birthday" class="font-weight-bold">Birthday</label>
                                    <input type="date" name="birthday" id="birthday" class="form-control" required onchange="calculateAge()">
                                </div>

                                <!-- Age -->
                                <div class="col-md-6 form-group">
                                    <label for="age" class="font-weight-bold">Age</label>
                                    <input type="text" name="age" id="age" class="form-control" readonly required>
                                </div>

                                <!-- Religion -->
                                <div class="col-md-6 form-group">
                                    <label for="religion" class="font-weight-bold">Religion</label>
                                    <select name="religion" id="religion" class="form-control religion-select" data-user-id="{{ Auth::id() }}" required>
                                        <option value="">Select Religion</option>
                                        <option value="Catholic">Catholic</option>
                                        <option value="Protestant" class="has-submenu">Protestant ▶</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Buddhism">Buddhism</option>
                                        <option value="Hinduism">Hinduism</option>
                                        <option value="Judaism">Judaism</option>
                                        <option value="Agnostic">Agnostic</option>
                                        <option value="Atheist">Atheist</option>
                                        <option value="Others">Others</option>
                                    </select>
                                    <input type="hidden" name="religion_denomination" id="religion-denomination" value="">
                                </div>

                                <!-- Nationality -->
                                <div class="col-md-6 form-group">
                                    <label for="nationality" class="font-weight-bold">Nationality</label>
                                    <input type="text" name="nationality" id="nationality" class="form-control" required placeholder="Enter Nationality">
                                </div>

                                <!-- FB/Messenger -->
                                <div class="col-md-12 form-group">
                                    <label for="fb_messenger" class="font-weight-bold">FB/Messenger</label>
                                    <input type="text" name="fb_messenger" id="fb_messenger"
                                        class="form-control" placeholder="Enter Facebook or Messenger / Optional">
                                </div>

                               
                            </div>

                            <div class="mt-3 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary next-step">Next</button>
                            </div>
                        </div>

                        <!-- Step 2: Father's Info -->
                        <div class="form-step" id="step-2">
                            <div class="row g-3">
                                <div class="col-md-6 form-group">
                                    <label for="father_name" class="font-weight-bold">Father's Name</label>
                                    <input type="text" name="father_name" id="father_name" class="form-control" placeholder="Enter Father's Name">
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="father_age" class="font-weight-bold">Age</label>
                                    <input type="number" name="father_age" id="father_age" class="form-control" placeholder="Age">
                                </div>

                                <!-- Occupation -->
                                <div class="col-md-4 form-group">
                                    <label for="father_occupation" class="font-weight-bold">Occupation / Work</label>
                                    <input type="text" name="father_occupation" id="father_occupation" class="form-control" placeholder="Enter Occupation">
                                </div>

                                <!-- Place of Work -->
                                <div class="col-md-6 form-group">
                                    <label for="father_place_work" class="font-weight-bold">Place of Work</label>
                                    <input type="text" name="father_place_work" id="father_place_work" class="form-control" placeholder="Enter Place of Work">
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="father_contact" class="font-weight-bold">Mobile Number</label>
                                    <input type="text" name="father_contact" id="father_contact" class="form-control" placeholder="09XXXXXXXXX">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="father_fb" class="font-weight-bold">FB/Messenger</label>
                                    <input type="text" name="father_fb" id="father_fb" class="form-control" placeholder="Father's FB/Messenger / Optional">
                                </div>
                            </div>

                            <div class="mt-3 d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                                <button type="button" class="btn btn-primary next-step">Next</button>
                            </div>
                        </div>

                        <!-- Step 3: Mother's Info -->
                        <div class="form-step" id="step-3">
                            <div class="row g-3">
                                <div class="col-md-6 form-group">
                                    <label for="mother_name" class="font-weight-bold">Mother's Name</label>
                                    <input type="text" name="mother_name" id="mother_name" class="form-control" placeholder="Enter Mother's Name">
                                </div>

                                <div class="col-md-2 form-group">
                                    <label for="mother_age" class="font-weight-bold">Age</label>
                                    <input type="number" name="mother_age" id="mother_age" class="form-control" placeholder="Age">
                                </div>

                                <!-- Occupation -->
                               <div class="col-md-4 form-group">
                                    <label for="mother_occupation" class="font-weight-bold">Occupation / Work</label>
                                    <input type="text" name="mother_occupation" id="mother_occupation" class="form-control" placeholder="Enter Occupation">
                                </div>

                                <!-- Place of Work -->
                                <div class="col-md-6 form-group">
                                    <label for="mother_place_work" class="font-weight-bold">Place of Work</label>
                                    <input type="text" name="mother_place_work" id="mother_place_work" class="form-control" placeholder="Enter Place of Work">
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="mother_contact" class="font-weight-bold">Mobile Number</label>
                                    <input type="text" name="mother_contact" id="mother_contact" class="form-control" placeholder="09XXXXXXXXX">
                                </div>

                                <div class="col-md-12 form-group">
                                    <label for="mother_fb" class="font-weight-bold">FB/Messenger</label>
                                    <input type="text" name="mother_fb" id="mother_fb" class="form-control" placeholder="Mother's FB/Messenger / Optional">
                                </div>
                            </div>

                            <div class="mt-3 d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                                <button type="button" class="btn btn-primary next-step">Next</button>
                            </div>
                        </div>

                        <!-- Step 4: Guardian Info -->
                        <div class="form-step" id="step-4">
                            <div class="row g-3">
                                <div class="col-md-6 form-group">
                                    <label for="guardian_name" class="font-weight-bold">Guardian's Name</label>
                                    <input type="text" name="guardian_name" id="guardian_name" class="form-control" placeholder="Enter Guardian's Name">
                                </div>

                                <div class="col-md-2 form-group">
                                    <label for="guardian_age" class="font-weight-bold">Age</label>
                                    <input type="number" name="guardian_age" id="guardian_age" class="form-control" placeholder="Age">
                                </div>

                                <!-- Occupation -->
                               <div class="col-md-4 form-group">
                                    <label for="guardian_occupation" class="font-weight-bold">Occupation / Work</label>
                                    <input type="text" name="guardian_occupation" id="guardian_occupation" class="form-control" placeholder="Enter Occupation">
                                </div>

                                <!-- Place of Work -->
                                <div class="col-md-6 form-group">
                                    <label for="guardian_place_work" class="font-weight-bold">Place of Work</label>
                                    <input type="text" name="guardian_place_work" id="guardian_place_work" class="form-control" placeholder="Enter Place of Work">
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="guardian_contact" class="font-weight-bold">Mobile Number</label>
                                    <input type="text" name="guardian_contact" id="guardian_contact" class="form-control" placeholder="09XXXXXXXXX">
                                </div>

                                <div class="col-md-12 form-group">
                                    <label for="guardian_fb" class="font-weight-bold">FB/Messenger</label>
                                    <input type="text" name="guardian_fb" id="guardian_fb" class="form-control" placeholder="Guardian's FB/Messenger / Optional">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="guardian_relationship" class="font-weight-bold">Relationship</label>
                                    <input type="text" name="guardian_relationship" id="guardian_relationship" class="form-control" placeholder="e.g., Aunt, Uncle, Grandparent">
                                </div>
                            </div>

                            <div class="mt-3 d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                                <button type="button" class="btn btn-primary next-step">Next</button>
                            </div>
                        </div>

                        <!-- Step 5: Agreements -->
                        <div class="form-step" id="step-5">
                            <div class="alert alert-warning mb-3">
                                <strong>Note:</strong> Please read all agreements carefully before proceeding.
                            </div>
                            <!-- Student and Parent Agreement Section -->
                            <div class="row g-3">
                                <!-- For Student -->
                                <div class="col-md-12 mb-3">
                                    <h5 class="font-weight-bold">For Student</h5>

                                    <div class="form-check">
                                        <input type="checkbox" name="student_agreement_1" id="student_agreement_1" class="form-check-input" required disabled>
                                        <label for="student_agreement_1" class="form-check-label">
                                            Sumasang-ayon ako sa
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#rulesModal" style="color: blue; text-decoration: underline;">Alituntunin sa Paaralan</a>
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" name="student_agreement_2" id="student_agreement_2" class="form-check-input" required disabled>
                                        <label for="student_agreement_2" class="form-check-label">
                                            Sumasang-ayon ako sa
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#commitmentModal" style="color: blue; text-decoration: underline;">Komitment sa Paaralan</a>
                                        </label>
                                    </div>
                                </div>

                                <!-- For Parent -->
                                <div class="col-md-12 mb-3">
                                    <h5 class="font-weight-bold">For Parent / Guardian</h5>

                                    <div class="form-check">
                                        <input type="checkbox" name="parent_agreement_1" id="parent_agreement_1" class="form-check-input" required disabled>
                                        <label for="parent_agreement_1" class="form-check-label">
                                            Sumasang-ayon ako sa
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#parentDutiesModal" style="color: blue; text-decoration: underline;">Mga Tungkulin ng Magulang / Guardian</a>
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" name="parent_agreement_2" id="parent_agreement_2" class="form-check-input" required disabled>
                                        <label for="parent_agreement_2" class="form-check-label">
                                            Sumasang-ayon ako sa
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#parentCommitmentModal" style="color: blue; text-decoration: underline;">Komitment sa Paaralan</a>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            @include('student.modals.agreements')
                            <!-- End of Agreements Section -->

                            <div class="mt-3 d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                    @endif

                    {{-- 🔹 Protestant Denomination Selection Modal --}}
                    <div class="modal fade" id="protestantDenominationModal" tabindex="-1" aria-labelledby="protestantDenominationLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="protestantDenominationLabel">
                                        <i class="dw dw-religion"></i> Select Protestant Denomination
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="denomination-list">
                                        <div class="denomination-item" onclick="selectDenomination('Lutheran')">
                                            <div class="denomination-text">Lutheran</div>
                                        </div>
                                        <div class="denomination-item" onclick="selectDenomination('Anglican / Episcopal')">
                                            <div class="denomination-text">Anglican / Episcopal</div>
                                        </div>
                                        <div class="denomination-item" onclick="selectDenomination('Baptist')">
                                            <div class="denomination-text">Baptist</div>
                                        </div>
                                        <div class="denomination-item" onclick="selectDenomination('Methodist')">
                                            <div class="denomination-text">Methodist</div>
                                        </div>
                                        <div class="denomination-item" onclick="selectDenomination('Presbyterian')">
                                            <div class="denomination-text">Presbyterian</div>
                                        </div>
                                        <div class="denomination-item" onclick="selectDenomination('Calvinist / Reformed')">
                                            <div class="denomination-text">Calvinist / Reformed</div>
                                        </div>
                                        <div class="denomination-item" onclick="selectDenomination('Pentecostal (e.g., Assemblies of God)')">
                                            <div class="denomination-text">Pentecostal (e.g., Assemblies of God)</div>
                                        </div>
                                        <div class="denomination-item" onclick="selectDenomination('Evangelical')">
                                            <div class="denomination-text">Evangelical</div>
                                        </div>
                                        <div class="denomination-item" onclick="selectDenomination('Seventh-day Adventist')">
                                            <div class="denomination-text">Seventh-day Adventist</div>
                                        </div>
                                        <div class="denomination-item" onclick="selectDenomination('Church of Christ')">
                                            <div class="denomination-text">Church of Christ</div>
                                        </div>
                                        <div class="denomination-item" onclick="selectDenomination('Born Again / Bible churches')">
                                            <div class="denomination-text">Born Again / Bible churches</div>
                                        </div>
                                        <div class="denomination-item" onclick="selectDenomination('Non-denominational Christian churches')">
                                            <div class="denomination-text">Non-denominational Christian churches</div>
                                        </div>
                                        <div class="denomination-item" onclick="selectDenomination('Jehovah\'s Witnesses')">
                                            <div class="denomination-text">Jehovah's Witnesses</div>
                                        </div>
                                        <div class="denomination-item" onclick="selectDenomination('Mormons')">
                                            <div class="denomination-text">Mormons</div>
                                        </div>
                                        <div class="denomination-item" onclick="selectDenomination('Iglesia ni Cristo (INC)')">
                                            <div class="denomination-text">Iglesia ni Cristo (INC)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
// Debug: Check if OCNHS logos load
const logoImgs = document.querySelectorAll('img[alt*="OCNHS Logo"]');
logoImgs.forEach((img, index) => {
    img.addEventListener('load', () => console.log(`OCNHS Logo ${index + 1} loaded successfully`));
    img.addEventListener('error', () => console.log(`OCNHS Logo ${index + 1} failed to load - check path: ` + img.src));
});
if (logoImgs.length === 0) {
    console.log('OCNHS Logo images not found in DOM');
}

document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll(".form-tab");
    const steps = document.querySelectorAll(".form-step");
    const nextBtns = document.querySelectorAll(".next-step");
    const prevBtns = document.querySelectorAll(".prev-step");
    const form = document.getElementById("multiStepForm");
    const livingCheckboxes = document.querySelectorAll("input[name='living_mode[]']");
    let currentStep = 0;
    let activeSteps = ['step-1', 'step-5']; // default, will be updated

    const tabData = [
        { label: 'Student Information', abbr: 'Student Info' },
        { label: 'Father\'s Information', abbr: 'Father Info' },
        { label: 'Mother\'s Information', abbr: 'Mother Info' },
        { label: 'Guardian\'s Information', abbr: 'Guardian Info' },
        { label: 'Agreements', abbr: 'Agreements' }
    ];

    function getActiveSteps() {
        let steps = ['step-1'];
        const fatherChecked = document.querySelector("input[name='living_mode[]'][value='Living with Father']").checked;
        const motherChecked = document.querySelector("input[name='living_mode[]'][value='Living with Mother']").checked;
        const guardianChecked = document.querySelector("input[name='living_mode[]'][value='Living with Other Guardians']").checked;
        if (fatherChecked) steps.push('step-2');
        if (motherChecked) steps.push('step-3');
        if (guardianChecked) steps.push('step-4');
        steps.push('step-5'); // agreements always
        return steps;
    }

    // 🔹 Add red asterisk for required labels
    document.querySelectorAll("input[required], select[required], textarea[required]").forEach(input => {
        const label = input.closest(".form-group")?.querySelector("label");
        if (label) label.classList.add("required");
    });

    // 🔹 Show correct step and highlight active tab
    function showStep(stepIndex) {
        // Hide all steps
        steps.forEach(s => s.classList.remove('active'));
        // Show active step
        const activeStepId = activeSteps[stepIndex];
        const activeStepEl = document.getElementById(activeStepId);
        if (activeStepEl) activeStepEl.classList.add('active');

        // Update tabs
        const tabElements = document.querySelectorAll('.form-tab');
        tabElements.forEach((tab, i) => {
            if (i < activeSteps.length) {
                tab.style.display = 'block';
                const stepId = activeSteps[i];
                const stepNum = parseInt(stepId.replace('step-', '')) - 1;
                const data = tabData[stepNum];
                const link = tab.querySelector('a');
                link.href = '#' + stepId;
                link.querySelector('.full-text').textContent = data.label;
                link.querySelector('.abbr-text').textContent = data.abbr;
                tab.classList.toggle('current', i === stepIndex);
                tab.classList.toggle('done', i < stepIndex);
                const stepSpan = tab.querySelector('.step');
                if (stepSpan) stepSpan.textContent = i + 1;
            } else {
                tab.style.display = 'none';
            }
        });
    }

    // 🔹 Move to next step
    function nextStep() {
        if (currentStep < activeSteps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    }

    // 🔹 Move to previous step
    function prevStep() {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    }

    // 🔹 Next button logic
    nextBtns.forEach(btn => {
        btn.addEventListener("click", async function (e) {
            e.preventDefault();
            // Update required fields before validation
            updateRequiredFields();
            const currentStepEl = document.getElementById(activeSteps[currentStep]);
            if (!await validateStep(currentStepEl)) return;
            if (currentStep === 0) {
                activeSteps = getActiveSteps();
            }
            nextStep();
        });
    });

    // 🔹 Prev button logic
    prevBtns.forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            prevStep();
        });
    });

    // 🔹 Contact number restrictions - auto-start with "09"
    const contactFields = ['contact_number', 'father_contact', 'mother_contact', 'guardian_contact'];
    contactFields.forEach(id => {
        const input = document.getElementById(id);
        if (!input) return;
        
        // Auto-start with "09" when field is focused and empty
        input.addEventListener('focus', function () {
            if (this.value === '' || this.value.length === 0) {
                this.value = '09';
            } else if (!this.value.startsWith('09')) {
                // If existing value doesn't start with 09, prepend it
                this.value = '09' + this.value.replace(/[^0-9]/g, '').replace(/^09/, '');
            }
        });
        
        // Ensure it always starts with "09"
        input.addEventListener('input', function () {
            let value = this.value.replace(/[^0-9]/g, '');
            
            // If it doesn't start with 09, prepend it
            if (!value.startsWith('09')) {
                value = '09' + value.replace(/^09/, '');
            }
            
            // Limit to 11 digits (09 + 9 more digits)
            this.value = value.slice(0, 11);
        });
        
        // Prevent typing if already at max length
        input.addEventListener('keypress', function (e) {
            if (!/[0-9]/.test(e.key) || this.value.length >= 11) {
                e.preventDefault();
            }
        });
        
        // Ensure "09" prefix on blur if field is not empty
        input.addEventListener('blur', function () {
            if (this.value && !this.value.startsWith('09')) {
                let value = this.value.replace(/[^0-9]/g, '');
                this.value = '09' + value.replace(/^09/, '').slice(0, 9);
            }
        });
    });

    // Enable checkboxes when modals are opened
    $('#rulesModal').on('shown.bs.modal', function () {
        $('#student_agreement_1').prop('disabled', false);
    });
    $('#commitmentModal').on('shown.bs.modal', function () {
        $('#student_agreement_2').prop('disabled', false);
    });
    $('#parentDutiesModal').on('shown.bs.modal', function () {
        $('#parent_agreement_1').prop('disabled', false);
    });
    $('#parentCommitmentModal').on('shown.bs.modal', function () {
        $('#parent_agreement_2').prop('disabled', false);
    });

    // Update tabs when living_mode changes in step 1
    livingCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            if (currentStep === 0) {
                activeSteps = getActiveSteps();
                showStep(currentStep);
            }
        });
    });

    // 🔹 LRN validation
    const lrnInput = document.getElementById("lrn");
    lrnInput.addEventListener("input", function () {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);
    });

    async function validateStep(stepElement) {
        const inputs = stepElement.querySelectorAll("input[required], select[required], textarea[required]");
        for (let input of inputs) {
            if (!input.checkValidity()) {
                input.reportValidity();
                return false;
            }
        }

        const lrnInput = stepElement.querySelector("#lrn");
        if (lrnInput) {
            const lrn = lrnInput.value.trim();
            if (lrn === '' || lrn.length < 11 || lrn.length > 12) {
                await Swal.fire({ icon: 'warning', title: 'Invalid LRN', text: 'LRN must be 11–12 digits long.' });
                return false;
            }
            const isUnique = await checkLrnUnique(lrn);
            if (!isUnique) {
                await Swal.fire({ icon: 'error', title: 'Duplicate LRN', text: 'This LRN already exists.' });
                return false;
            }
        }

        if (stepElement.id === "step-1") {
            const checkboxes = stepElement.querySelectorAll("input[name='living_mode[]']");
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            if (!anyChecked) {
                await Swal.fire({ icon: 'warning', title: 'Oops!', text: 'Please select at least one Mode of Living.' });
                return false;
            }
        }

        // Validate agreement checkboxes in step 5
        if (stepElement.id === "step-5") {
            const agreementCheckboxes = stepElement.querySelectorAll("input[type='checkbox'][required]");
            for (let checkbox of agreementCheckboxes) {
                if (!checkbox.checked) {
                    checkbox.focus();
                    await Swal.fire({ 
                        icon: 'warning', 
                        title: 'Agreement Required', 
                        text: 'Please accept all required agreements before submitting.' 
                    });
                    return false;
                }
            }
        }

        return true;
    }

    async function checkLrnUnique(lrn) {
        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch("{{ route('student.check-lrn') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                    "Accept": "application/json"
                },
                body: JSON.stringify({ lrn })
            });
            const data = await response.json();
            return !data.exists;
        } catch (err) {
            await Swal.fire({ icon: 'error', title: 'Error!', text: 'Unable to validate LRN at the moment.' });
            return false;
        }
    }

    // 🔹 Form confirmation - Chrome compatible submission
    let isSubmitting = false;
    const handleFormSubmit = async function (e) {
        // Prevent multiple submissions
        if (isSubmitting) {
            e.preventDefault();
            return;
        }

        e.preventDefault();

        // Update required fields before validation
        updateRequiredFields();

        // Validate all active steps before showing confirmation
        for (let i = 0; i < activeSteps.length; i++) {
            const stepEl = document.getElementById(activeSteps[i]);
            if (!await validateStep(stepEl)) {
                currentStep = i;
                showStep(currentStep);
                return;
            }
        }
        
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "Make sure all information is correct before submitting!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit!'
        });
        
        if (result.isConfirmed) {
            isSubmitting = true;
            // Remove the event listener to allow natural form submission
            form.removeEventListener("submit", handleFormSubmit);
            
            // Use requestSubmit() for better browser compatibility (Chrome, Firefox, etc.)
            // This method triggers HTML5 validation and submit event properly
            if (typeof form.requestSubmit === 'function') {
                // requestSubmit() will trigger validation and submit event
                form.requestSubmit();
            } else {
                // Fallback for older browsers - create a temporary submit button
                // This ensures HTML5 validation still runs
                const submitBtn = document.createElement('button');
                submitBtn.type = 'submit';
                submitBtn.style.display = 'none';
                form.appendChild(submitBtn);
                // Clicking the button triggers validation and submit event
                submitBtn.click();
                // Clean up after a short delay
                setTimeout(() => {
                    if (form.contains(submitBtn)) {
                        form.removeChild(submitBtn);
                    }
                }, 100);
            }
        }
    };
    
    form.addEventListener("submit", handleFormSubmit);

    // 🔹 Auto-fill today's date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById("current_date").value = today;

    // 🔹 Auto-calculate age
    window.calculateAge = function () {
        const birthday = document.getElementById("birthday").value;
        if (birthday) {
            const birthDate = new Date(birthday);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
            document.getElementById("age").value = age;
        }
    };

    // 🔹 Initialize - show first step
    activeSteps = getActiveSteps();
    showStep(currentStep);

    // 🔹 Handle dynamic required fields based on living mode
    function updateRequiredFields() {
        const fatherChecked = document.querySelector("input[name='living_mode[]'][value='Living with Father']").checked;
        const motherChecked = document.querySelector("input[name='living_mode[]'][value='Living with Mother']").checked;
        const guardianChecked = document.querySelector("input[name='living_mode[]'][value='Living with Other Guardians']").checked;

        // Father fields - only name is required
        const fatherNameField = document.getElementById('father_name');
        if (fatherNameField) {
            fatherNameField.required = fatherChecked;
            const label = fatherNameField.closest('.form-group').querySelector('label');
            if (label) {
                label.classList.toggle('required', fatherChecked);
            }
        }

        // Mother fields - only name is required
        const motherNameField = document.getElementById('mother_name');
        if (motherNameField) {
            motherNameField.required = motherChecked;
            const label = motherNameField.closest('.form-group').querySelector('label');
            if (label) {
                label.classList.toggle('required', motherChecked);
            }
        }

        // Guardian fields - name and relationship are required
        const guardianRequiredFields = ['guardian_name', 'guardian_relationship'];
        guardianRequiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.required = guardianChecked;
                const label = field.closest('.form-group').querySelector('label');
                if (label) {
                    label.classList.toggle('required', guardianChecked);
                }
            }
        });
    }

    // Add event listeners to living mode checkboxes
    document.querySelectorAll('.living-mode-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateRequiredFields);
    });

    // Initial update
    updateRequiredFields();

    // Religion dropdown handler
    const religionSelect = document.getElementById('religion');
    if (religionSelect) {
        religionSelect.addEventListener('change', function() {
            // List of all denomination values that should trigger the modal
            const denominations = ['Lutheran', 'Anglican / Episcopal', 'Baptist', 'Methodist', 'Presbyterian',
                                  'Calvinist / Reformed', 'Pentecostal (e.g., Assemblies of God)', 'Evangelical',
                                  'Seventh-day Adventist', 'Church of Christ', 'Born Again / Bible churches',
                                  'Non-denominational Christian churches', 'Jehovah\'s Witnesses', 'Mormons', 'Iglesia ni Cristo (INC)'];

            if (this.value === 'Protestant') {
                $('#protestantDenominationModal').modal('show');
            }
        });
    }
});

function selectDenomination(denomination) {
    const religionSelect = document.getElementById('religion');
    const denominationField = document.getElementById('religion-denomination');

    // Store the denomination in the hidden field
    denominationField.value = denomination;

    // Check if the denomination option already exists
    let option = Array.from(religionSelect.options).find(opt => opt.value === denomination);

    // If it doesn't exist, create it
    if (!option) {
        option = document.createElement('option');
        option.value = denomination;
        option.text = denomination;
        religionSelect.appendChild(option);
    }

    // Select the denomination option in the main religion field
    religionSelect.value = denomination;

    // Close the denomination modal
    $('#protestantDenominationModal').modal('hide');
}

// File size validation function
function validateFileSize(event) {
    const file = event.target.files[0];
    const maxSize = 10 * 1024 * 1024; // 10MB in bytes

    if (file && file.size > maxSize) {
        // Clear the file input
        event.target.value = '';

        // Hide preview if it was shown
        const preview = document.getElementById('image-preview');
        if (preview) {
            preview.style.display = 'none';
        }

        // Show warning
        Swal.fire({
            icon: 'warning',
            title: 'File Too Large',
            text: 'The selected file exceeds the maximum size limit of 10MB. Please choose a smaller image.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });

        return false;
    }

    return true;
}

// Image preview function
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}
</script>

</body>
</html>