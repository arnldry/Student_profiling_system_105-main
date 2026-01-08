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
                <h2 class="h3 mb-0">Archived Student List</h2>
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
                    <p class="mb-0">Archived students list here.</p>
                </div>

                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap" id="archivedStudentsTable" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Profile Picture</th>
                                <th>LRN</th>
                                <th>Student Name</th>
                                <th>Grade and Section</th>
                                <th>Curriculum</th>
                                <th class="datatable-nosort">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            {{-- 🔹 Edit Archived Student Modal (Multi-Step Version) --}}
            @foreach($archivedSchoolYears as $sy)
                @php
                    $archivedStudents = \App\Models\ArchivedStudentInformation::where('school_year_id', $sy->id)->get();
                @endphp
                @foreach($archivedStudents as $archivedStudent)
                    <div class="modal fade" id="editArchivedModal{{ $archivedStudent->id }}" tabindex="-1" aria-labelledby="editArchivedModalLabel{{ $archivedStudent->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-white border-bottom">
                                    <h5 class="modal-title text-primary">
                                        <i class="dw dw-edit2 mr-2"></i> Edit Archived Student Info - {{ $archivedStudent->user ? $archivedStudent->user->name : 'N/A' }}
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>

                                <form action="{{ route('admin.update-archived-student-info', $archivedStudent->id) }}" method="POST" enctype="multipart/form-data" class="edit-archived-form" data-archived-id="{{ $archivedStudent->id }}">
                                     @csrf
                                     <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                        {{-- Step Indicators --}}
                                        <div class="edit-form-tabs mb-4">
                                            <div class="edit-form-tab active" data-step="1">
                                                <span class="step-number">1</span>
                                                <span class="step-label">Student Info</span>
                                            </div>
                                            <div class="edit-form-tab" data-step="2">
                                                <span class="step-number">2</span>
                                                <span class="step-label">Mother's Info</span>
                                            </div>
                                            <div class="edit-form-tab" data-step="3">
                                                <span class="step-number">3</span>
                                                <span class="step-label">Father's Info</span>
                                            </div>
                                            <div class="edit-form-tab" data-step="4">
                                                <span class="step-number">4</span>
                                                <span class="step-label">Guardian's Info</span>
                                            </div>
                                            <div class="edit-form-tab" data-step="5">
                                                <span class="step-number">5</span>
                                                <span class="step-label">Agreements</span>
                                            </div>
                                        </div>

                                        {{-- Step 1: Student Information --}}
                                        <div class="edit-form-step active" id="edit-archived-step-1-{{ $archivedStudent->id }}">
                                            <h6 class="text-primary mb-3"><i class="dw dw-user"></i> Student Information</h6>
                                            <div class="row">
                                                <div class="col-md-12 form-group text-center">
                                                    <label>Profile Picture</label><br>
                                                    <input type="file" name="profile_picture" class="form-control d-inline-block" accept="image/*" style="width: auto; max-width: 300px;" onchange="validateFileSize(event)">
                                                    @if($archivedStudent->profile_picture)
                                                        <br><img src="{{ asset($archivedStudent->profile_picture) }}" alt="Current Profile Picture" style="width: 100px; height: 100px; object-fit: cover; margin-top: 10px; border-radius: 5px;">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label>Student Name</label>
                                                    <input type="text" name="student_name" id="edit-archived-student-name-{{ $archivedStudent->id }}" class="form-control"
                                                        value="{{ $archivedStudent->user ? $archivedStudent->user->name : '' }}"
                                                        pattern="[A-Za-zÑñ\s\-\']+"
                                                        title="Only letters (including Ñ/ñ), spaces, hyphens, and apostrophes are allowed"
                                                        required>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>School Year</label>
                                                    <input type="text" name="school_year" class="form-control"
                                                        value="{{ $archivedStudent->schoolYear ? $archivedStudent->schoolYear->school_year : 'N/A' }}" readonly required>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>LRN</label>
                                                    <input type="text" name="lrn" id="edit-archived-lrn-{{ $archivedStudent->id }}" class="form-control" value="{{ $archivedStudent->lrn ?? '' }}" required>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Sex</label>
                                                    <select name="sex" class="form-control" required>
                                                        <option value="">Select</option>
                                                        <option value="Male" {{ ($archivedStudent->sex ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ ($archivedStudent->sex ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Grade</label>
                                                    <select name="grade" class="form-control" required>
                                                        <option value="">Select Grade</option>
                                                        @for($i = 7; $i <= 12; $i++)
                                                            <option value="{{ $i }}" {{ ($archivedStudent->grade ?? '') == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Curriculum / Program</label>
                                                    <select name="curriculum" class="form-control" required>
                                                        <option value="">Select Curriculum</option>
                                                        @foreach($curriculums as $curriculum)
                                                            <option value="{{ $curriculum->name }}" {{ ($archivedStudent->curriculum ?? '') == $curriculum->name ? 'selected' : '' }}>
                                                                {{ $curriculum->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Section</label>
                                                    <input type="text" name="section" class="form-control" value="{{ $archivedStudent->section ?? '' }}" required>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Disability (if any)</label>
                                                    <input type="text" name="disability" class="form-control" value="{{ $archivedStudent->disability ?? '' }}" placeholder="Enter Disability (if any)">
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label class="font-weight-bold">Living with</label>
                                                    @php
                                                        $livingMode = is_array($archivedStudent->living_mode ?? null) ? $archivedStudent->living_mode : [];
                                                    @endphp
                                                    <div class="form-check">
                                                        <input type="checkbox" name="living_mode[]" value="Living with Father" class="form-check-input" {{ in_array('Living with Father', $livingMode) ? 'checked' : '' }}> Father
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" name="living_mode[]" value="Living with Mother" class="form-check-input" {{ in_array('Living with Mother', $livingMode) ? 'checked' : '' }}> Mother
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" name="living_mode[]" value="Living with Other Guardians" class="form-check-input" {{ in_array('Living with Other Guardians', $livingMode) ? 'checked' : '' }}> Guardians
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label>Address</label>
                                                    <input type="text" name="address" class="form-control" value="{{ $archivedStudent->address ?? '' }}" required>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="text" name="contact_number" id="edit-archived-contact-{{ $archivedStudent->id }}" class="form-control" value="{{ $archivedStudent->contact_number ?? '' }}" required>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Birthday</label>
                                                    <input
                                                        type="date"
                                                        name="birthday"
                                                        id="edit-archived-birthday-{{ $archivedStudent->id }}"
                                                        class="form-control"
                                                        value="{{ $archivedStudent && $archivedStudent->birthday ? \Carbon\Carbon::parse($archivedStudent->birthday)->format('Y-m-d') : '' }}"
                                                        required
                                                        onchange="calculateArchivedEditAge({{ $archivedStudent->id }})"
                                                    >
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Age</label>
                                                    <input type="number" name="age" id="edit-archived-age-{{ $archivedStudent->id }}" class="form-control" value="{{ $archivedStudent->age ?? '' }}" readonly required>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Religion</label>
                                                    <input type="text" name="religion" class="form-control" value="{{ $archivedStudent->religion ?? '' }}" required>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Nationality</label>
                                                    <input type="text" name="nationality" class="form-control" value="{{ $archivedStudent->nationality ?? '' }}" required>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Facebook / Messenger</label>
                                                    <input type="text" name="fb_messenger" class="form-control" value="{{ $archivedStudent->fb_messenger ?? '' }}">
                                                </div>

                                            </div>
                                        </div>

                                        {{-- Step 2: Mother's Information --}}
                                        <div class="edit-form-step" id="edit-archived-step-2-{{ $archivedStudent->id }}">
                                            <h6 class="text-primary mb-3"><i class="dw dw-woman"></i> Mother's Information</h6>
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label>Name</label>
                                                    <input type="text" name="mother_name" class="form-control" value="{{ $archivedStudent->mother_name ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Age</label>
                                                    <input type="number" name="mother_age" class="form-control" value="{{ $archivedStudent->mother_age ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Occupation</label>
                                                    <input type="text" name="mother_occupation" class="form-control" value="{{ $archivedStudent->mother_occupation ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Place of Work</label>
                                                    <input type="text" name="mother_place_work" class="form-control" value="{{ $archivedStudent->mother_place_work ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="text" name="mother_contact" id="edit-archived-mother-contact-{{ $archivedStudent->id }}" class="form-control" value="{{ $archivedStudent->mother_contact ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Facebook</label>
                                                    <input type="text" name="mother_fb" class="form-control" value="{{ $archivedStudent->mother_fb ?? '' }}">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Step 3: Father's Information --}}
                                        <div class="edit-form-step" id="edit-archived-step-3-{{ $archivedStudent->id }}">
                                            <h6 class="text-primary mb-3"><i class="dw dw-man"></i> Father's Information</h6>
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label>Name</label>
                                                    <input type="text" name="father_name" class="form-control" value="{{ $archivedStudent->father_name ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Age</label>
                                                    <input type="number" name="father_age" class="form-control" value="{{ $archivedStudent->father_age ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Occupation</label>
                                                    <input type="text" name="father_occupation" class="form-control" value="{{ $archivedStudent->father_occupation ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Place of Work</label>
                                                    <input type="text" name="father_place_work" class="form-control" value="{{ $archivedStudent->father_place_work ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="text" name="father_contact" id="edit-archived-father-contact-{{ $archivedStudent->id }}" class="form-control" value="{{ $archivedStudent->father_contact ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Facebook</label>
                                                    <input type="text" name="father_fb" class="form-control" value="{{ $archivedStudent->father_fb ?? '' }}">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Step 4: Guardian's Information --}}
                                        <div class="edit-form-step" id="edit-archived-step-4-{{ $archivedStudent->id }}">
                                            <h6 class="text-primary mb-3"><i class="dw dw-user"></i> Guardian's Information</h6>
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label>Name</label>
                                                    <input type="text" name="guardian_name" class="form-control" value="{{ $archivedStudent->guardian_name ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Age</label>
                                                    <input type="number" name="guardian_age" class="form-control" value="{{ $archivedStudent->guardian_age ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Occupation</label>
                                                    <input type="text" name="guardian_occupation" class="form-control" value="{{ $archivedStudent->guardian_occupation ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Place of Work</label>
                                                    <input type="text" name="guardian_place_work" class="form-control" value="{{ $archivedStudent->guardian_place_work ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="text" name="guardian_contact" id="edit-archived-guardian-contact-{{ $archivedStudent->id }}" class="form-control" value="{{ $archivedStudent->guardian_contact ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Facebook</label>
                                                    <input type="text" name="guardian_fb" class="form-control" value="{{ $archivedStudent->guardian_fb ?? '' }}">
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label>Relationship</label>
                                                    <input type="text" name="guardian_relationship" class="form-control" value="{{ $archivedStudent->guardian_relationship ?? '' }}" placeholder="e.g., Aunt, Uncle, Grandparent">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Step 5: Agreements --}}
                                        <div class="edit-form-step" id="edit-archived-step-5-{{ $archivedStudent->id }}">
                                            <h6 class="text-primary mb-3"><i class="dw dw-file"></i> Agreements</h6>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="alert alert-info">
                                                        <strong>Agreement Status (Submitted on {{ $archivedStudent->current_date ? $archivedStudent->current_date->format('Y-m-d') : 'N/A' }}):</strong> These agreements were accepted by the student and parent/guardian during initial registration and cannot be modified.
                                                    </div>
                                                </div>

                                                {{-- Student Agreements --}}
                                                <div class="col-md-6 form-group">
                                                    <h6 class="text-primary">For Student</h6>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" disabled {{ $archivedStudent->student_agreement_1 ? 'checked' : '' }}>
                                                        <label class="form-check-label">
                                                            Sumasang-ayon ako sa <strong>Mga Alituntuning Dapat Sundin ng Mag-aaral ng OCNHS</strong>
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" disabled {{ $archivedStudent->student_agreement_2 ? 'checked' : '' }}>
                                                        <label class="form-check-label">
                                                            Sumasang-ayon ako sa <strong>Komitment sa Paaralan</strong>
                                                        </label>
                                                    </div>
                                                </div>

                                                {{-- Parent Agreements --}}
                                                <div class="col-md-6 form-group">
                                                    <h6 class="text-primary">For Parent / Guardian</h6>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" disabled {{ $archivedStudent->parent_agreement_1 ? 'checked' : '' }}>
                                                        <label class="form-check-label">
                                                            Sumasang-ayon ako sa <strong>Mga Tungkulin ng Magulang / Guardian</strong>
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" disabled {{ $archivedStudent->parent_agreement_2 ? 'checked' : '' }}>
                                                        <label class="form-check-label">
                                                            Sumasang-ayon ako sa <strong>Komitment sa Paaralan</strong>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary edit-archived-prev-btn" style="display: none;">
                                            <i class="dw dw-left-arrow2"></i> Previous
                                        </button>
                                        <button type="button" class="btn btn-primary edit-archived-next-btn">
                                            Next <i class="dw dw-right-arrow2"></i>
                                        </button>
                                        <button type="submit" class="btn btn-success edit-archived-submit-btn" style="display: none;">
                                            <i class="dw dw-diskette"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach

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
        document.addEventListener('DOMContentLoaded', function () {
            // Add red asterisk for required labels in archived modals
            document.querySelectorAll('.modal input[required], .modal select[required], .modal textarea[required]').forEach(input => {
                const label = input.closest(".form-group")?.querySelector("label");
                if (label) label.classList.add("required");
            });

            // Initialize multi-step forms for archived modals
            document.querySelectorAll('.edit-archived-form').forEach(form => {
                const modal = form.closest('.modal');
                const archivedId = form.getAttribute('data-archived-id');
                const tabs = modal.querySelectorAll('.edit-form-tab');
                const steps = modal.querySelectorAll('.edit-form-step');
                const nextBtn = modal.querySelector('.edit-archived-next-btn');
                const prevBtn = modal.querySelector('.edit-archived-prev-btn');
                const submitBtn = modal.querySelector('.edit-archived-submit-btn');
                let currentStep = 0;

                // Contact number restrictions - auto-start with "09"
                const contactInput = modal.querySelector(`#edit-archived-contact-${archivedId}`);
                if (contactInput) {
                    // Auto-start with "09" when field is focused and empty
                    contactInput.addEventListener('focus', function () {
                        if (this.value === '' || this.value.length === 0) {
                            this.value = '09';
                        } else if (!this.value.startsWith('09')) {
                            // If existing value doesn't start with 09, prepend it
                            this.value = '09' + this.value.replace(/[^0-9]/g, '').replace(/^09/, '');
                        }
                    });

                    // Ensure it always starts with "09"
                    contactInput.addEventListener('input', function () {
                        let value = this.value.replace(/[^0-9]/g, '');

                        // If it doesn't start with 09, prepend it
                        if (!value.startsWith('09')) {
                            value = '09' + value.replace(/^09/, '');
                        }

                        // Limit to 11 digits (09 + 9 more digits)
                        this.value = value.slice(0, 11);
                    });

                    // Prevent typing if already at max length
                    contactInput.addEventListener('keypress', function (e) {
                        if (!/[0-9]/.test(e.key) || this.value.length >= 11) {
                            e.preventDefault();
                        }
                    });

                    // Ensure "09" prefix on blur if field is not empty
                    contactInput.addEventListener('blur', function () {
                        if (this.value && !this.value.startsWith('09')) {
                            let value = this.value.replace(/[^0-9]/g, '');
                            this.value = '09' + value.replace(/^09/, '').slice(0, 9);
                        }
                    });
                }

                // Father, Mother, and Guardian contact numbers - auto-start with "09"
                const fatherContactInput = modal.querySelector(`#edit-archived-father-contact-${archivedId}`);
                const motherContactInput = modal.querySelector(`#edit-archived-mother-contact-${archivedId}`);
                const guardianContactInput = modal.querySelector(`#edit-archived-guardian-contact-${archivedId}`);

                [fatherContactInput, motherContactInput, guardianContactInput].forEach(input => {
                    if (!input) return;

                    input.addEventListener('focus', function () {
                        if (this.value === '' || this.value.length === 0) {
                            this.value = '09';
                        } else if (!this.value.startsWith('09')) {
                            this.value = '09' + this.value.replace(/[^0-9]/g, '').replace(/^09/, '');
                        }
                    });

                    input.addEventListener('input', function () {
                        let value = this.value.replace(/[^0-9]/g, '');
                        if (!value.startsWith('09')) {
                            value = '09' + value.replace(/^09/, '');
                        }
                        this.value = value.slice(0, 11);
                    });

                    input.addEventListener('keypress', function (e) {
                        if (!/[0-9]/.test(e.key) || this.value.length >= 11) {
                            e.preventDefault();
                        }
                    });

                    input.addEventListener('blur', function () {
                        if (this.value && !this.value.startsWith('09')) {
                            let value = this.value.replace(/[^0-9]/g, '');
                            this.value = '09' + value.replace(/^09/, '').slice(0, 9);
                        }
                    });
                });

                // LRN validation
                const lrnInput = modal.querySelector(`#edit-archived-lrn-${archivedId}`);
                if (lrnInput) {
                    lrnInput.addEventListener("input", function () {
                        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);
                    });
                }

                // Student name validation - only letters (including Ñ/ñ), spaces, hyphens, and apostrophes
                const studentNameInput = modal.querySelector(`#edit-archived-student-name-${archivedId}`);
                if (studentNameInput) {
                    studentNameInput.addEventListener("input", function () {
                        // Allow only letters (including Ñ/ñ), spaces, and common name characters (hyphens, apostrophes)
                        this.value = this.value.replace(/[^A-Za-zÑñ\s\-\']/g, '');
                    });
                    studentNameInput.addEventListener("keypress", function (e) {
                        // Allow letters (including Ñ/ñ), spaces, hyphens, apostrophes, and backspace/delete
                        const char = String.fromCharCode(e.which || e.keyCode);
                        if (!/[A-Za-zÑñ\s\-\']/.test(char) && !e.ctrlKey && !e.metaKey && e.keyCode !== 8 && e.keyCode !== 46) {
                            e.preventDefault();
                        }
                    });
                }

                // Function to show a specific step
                function showArchivedStep(stepIndex) {
                    // Hide all steps
                    steps.forEach((step, index) => {
                        step.classList.toggle('active', index === stepIndex);
                    });

                    // Update tabs
                    tabs.forEach((tab, index) => {
                        tab.classList.toggle('active', index === stepIndex);
                    });

                    // Update buttons
                    if (stepIndex === 0) {
                        prevBtn.style.display = 'none';
                        nextBtn.style.display = 'inline-block';
                        submitBtn.style.display = 'none';
                    } else if (stepIndex === steps.length - 1) {
                        prevBtn.style.display = 'inline-block';
                        nextBtn.style.display = 'none';
                        submitBtn.style.display = 'inline-block';
                    } else {
                        prevBtn.style.display = 'inline-block';
                        nextBtn.style.display = 'inline-block';
                        submitBtn.style.display = 'none';
                    }

                    currentStep = stepIndex;
                }

                // Next button click
                if (nextBtn) {
                    nextBtn.addEventListener('click', async function(e) {
                        e.preventDefault();
                        if (!await validateArchivedStep(steps[currentStep], archivedId)) return;
                        if (currentStep < steps.length - 1) {
                            showArchivedStep(currentStep + 1);
                        }
                    });
                }

                // Previous button click
                if (prevBtn) {
                    prevBtn.addEventListener('click', function() {
                        if (currentStep > 0) {
                            showArchivedStep(currentStep - 1);
                        }
                    });
                }

                // Tab click to navigate
                tabs.forEach((tab, index) => {
                    tab.addEventListener('click', function() {
                        showArchivedStep(index);
                    });
                });

                // Initialize - show first step
                showArchivedStep(0);

                // Reset to first step when modal is opened
                modal.addEventListener('shown.bs.modal', function() {
                    showArchivedStep(0);
                    // Re-add required class to labels when modal opens
                    modal.querySelectorAll("input[required], select[required], textarea[required]").forEach(input => {
                        const label = input.closest(".form-group")?.querySelector("label");
                        if (label) label.classList.add("required");
                    });

                    // Format existing contact numbers to start with "09" if they don't
                    const allContactInputs = [
                        modal.querySelector(`#edit-archived-contact-${archivedId}`),
                        modal.querySelector(`#edit-archived-father-contact-${archivedId}`),
                        modal.querySelector(`#edit-archived-mother-contact-${archivedId}`),
                        modal.querySelector(`#edit-archived-guardian-contact-${archivedId}`)
                    ];

                    allContactInputs.forEach(input => {
                        if (input && input.value && !input.value.startsWith('09')) {
                            let value = input.value.replace(/[^0-9]/g, '');
                            if (value) {
                                input.value = '09' + value.replace(/^09/, '').slice(0, 9);
                            }
                        }
                    });
                });
            });

            // Validate archived step function
            async function validateArchivedStep(stepElement, archivedId) {
                const inputs = stepElement.querySelectorAll("input[required], select[required], textarea[required]");
                for (let input of inputs) {
                    if (!input.checkValidity()) {
                        input.reportValidity();
                        return false;
                    }
                }

                // Validate LRN
                const lrnInput = stepElement.querySelector(`#edit-archived-lrn-${archivedId}`);
                if (lrnInput) {
                    const lrn = lrnInput.value.trim();
                    if (lrn === '' || lrn.length < 11 || lrn.length > 12) {
                        await Swal.fire({
                            icon: 'warning',
                            title: 'Invalid LRN',
                            text: 'LRN must be 11–12 digits long.'
                        });
                        return false;
                    }
                }

                // Validate Mode of Living (at least one checkbox must be checked)
                if (stepElement.id.includes('edit-archived-step-1')) {
                    const checkboxes = stepElement.querySelectorAll("input[name='living_mode[]']");
                    const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                    if (!anyChecked) {
                        await Swal.fire({
                            icon: 'warning',
                            title: 'Oops!',
                            text: 'Please select at least one Mode of Living.'
                        });
                        return false;
                    }
                }

                return true;
            }

            // Calculate age function for archived
            window.calculateArchivedEditAge = function(archivedId) {
                const birthday = document.querySelector(`#edit-archived-birthday-${archivedId}`).value;
                if (birthday) {
                    const birthDate = new Date(birthday);
                    const today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const m = today.getMonth() - birthDate.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
                    document.querySelector(`#edit-archived-age-${archivedId}`).value = age;
                }
            };

            // Form submission with confirmation for archived
            document.querySelectorAll('.edit-archived-form').forEach(form => {
                const handleSubmit = async function (e) {
                    e.preventDefault();

                    const modal = form.closest('.modal');
                    const archivedId = form.getAttribute('data-archived-id');
                    const steps = modal.querySelectorAll('.edit-form-step');

                    // Validate all steps before submission
                    for (let step of steps) {
                        if (!await validateArchivedStep(step, archivedId)) {
                            // Show the step that failed validation
                            const stepIndex = Array.from(steps).indexOf(step);
                            const tabs = modal.querySelectorAll('.edit-form-tab');
                            steps.forEach((s, i) => {
                                s.classList.toggle('active', i === stepIndex);
                            });
                            tabs.forEach((t, i) => {
                                t.classList.toggle('active', i === stepIndex);
                            });
                            return;
                        }
                    }

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to save these changes to the archived student?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, save it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit via AJAX
                            const formData = new FormData(form);
                            fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonColor: '#28a745'
                                    }).then(() => {
                                        // Reload the table if there's an active school year
                                        const activeYearBtn = document.querySelector('.show-students.active-year');
                                        if (activeYearBtn) {
                                            activeYearBtn.click();
                                        }
                                        // Close the modal
                                        const modal = form.closest('.modal');
                                        if (modal) {
                                            $(modal).modal('hide');
                                        }
                                    });
                                } else {
                                    let message = data.message || 'An error occurred while saving.';
                                    if (data.errors) {
                                        let errorMessages = [];
                                        for (let field in data.errors) {
                                            errorMessages.push(...data.errors[field]);
                                        }
                                        message += '<br>' + errorMessages.join('<br>');
                                    }
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        html: message
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'An error occurred while saving.'
                                });
                            });
                        }
                    });
                };
                form.addEventListener('submit', handleSubmit);
            });

            // Success alert after saving archived student
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session("success") }}',
                    confirmButtonColor: '#28a745'
                });
            @endif
        });

        // File size validation function
        function validateFileSize(event) {
            const file = event.target.files[0];
            const maxSize = 10 * 1024 * 1024; // 10MB in bytes

            if (file && file.size > maxSize) {
                // Clear the file input
                event.target.value = '';

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

        $(document).ready(function () {
            let table = $('#archivedStudentsTable').DataTable({
                responsive: true,
                columns: [
                    { data: 'profile_picture', orderable: false, searchable: false },
                    { data: 'lrn' },
                    { data: 'name' },
                    { data: 'grade_section' },
                    { data: 'curriculum' },
                    { data: 'action', orderable: false, searchable: false }
                ]
            });

            let currentActiveYear = null;

            $('.show-students').click(function () {
                let schoolYearId = $(this).data('id');
                let schoolYearText = $(this).text().trim();

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
                                profile_picture: student.profile_picture ? `<div class="profile-pic-container" onclick="viewProfilePicture('/${student.profile_picture}', '${student.user ? student.user.name : 'N/A'}', '${student.lrn || 'N/A'}')"><img src="/${student.profile_picture}" alt="Profile Picture" class="profile-pic"></div>` : '<div class="profile-pic-container no-image"><i class="dw dw-user"></i></div>',
                                lrn: student.lrn,
                                name: student.user ? student.user.name : 'N/A',
                                grade_section: `${student.grade} / ${student.section}`,
                                curriculum: student.curriculum,
                                action: `<button class="btn btn-info btn-sm" onclick="viewArchivedInfo(${student.id}, '${student.user ? student.user.name : ''}')">
                                           <i class="dw dw-eye"></i> View
                                         </button>
                                         <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editArchivedModal${student.id}">
                                           <i class="dw dw-edit2"></i> Edit
                                         </button>`,
                                profile_picture_url: student.profile_picture ? `/${student.profile_picture}` : null
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
              let learnerFullName = '<span class="na-placeholder">N/A</span>';
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
                    let formattedBirthday = '<span class="na-placeholder">N/A</span>';
                    let formattedAge = '<span class="na-placeholder">N/A</span>';
                    
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
                                <td colspan="3">School Year: ${data.school_year_name || '<span class="na-placeholder">N/A</span>'}</td>
                                <td colspan="3">Curriculum/Program: ${data.curriculum || '<span class="na-placeholder">N/A</span>'}</td>
                                <td colspan="4">Grade & Section: ${data.grade || '<span class="na-placeholder">N/A</span>'}/${data.section || '<span class="na-placeholder">N/A</span>'}</td>
                                <td colspan="2">Sex: ${data.sex || '<span class="na-placeholder">N/A</span>'}</td>
                            </tr>

                            <tr>
                                <td colspan="1" rowspan="2" class="section-title">LEARNER'S NAME</td>
                                <td colspan="5">${learnerFullName}</td>
                                <td colspan="2">Mode of Living:</td>
                                <td colspan="4">${data.living_mode ? data.living_mode.join(', ') : '<span class="na-placeholder">N/A</span>'}</td>
                            </tr>
                            <tr>
                            <td colspan="2"> <span>Family Name</span></td>
                            <td colspan="1">  <span>First Name</span>             </td>
                            <td colspan="2" > <span>Middle Name</span></td>

                                <td colspan="2">Disability(if any):</td><td colspan="3">${data.disability || '<span class="na-placeholder">N/A</span>'}</td>
                            </tr>

                            <tr>
                                <td colspan="2">Complete Address:</td><td colspan="10">${data.address || '<span class="na-placeholder">N/A</span>'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Mobile Number:</td><td colspan="4">${data.contact_number || '<span class="na-placeholder">N/A</span>'}</td>
                                <td colspan="2">FB/Messenger:</td>
                                <td colspan="4">${data.fb_messenger || '<span class="na-placeholder">N/A</span>'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Birthday & Age:</td><td colspan="2">${formattedBirthday} (${formattedAge})</td>
                                <td colspan="1">Religion:</td><td colspan="2">${data.religion || '<span class="na-placeholder">N/A</span>'}</td>
                                <td colspan="1">Nationality:</td>
                                <td colspan="4">${data.nationality || '<span class="na-placeholder">N/A</span>'}</td>
                            </tr>

                            <tr>
                                <td class="section-title" colspan="2">Father's Name</td><td colspan="4">${data.father_name || '<span class="na-placeholder">N/A</span>'}</td>
                                <td colspan="1">Age:</td><td colspan="5">${data.father_age || '<span class="na-placeholder">N/A</span>'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Occupation/Work:</td><td colspan="4">${data.father_occupation || '<span class="na-placeholder">N/A</span>'}</td>
                                <td colspan="2">Mobile Number:</td><td colspan="4">${data.father_contact || '<span class="na-placeholder">N/A</span>'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">FB/Messenger:</td><td colspan="4">${data.father_fb || '<span class="na-placeholder">N/A</span>'}</td>
                                <td colspan="2">Place of Work:</td><td colspan="4">${data.father_place_work || '<span class="na-placeholder">N/A</span>'}</td>
                            </tr>

                            <tr>
                                <td class="section-title" colspan="2">Mother's Name</td><td colspan="4">${data.mother_name || '<span class="na-placeholder">N/A</span>'}</td>
                                <td colspan="1">Age:</td><td colspan="5">${data.mother_age || '<span class="na-placeholder">N/A</span>'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Occupation/Work:</td><td colspan="4">${data.mother_occupation || '<span class="na-placeholder">N/A</span>'}</td>
                                <td colspan="2">Mobile Number:</td><td colspan="4">${data.mother_contact || '<span class="na-placeholder">N/A</span>'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">FB/Messenger:</td><td colspan="4">${data.mother_fb || '<span class="na-placeholder">N/A</span>'}</td>
                                <td colspan="2">Place of Work:</td><td colspan="4">${data.mother_place_work || '<span class="na-placeholder">N/A</span>'}</td>
                            </tr>
                            <tr>
                                <td class="section-title" colspan="2">Guardian's Name</td><td colspan="4">${data.guardian_name || '<span class="na-placeholder">N/A</span>'}</td>
                                <td colspan="1">Age:</td><td colspan="5">${data.guardian_age || '<span class="na-placeholder">N/A</span>'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Occupation/Work:</td><td colspan="4">${data.guardian_occupation || '<span class="na-placeholder">N/A</span>'}</td>
                                <td colspan="2">Mobile Number:</td><td colspan="4">${data.guardian_contact || '<span class="na-placeholder">N/A</span>'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">FB/Messenger:</td><td colspan="4">${data.guardian_fb || '<span class="na-placeholder">N/A</span>'}</td>
                                <td colspan="2">Place of Work:</td><td colspan="4">${data.guardian_place_work || '<span class="na-placeholder">N/A</span>'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Relationship:</td><td colspan="10">${data.guardian_relationship || '<span class="na-placeholder">N/A</span>'}</td>
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
                                                <div style="height: 10px;"></div>
                                                <div style="font-size: 14px; margin-bottom: 1px;">${data.current_date_formatted}</div>
                                                <div style="border-bottom: 1px solid #000; height: 1em;"></div>
                                                <div style="margin-top: 2px;">(Petsa)</div>
                                            </div>
                                            <div style="text-align: center; flex-basis: 45%;">
                                                <div style="height: 5px;"></div>
                                                <div style="margin-bottom: 10px; font-size: 11px;">
                                                    <strong>Agreement Status:</strong>
                                                    <span style="margin-left: 20px;">Parent Agreements: ${data.agreements.parent_agreement_1 && data.agreements.parent_agreement_2 ? '✓ Accepted' : '<span class="na-placeholder">N/A</span>'}</span>
                                                </div>
                                                <div style="border-bottom: 1px solid #000; height: 1em;"></div>
                                                <div style="margin-top: 2px;">Lagda ng magulang/guardian</div>
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
                                  <p style="font-size: 10px; text-align: center; margin: 0;">
                                      <strong>Agreement Status:</strong>
                                      <span style="margin-left: 10px;">Student Agreements: ${data.agreements.student_agreement_1 && data.agreements.student_agreement_2 ? '✓ Accepted' : '<span class="na-placeholder">N/A</span>'}</span>
                                  </p>
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
                                                <div style="font-size: 14px; margin-bottom: 1px;">${data.current_date_formatted}</div>
                                                <div style="border-bottom: 1px solid #000; height: 1em;"></div>
                                                <div style="margin-top: 2px;">(Petsa)</div>
                                            </div>
                                            <div style="text-align: center; flex-basis: 45%;">
                                                <div style="height: 5px;"></div>
                                                <div style="margin-bottom: 10px; font-size: 11px;">
                                                    <strong>Agreement Status:</strong>
                                                    <span style="margin-left: 10px;">Student Agreements: ${data.agreements.student_agreement_1 && data.agreements.student_agreement_2 ? '✓ Accepted' : '✗ Not Accepted'}</span>
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
                        width: '70%',
                        heightAuto: false,
                        customClass: {
                            popup: 'swal-form-popup scrollable-modal',
                            actions: 'swal-actions-custom'
                        },
                        showCloseButton: true,
                        confirmButtonText: 'Close',
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
                                        printStudentProfile(frontContent, backContent, learnerFullName);
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

        function viewProfilePicture(imageSrc, studentName, lrn) {
            Swal.fire({
                html: `<div class="school-id-card">
                          <div class="profile-image-container">
                              <img src="${imageSrc}" alt="Profile Picture" class="profile-picture-modal">
                          </div>
                          <div class="student-info">
                              <h4 class="student-name">${studentName}</h4>
                              <span class="lrn-badge">LRN: ${lrn}</span>
                          </div>
                       </div>`,
                showCloseButton: true,
                showConfirmButton: false,
                customClass: {
                    popup: 'profile-picture-popup'
                },
                width: '450px',
                padding: '0',
                background: '#fff',
                backdrop: 'rgba(0,0,0,0.8)',
                showClass: {
                    popup: 'animate__animated animate__zoomIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut'
                }
            });
        }

        function printStudentProfile(frontContent, backContent, studentName) {
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
                                page-break-before: always;
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
                            text-align: center;
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
                        .na-placeholder {
                            color: #666 !important;
                            font-style: italic;
                            font-size: 0.85em;
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
        /* Multi-step form styles */
        .edit-form-tabs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            position: relative;
        }

        .edit-form-tab {
            flex: 1;
            text-align: center;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .edit-form-tab.active {
            background: #e8f0fe;
            border: 2px solid #007bff;
        }

        .edit-form-tab .step-number {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            font-weight: bold;
            margin-right: 8px;
        }

        .edit-form-tab.active .step-number {
            background: #007bff;
            color: white;
        }

        .edit-form-tab .step-label {
            font-weight: 500;
            color: #6c757d;
        }

        .edit-form-tab.active .step-label {
            color: #007bff;
            font-weight: 600;
        }

        .edit-form-step {
            display: none;
        }

        .edit-form-step.active {
            display: block;
        }

        label.required::after {
            content: " *";
            color: red;
            font-weight: bold;
        }

        .swal-form-popup {
            background: #fff;
            font-family: 'Arial', sans-serif;
            color: #000;
        }

        .scrollable-modal .swal2-popup {
            max-height: 80vh;
            overflow-y: auto;
        }

        .na-placeholder {
            color: #999 !important;
            font-style: italic;
            font-size: 0.9em;
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
            text-align: center;
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

        .profile-picture-popup {
            background: #fff !important;
            border-radius: 12px !important;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2) !important;
            border: 2px solid #007bff !important;
        }

        /* Profile Picture Styles */
        .profile-pic-container {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #e9ecef;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            background: #f8f9fa;
        }

        .profile-pic-container:hover {
            border-color: #007bff;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .profile-pic {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .profile-pic-container:hover .profile-pic {
            transform: scale(1.1);
        }

        .profile-pic-container.no-image {
            background: #e9ecef;
            border-color: #dee2e6;
            color: #6c757d;
        }

        .profile-pic-container.no-image i {
            font-size: 24px;
        }

        .profile-pic-container.no-image:hover {
            border-color: #6c757d;
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        .school-id-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px;
        }

        .profile-image-container {
            margin-bottom: 25px;
        }

        .profile-picture-modal {
            width: 250px;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .student-info {
            text-align: center;
            width: 100%;
        }

        .student-info .student-name {
            margin: 0 0 10px 0;
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }

        .student-info .lrn-badge {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 500;
        }
    </style>

</body>
</html>
