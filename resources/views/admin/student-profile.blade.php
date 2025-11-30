<!DOCTYPE html>
<html>
<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Student Profile</title>

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
                <h2 class="h3 mb-0">Student List</h2>
            </div>

            <div class="card-box mb-30">
                <div class="pd-20">
                    <p class="mb-0">Students list here.</p>
                </div>

                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>Profile</th>
                                <th>LRN</th>
                                <th>Student Name</th>
                                <th>Grade and Section</th>
                                <th>Curriculum</th>
                                <th class="datatable-nosort">Action</th>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                @if($user->role === 'student')
                                <tr>
                                    <td>
                                        @php
                                            $info = $user->additionalInfo;
                                            $profileSrc = ($info && $info->profile_picture) ? asset($info->profile_picture) : '/vendors/images/logo-ocnhs.png';
                                        @endphp
                                        <img src="{{ $profileSrc }}" alt="Profile" style="width:50px;height:50px;object-fit:cover;border-radius:50%;border:1px solid #ddd;">
                                    </td>
                                    <td>
                                        {{ $info ? $info->lrn : '-' }}
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $info ? $info->grade . ' / ' . $info->section : '-' }}</td>
                                    <td>{{ $info ? $info->curriculum : '-' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" onclick="viewAdditionalInfo({{ $user->id }}, '{{ $user->name }}')">
                                            <i class="dw dw-eye"></i> View
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editInfoModal{{ $user->id }}">
                                            <i class="dw dw-edit2"></i> Edit
                                        </button>
                                    </td>

                                </tr>
                                    {{-- 🔹 Edit Info Modal (Multi-Step Version) --}}
                                    <div class="modal fade" id="editInfoModal{{ $user->id }}" tabindex="-1" aria-labelledby="editInfoModalLabel{{ $user->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header bg-white border-bottom">
                                                    <h5 class="modal-title text-primary">
                                                        <i class="dw dw-edit2 mr-2"></i> Edit Student Info - {{ $user->name }}
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>

                                                <form action="{{ route('admin.update-student-info', $user->id) }}" method="POST" enctype="multipart/form-data" class="edit-student-form" data-user-id="{{ $user->id }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        {{-- Step Indicators --}}
                                                        @php
                                                            // Normalize living_mode into an array for this modal
                                                            $livingMode = [];
                                                            if (isset($info->living_mode)) {
                                                                if (is_array($info->living_mode)) {
                                                                    $livingMode = $info->living_mode;
                                                                } elseif (is_string($info->living_mode)) {
                                                                    $decoded = json_decode($info->living_mode, true);
                                                                    $livingMode = is_array($decoded) ? $decoded : [];
                                                                }
                                                            }
                                                        @endphp
                                                        <div class="edit-form-tabs mb-4">
                                                            <div class="edit-form-tab active" data-step="1">
                                                                <span class="step-number">1</span>
                                                                <span class="step-label">Student Info</span>
                                                            </div>
                                                            <div class="edit-form-tab" data-step="2" @if(!in_array('Living with Mother', $livingMode)) style="display:none" data-skip="1" @endif>
                                                                <span class="step-number">2</span>
                                                                <span class="step-label">Mother's Info</span>
                                                            </div>
                                                            <div class="edit-form-tab" data-step="3" @if(!in_array('Living with Father', $livingMode)) style="display:none" data-skip="1" @endif>
                                                                <span class="step-number">3</span>
                                                                <span class="step-label">Father's Info</span>
                                                            </div>
                                                            <div class="edit-form-tab" data-step="4" @if(!in_array('Living with Other Guardians', $livingMode)) style="display:none" data-skip="1" @endif>
                                                                <span class="step-number">4</span>
                                                                <span class="step-label">Guardian Info</span>
                                                            </div>
                                                            <div class="edit-form-tab" data-step="5">
                                                                <span class="step-number">5</span>
                                                                <span class="step-label">Agreements</span>
                                                            </div>
                                                        </div>

                                                        {{-- Step 1: Student Information --}}
                                                        <div class="edit-form-step active" id="edit-step-1-{{ $user->id }}">
                                                            <h6 class="text-primary mb-3"><i class="dw dw-user"></i> Student Information</h6>
                                                            <div class="row">
                                                                <div class="col-md-12 form-group text-center">
                                                                    @php
                                                                        $profileSrc = ($info && $info->profile_picture) ? asset($info->profile_picture) : '/vendors/images/logo-ocnhs.png';
                                                                    @endphp
                                                                    <label>Profile Photo</label>
                                                                    <div style="margin-bottom:8px;">
                                                                        <img id="edit-profile-preview-{{ $user->id }}" src="{{ $profileSrc }}" alt="Profile" style="width:120px;height:120px;object-fit:cover;border-radius:6px;border:1px solid #ddd;">
                                                                    </div>
                                                                    <input type="file" name="profile_picture" id="edit-profile-photo-{{ $user->id }}" accept="image/*" class="form-control-file" />
                                                                    <small class="form-text text-muted">Max 2MB. JPG, PNG only. Admin may change student photo here.</small>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Student Name</label>
                                                                    <input type="text" name="student_name" id="edit-student-name-{{ $user->id }}" class="form-control" 
                                                                        value="{{ $user->name ?? '' }}" 
                                                                        pattern="[A-Za-zÑñ\s\-\']+" 
                                                                        title="Only letters (including Ñ/ñ), spaces, hyphens, and apostrophes are allowed"
                                                                        required>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>School Year</label>
                                                                    <input type="text" name="school_year" class="form-control" 
                                                                        value="{{ $info->schoolYear->school_year ?? 'N/A' }}" readonly required>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>LRN</label>
                                                                    <input type="text" name="lrn" id="edit-lrn-{{ $user->id }}" class="form-control" value="{{ $info->lrn ?? '' }}" required>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Sex</label>
                                                                    <select name="sex" class="form-control" required>
                                                                        <option value="">Select</option>
                                                                        <option value="Male" {{ ($info->sex ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                                                        <option value="Female" {{ ($info->sex ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Grade</label>
                                                                    <select name="grade" class="form-control" required>
                                                                        <option value="">Select Grade</option>
                                                                        @for($i = 7; $i <= 12; $i++)
                                                                            <option value="{{ $i }}" {{ ($info->grade ?? '') == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                                                                        @endfor
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Curriculum / Program</label>
                                                                    <select name="curriculum" class="form-control" required>
                                                                        <option value="">Select Curriculum</option>
                                                                        @foreach($curriculums as $curriculum)
                                                                            <option value="{{ $curriculum->name }}" {{ ($info->curriculum ?? '') == $curriculum->name ? 'selected' : '' }}>
                                                                                {{ $curriculum->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Section</label>
                                                                    <input type="text" name="section" class="form-control" value="{{ $info->section ?? '' }}" required>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Disability (if any)</label>
                                                                    <input type="text" name="disability" class="form-control" value="{{ $info->disability ?? '' }}" placeholder="Enter Disability (if any)">
                                                                </div>
                                                                <div class="col-md-12 form-group">
                                                                    <label class="font-weight-bold">Living with</label>
                                                                    @php
                                                                        $livingMode = is_array($info->living_mode ?? null) ? $info->living_mode : [];
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
                                                                    <input type="text" name="address" class="form-control" value="{{ $info->address ?? '' }}" required>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Mobile Number</label>
                                                                    <input type="text" name="contact_number" id="edit-contact-{{ $user->id }}" class="form-control" value="{{ $info->contact_number ?? '' }}" required>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Birthday</label>
                                                                    <input 
                                                                        type="date" 
                                                                        name="birthday" 
                                                                        id="edit-birthday-{{ $user->id }}"
                                                                        class="form-control"
                                                                        value="{{ $info && $info->birthday ? \Carbon\Carbon::parse($info->birthday)->format('Y-m-d') : '' }}"
                                                                        required
                                                                        onchange="calculateEditAge({{ $user->id }})"
                                                                    >
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Age</label>
                                                                    <input type="number" name="age" id="edit-age-{{ $user->id }}" class="form-control" value="{{ $info->age ?? '' }}" readonly required>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Religion</label>
                                                                    <input type="text" name="religion" class="form-control" value="{{ $info->religion ?? '' }}" required>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Nationality</label>
                                                                    <input type="text" name="nationality" class="form-control" value="{{ $info->nationality ?? '' }}" required>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Facebook / Messenger</label>
                                                                    <input type="text" name="fb_messenger" class="form-control" value="{{ $info->fb_messenger ?? '' }}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Step 2: Mother's Information --}}
                                                        <div class="edit-form-step" id="edit-step-2-{{ $user->id }}" @if(!in_array('Living with Mother', $livingMode)) style="display:none" data-skip="1" @endif>
                                                            <h6 class="text-primary mb-3"><i class="dw dw-woman"></i> Mother's Information</h6>
                                                            <div class="row">
                                                                <div class="col-md-6 form-group">
                                                                    <label>Name</label>
                                                                    <input type="text" name="mother_name" class="form-control" value="{{ $info->mother_name ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Age</label>
                                                                    <input type="number" name="mother_age" class="form-control" value="{{ $info->mother_age ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Occupation</label>
                                                                    <input type="text" name="mother_occupation" class="form-control" value="{{ $info->mother_occupation ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Place of Work</label>
                                                                    <input type="text" name="mother_place_work" class="form-control" value="{{ $info->mother_place_work ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Mobile Number</label>
                                                                    <input type="text" name="mother_contact" id="edit-mother-contact-{{ $user->id }}" class="form-control" value="{{ $info->mother_contact ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Facebook</label>
                                                                    <input type="text" name="mother_fb" class="form-control" value="{{ $info->mother_fb ?? '' }}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Step 3: Father's Information --}}
                                                        <div class="edit-form-step" id="edit-step-3-{{ $user->id }}" @if(!in_array('Living with Father', $livingMode)) style="display:none" data-skip="1" @endif>
                                                            <h6 class="text-primary mb-3"><i class="dw dw-man"></i> Father's Information</h6>
                                                            <div class="row">
                                                                <div class="col-md-6 form-group">
                                                                    <label>Name</label>
                                                                    <input type="text" name="father_name" class="form-control" value="{{ $info->father_name ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Age</label>
                                                                    <input type="number" name="father_age" class="form-control" value="{{ $info->father_age ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Occupation</label>
                                                                    <input type="text" name="father_occupation" class="form-control" value="{{ $info->father_occupation ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Place of Work</label>
                                                                    <input type="text" name="father_place_work" class="form-control" value="{{ $info->father_place_work ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Mobile Number</label>
                                                                    <input type="text" name="father_contact" id="edit-father-contact-{{ $user->id }}" class="form-control" value="{{ $info->father_contact ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Facebook</label>
                                                                    <input type="text" name="father_fb" class="form-control" value="{{ $info->father_fb ?? '' }}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Step 4: Guardian Information --}}
                                                        <div class="edit-form-step" id="edit-step-4-{{ $user->id }}" @if(!in_array('Living with Other Guardians', $livingMode)) style="display:none" data-skip="1" @endif>
                                                            <h6 class="text-primary mb-3"><i class="dw dw-user-1"></i> Guardian's Information</h6>
                                                            <div class="row">
                                                                <div class="col-md-6 form-group">
                                                                    <label>Name</label>
                                                                    <input type="text" name="guardian_name" class="form-control" value="{{ $info->guardian_name ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Age</label>
                                                                    <input type="number" name="guardian_age" class="form-control" value="{{ $info->guardian_age ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Occupation</label>
                                                                    <input type="text" name="guardian_occupation" class="form-control" value="{{ $info->guardian_occupation ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Place of Work</label>
                                                                    <input type="text" name="guardian_place_work" class="form-control" value="{{ $info->guardian_place_work ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Mobile Number</label>
                                                                    <input type="text" name="guardian_contact" class="form-control" value="{{ $info->guardian_contact ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Facebook</label>
                                                                    <input type="text" name="guardian_fb" class="form-control" value="{{ $info->guardian_fb ?? '' }}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Step 5: Agreements --}}
                                                        <div class="edit-form-step" id="edit-step-5-{{ $user->id }}">
                                                            <h6 class="text-primary mb-3"><i class="dw dw-file"></i> Agreements</h6>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="alert alert-info">
                                                                        <strong>Agreement Status (Submitted on {{ $info->current_date ? $info->current_date->format('Y-m-d') : 'N/A' }}):</strong> These agreements were accepted by the student and parent/guardian during initial registration and cannot be modified.
                                                                    </div>
                                                                </div>

                                                                {{-- Student Agreements --}}
                                                                <div class="col-md-6 form-group">
                                                                    <h6 class="text-primary">For Student</h6>
                                                                    <div class="form-check">
                                                                        <input type="checkbox" class="form-check-input" disabled {{ $info->student_agreement_1 ? 'checked' : '' }}>
                                                                        <label class="form-check-label">
                                                                            Sumasang-ayon ako sa <strong>Mga Alituntuning Dapat Sundin ng Mag-aaral ng OCNHS</strong>
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="checkbox" class="form-check-input" disabled {{ $info->student_agreement_2 ? 'checked' : '' }}>
                                                                        <label class="form-check-label">
                                                                            Sumasang-ayon ako sa <strong>Komitment sa Paaralan</strong>
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                {{-- Parent Agreements --}}
                                                                <div class="col-md-6 form-group">
                                                                    <h6 class="text-primary">For Parent / Guardian</h6>
                                                                    <div class="form-check">
                                                                        <input type="checkbox" class="form-check-input" disabled {{ $info->parent_agreement_1 ? 'checked' : '' }}>
                                                                        <label class="form-check-label">
                                                                            Sumasang-ayon ako sa <strong>Mga Tungkulin ng Magulang / Guardian</strong>
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="checkbox" class="form-check-input" disabled {{ $info->parent_agreement_2 ? 'checked' : '' }}>
                                                                        <label class="form-check-label">
                                                                            Sumasang-ayon ako sa <strong>Komitment sa Paaralan</strong>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary edit-prev-btn" style="display: none;">
                                                            <i class="dw dw-left-arrow2"></i> Previous
                                                        </button>
                                                        <button type="button" class="btn btn-primary edit-next-btn">
                                                            Next <i class="dw dw-right-arrow2"></i>
                                                        </button>
                                                        <button type="submit" class="btn btn-success edit-submit-btn" style="display: none;">
                                                            <i class="dw dw-diskette"></i> Save Changes
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>        
        </div>
    </div>

    <!-- js -->
    <script src="/vendors/scripts/core.js"></script>
    <script src="/vendors/scripts/script.min.js"></script>
    <script src="/vendors/scripts/process.js"></script>
    <script src="/vendors/scripts/layout-settings.js"></script>
    <script src="/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <!-- buttons for Export datatable -->
    <script src="/src/plugins/datatables/js/dataTables.buttons.min.js"></script>
    <script src="/src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
    <script src="/src/plugins/datatables/js/buttons.print.min.js"></script>
    <script src="/src/plugins/datatables/js/buttons.html5.min.js"></script>
    <script src="/src/plugins/datatables/js/buttons.flash.min.js"></script>
    <script src="/src/plugins/datatables/js/pdfmake.min.js"></script>
    <script src="/src/plugins/datatables/js/vfs_fonts.js"></script>
    <!-- Datatable Setting js -->
    <script src="/vendors/scripts/datatable-setting.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add red asterisk for required labels
            document.querySelectorAll("input[required], select[required], textarea[required]").forEach(input => {
                const label = input.closest(".form-group")?.querySelector("label");
                if (label) label.classList.add("required");
            });

            // Initialize multi-step forms for each modal
            document.querySelectorAll('.edit-student-form').forEach(form => {
                const modal = form.closest('.modal');
                const userId = form.getAttribute('data-user-id');
                // We'll maintain arrays for tabs/steps and refresh them when some steps are skipped
                let tabs = Array.from(modal.querySelectorAll('.edit-form-tab'));
                let steps = Array.from(modal.querySelectorAll('.edit-form-step'));
                const nextBtn = modal.querySelector('.edit-next-btn');
                const prevBtn = modal.querySelector('.edit-prev-btn');
                const submitBtn = modal.querySelector('.edit-submit-btn');
                let currentStep = 0;

                // Contact number restrictions - auto-start with "09"
                const contactInput = modal.querySelector(`#edit-contact-${userId}`);
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
                
                // Father and Mother contact numbers - auto-start with "09"
                const fatherContactInput = modal.querySelector(`#edit-father-contact-${userId}`);
                const motherContactInput = modal.querySelector(`#edit-mother-contact-${userId}`);
                
                [fatherContactInput, motherContactInput].forEach(input => {
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
                const lrnInput = modal.querySelector(`#edit-lrn-${userId}`);
                if (lrnInput) {
                    lrnInput.addEventListener("input", function () {
                        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);
                    });
                }

                // Student name validation - only letters (including Ñ/ñ), spaces, hyphens, and apostrophes
                const studentNameInput = modal.querySelector(`#edit-student-name-${userId}`);
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

                // Profile photo preview (when admin selects a new file)
                const profileInput = modal.querySelector(`#edit-profile-photo-${userId}`);
                const profilePreview = modal.querySelector(`#edit-profile-preview-${userId}`);
                if (profileInput && profilePreview) {
                    profileInput.addEventListener('change', function (e) {
                        const file = this.files && this.files[0];
                        if (file) {
                            profilePreview.src = URL.createObjectURL(file);
                        }
                    });
                }

                // Function to show a specific step
                function showStep(stepIndex) {
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

                // Refresh tabs and steps collections excluding those marked to skip (data-skip="1")
                function refreshCollections() {
                    tabs = Array.from(modal.querySelectorAll('.edit-form-tab')).filter(t => t.dataset.skip !== '1');
                    steps = Array.from(modal.querySelectorAll('.edit-form-step')).filter(s => s.dataset.skip !== '1');
                }

                // Next button click
                if (nextBtn) {
                    nextBtn.addEventListener('click', async function(e) {
                        e.preventDefault();
                        if (!await validateEditStep(steps[currentStep], userId)) return;
                        if (currentStep < steps.length - 1) {
                            showStep(currentStep + 1);
                        }
                    });
                }

                // Previous button click
                if (prevBtn) {
                    prevBtn.addEventListener('click', function() {
                        if (currentStep > 0) {
                            showStep(currentStep - 1);
                        }
                    });
                }

                // Ensure collections are in sync and attach tab click handlers
                refreshCollections();
                function attachTabListeners() {
                    tabs.forEach((tab, index) => {
                        tab.onclick = function() { showStep(index); };
                    });
                }
                attachTabListeners();

                // Initialize - show first step
                showStep(0);

                // Reset to first step when modal is opened
                modal.addEventListener('shown.bs.modal', function() {
                    // Before initializing steps, determine which parent/guardian steps should be shown based on living_mode
                    const livingFatherCheckbox = modal.querySelector("input[name='living_mode[]'][value='Living with Father']");
                    const livingMotherCheckbox = modal.querySelector("input[name='living_mode[]'][value='Living with Mother']");
                    const guardianCheckbox = modal.querySelector("input[name='living_mode[]'][value='Living with Other Guardians']");

                    const motherTab = modal.querySelector('.edit-form-tab[data-step="2"]');
                    const fatherTab = modal.querySelector('.edit-form-tab[data-step="3"]');
                    const guardianTab = modal.querySelector('.edit-form-tab[data-step="4"]');

                    const motherStep = modal.querySelector('#edit-step-2-' + userId);
                    const fatherStep = modal.querySelector('#edit-step-3-' + userId);
                    const guardianStep = modal.querySelector('#edit-step-4-' + userId);

                    // Show/hide mother
                    if (livingMotherCheckbox && livingMotherCheckbox.checked) {
                        if (motherTab) { motherTab.style.display = ''; motherTab.dataset.skip = '0'; }
                        if (motherStep) { motherStep.style.display = ''; motherStep.dataset.skip = '0'; }
                    } else {
                        if (motherTab) { motherTab.style.display = 'none'; motherTab.dataset.skip = '1'; }
                        if (motherStep) { motherStep.style.display = 'none'; motherStep.dataset.skip = '1'; }
                    }

                    // Show/hide father
                    if (livingFatherCheckbox && livingFatherCheckbox.checked) {
                        if (fatherTab) { fatherTab.style.display = ''; fatherTab.dataset.skip = '0'; }
                        if (fatherStep) { fatherStep.style.display = ''; fatherStep.dataset.skip = '0'; }
                    } else {
                        if (fatherTab) { fatherTab.style.display = 'none'; fatherTab.dataset.skip = '1'; }
                        if (fatherStep) { fatherStep.style.display = 'none'; fatherStep.dataset.skip = '1'; }
                    }

                    // Show/hide guardian
                    if (guardianCheckbox && guardianCheckbox.checked) {
                        if (guardianTab) { guardianTab.style.display = ''; guardianTab.dataset.skip = '0'; }
                        if (guardianStep) { guardianStep.style.display = ''; guardianStep.dataset.skip = '0'; }
                    } else {
                        if (guardianTab) { guardianTab.style.display = 'none'; guardianTab.dataset.skip = '1'; }
                        if (guardianStep) { guardianStep.style.display = 'none'; guardianStep.dataset.skip = '1'; }
                    }

                    // Refresh collection arrays and start at first visible step
                    refreshCollections();
                    attachTabListeners();
                    showStep(0);

                    // Re-add required class to labels when modal opens
                    modal.querySelectorAll("input[required], select[required], textarea[required]").forEach(input => {
                        const label = input.closest(".form-group")?.querySelector("label");
                        if (label) label.classList.add("required");
                    });

                    // Format existing contact numbers to start with "09" if they don't
                    const allContactInputs = [
                        modal.querySelector(`#edit-contact-${userId}`),
                        modal.querySelector(`#edit-father-contact-${userId}`),
                        modal.querySelector(`#edit-mother-contact-${userId}`)
                    ];

                    allContactInputs.forEach(input => {
                        if (input && input.value && !input.value.startsWith('09')) {
                            let value = input.value.replace(/[^0-9]/g, '');
                            if (value) {
                                input.value = '09' + value.replace(/^09/, '').slice(0, 9);
                            }
                        }
                    });

                    // Also watch for changes to living_mode checkboxes while modal is open to dynamically show/hide parent/guardian steps
                    const livingCheckboxes = modal.querySelectorAll("input[name='living_mode[]']");
                    livingCheckboxes.forEach(cb => {
                        cb.addEventListener('change', function() {
                            const motherTab = modal.querySelector('.edit-form-tab[data-step="2"]');
                            const fatherTab = modal.querySelector('.edit-form-tab[data-step="3"]');
                            const guardianTab = modal.querySelector('.edit-form-tab[data-step="4"]');

                            const motherStep = modal.querySelector('#edit-step-2-' + userId);
                            const fatherStep = modal.querySelector('#edit-step-3-' + userId);
                            const guardianStep = modal.querySelector('#edit-step-4-' + userId);

                            if (this.value === 'Living with Mother') {
                                if (this.checked) { if (motherTab) { motherTab.style.display = ''; motherTab.dataset.skip = '0'; } if (motherStep) { motherStep.style.display = ''; motherStep.dataset.skip = '0'; } }
                                else { if (motherTab) { motherTab.style.display = 'none'; motherTab.dataset.skip = '1'; } if (motherStep) { motherStep.style.display = 'none'; motherStep.dataset.skip = '1'; } }
                            }

                            if (this.value === 'Living with Father') {
                                if (this.checked) { if (fatherTab) { fatherTab.style.display = ''; fatherTab.dataset.skip = '0'; } if (fatherStep) { fatherStep.style.display = ''; fatherStep.dataset.skip = '0'; } }
                                else { if (fatherTab) { fatherTab.style.display = 'none'; fatherTab.dataset.skip = '1'; } if (fatherStep) { fatherStep.style.display = 'none'; fatherStep.dataset.skip = '1'; } }
                            }

                            if (this.value === 'Living with Other Guardians') {
                                if (this.checked) { if (guardianTab) { guardianTab.style.display = ''; guardianTab.dataset.skip = '0'; } if (guardianStep) { guardianStep.style.display = ''; guardianStep.dataset.skip = '0'; } }
                                else { if (guardianTab) { guardianTab.style.display = 'none'; guardianTab.dataset.skip = '1'; } if (guardianStep) { guardianStep.style.display = 'none'; guardianStep.dataset.skip = '1'; } }
                            }

                            // Collections updated — refresh handlers and ensure currentStep is valid
                            refreshCollections();
                            attachTabListeners();
                            // If the checkbox was just checked, navigate to the newly added tab so admin can update fields immediately
                            if (this.checked) {
                                let targetStep = null;
                                if (this.value === 'Living with Mother') targetStep = '2';
                                if (this.value === 'Living with Father') targetStep = '3';
                                if (this.value === 'Living with Other Guardians') targetStep = '4';
                                if (targetStep) {
                                    // find index of the visible tab with the matching data-step
                                    const visibleTabs = Array.from(modal.querySelectorAll('.edit-form-tab')).filter(t => t.dataset.skip !== '1');
                                    const idx = visibleTabs.findIndex(t => t.getAttribute('data-step') === targetStep);
                                    if (idx !== -1) {
                                        console.log('Showing step for', this.value, 'index', idx);
                                        showStep(idx);
                                        // autofocus first input in the newly shown step
                                        setTimeout(() => {
                                            refreshCollections();
                                            const stepEls = steps; // refreshed by refreshCollections/attachTabListeners
                                            if (stepEls && stepEls[idx]) {
                                                const firstInput = stepEls[idx].querySelector('input, select, textarea');
                                                if (firstInput) {
                                                    try { firstInput.focus(); } catch (e) { /* ignore */ }
                                                }
                                            }
                                        }, 100);
                                    }
                                }
                            } else {
                                if (currentStep >= steps.length) {
                                    showStep(Math.max(0, steps.length - 1));
                                }
                            }
                        });
                    });
                });
            });

            // Validate step function
            async function validateEditStep(stepElement, userId) {
                const inputs = stepElement.querySelectorAll("input[required], select[required], textarea[required]");
                for (let input of inputs) {
                    if (!input.checkValidity()) {
                        input.reportValidity();
                        return false;
                    }
                }

                // Validate LRN
                const lrnInput = stepElement.querySelector(`#edit-lrn-${userId}`);
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
                if (stepElement.id.includes('edit-step-1')) {
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

            // Calculate age function
            window.calculateEditAge = function(userId) {
                const birthday = document.querySelector(`#edit-birthday-${userId}`).value;
                if (birthday) {
                    const birthDate = new Date(birthday);
                    const today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const m = today.getMonth() - birthDate.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
                    document.querySelector(`#edit-age-${userId}`).value = age;
                }
            };

            // Form submission with confirmation
            document.querySelectorAll('.edit-student-form').forEach(form => {
                const handleSubmit = async function (e) {
                    e.preventDefault();

                    const modal = form.closest('.modal');
                    const userId = form.getAttribute('data-user-id');
                    const steps = modal.querySelectorAll('.edit-form-step');

                    // Validate all steps before submission
                    for (let step of steps) {
                        if (!await validateEditStep(step, userId)) {
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
                        text: "Do you want to save these changes?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, save it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Remove event listener to allow submission
                            form.removeEventListener('submit', handleSubmit);
                            if (typeof form.requestSubmit === 'function') {
                                form.requestSubmit();
                            } else {
                                form.submit();
                            }
                        }
                    });
                };
                form.addEventListener('submit', handleSubmit);
            });

            // 🔹 Success alert after saving
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session("success") }}',
                    confirmButtonColor: '#28a745'
                });
            @endif
        });
    </script>

    <script>
        function viewAdditionalInfo(userId, studentName) {
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

            fetch(`/admin/students/${userId}/additional-info`)
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

                            <tr>
                                <td class="section-title" colspan="2">Guardian's Name</td><td colspan="4">${data.guardian_name || '-'}</td>
                                <td colspan="1">Age:</td><td colspan="5">${data.guardian_age || '-'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Occupation/Work:</td><td colspan="4">${data.guardian_occupation || 'N/A'}</td>
                                <td colspan="2">Mobile Number:</td><td colspan="4">${data.guardian_contact || 'N/A'}</td>
                            </tr>
                            <tr>
                                <td colspan="2">FB/Messenger:</td><td colspan="4">${data.guardian_fb || 'N/A'}</td>
                                <td colspan="2">Place of Work:</td><td colspan="4">${data.guardian_place_work || 'N/A'}</td>
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
                       
                    
                            <tr>
                                <td colspan="12" style="text-align: left;">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                                        <span style="white-space: nowrap;">Nilagdaan ngayong araw:</span>
                                        <div style="flex-grow: 1; display: flex; justify-content: space-around; margin-left: 10px;">
                                            <div style="text-align: center; flex-basis: 45%;">
                                                <div style="height: 20px;"></div>
                                                <div style="font-size: 14px; margin-bottom: 1px;">${data.current_date_formatted}</div>
                                                <div style="border-bottom: 1px solid #000; height: 1em;"></div>
                                                <div style="margin-top: 2px;">(Petsa)</div>
                                            </div>
                                            <div style="text-align: center; flex-basis: 45%;">
                                                <div style="height: 5px;"></div>
                                                <div style="margin-bottom: 2px; font-size: 11px;">
                                                    <strong>Agreement Status:</strong>
                                                    <span style="margin-left: 10px;">Student Agreements: ${data.student_agreement_1 && data.student_agreement_2 ? '✓ Accepted' : '✗ Not Accepted'}</span>
                                                </div>
                                                <div style="border-bottom: 1px solid #000; height: 1em;"></div>
                                                <div style="margin-top: 2px;">Lagda ng mag-aaral</div>
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
                                                <div style="height: 20px;"></div>
                                                <div style="font-size: 14px; margin-bottom: 1px;">${data.current_date_formatted}</div>
                                                <div style="border-bottom: 1px solid #000; height: 1em;"></div>
                                                <div style="margin-top: 2px;">(Petsa)</div>
                                            </div>
                                            <div style="text-align: center; flex-basis: 45%;">
                                                <div style="height: 5px;"></div>
                                                <div style="margin-bottom: 2px; font-size: 11px;">
                                                    <strong>Agreement Status:</strong>
                                                    <span style="margin-left: 10px;">Parent Agreements: ${data.parent_agreement_1 && data.parent_agreement_2 ? '✓ Accepted' : '✗ Not Accepted'}</span>
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
