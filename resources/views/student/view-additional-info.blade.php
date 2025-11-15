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
                            <div class="title">
                                <h1>Guidance & Counseling Unit</h1>
                            </div>
                            <h4>My Additional Information</h4>
                        </div>
                    </div>
                </div>

                <div class="card-box mb-30 p-4">
                    <div class="clearfix mb-30">
                        <h4 class="text-blue h4">Student Information Form</h4>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th colspan="2" class="text-center bg-light">Basic Information</th>
                                    </tr>
                                    <tr>
                                        <td><strong>Learner's Name:</strong></td>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>LRN:</strong></td>
                                        <td>{{ $info->lrn }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Sex:</strong></td>
                                        <td>{{ $info->sex }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Grade & Section:</strong></td>
                                        <td>{{ $info->grade }} / {{ $info->section }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Curriculum:</strong></td>
                                        <td>{{ $info->curriculum }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>School Year:</strong></td>
                                        <td>{{ $info->school_year_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Birthday:</strong></td>
                                        <td>{{ $info->birthday_formatted }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Age:</strong></td>
                                        <td>{{ $info->age }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Religion:</strong></td>
                                        <td>{{ $info->religion }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nationality:</strong></td>
                                        <td>{{ $info->nationality }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Address:</strong></td>
                                        <td>{{ $info->address }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Mobile Number:</strong></td>
                                        <td>{{ $info->contact_number }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>FB/Messenger:</strong></td>
                                        <td>{{ $info->fb_messenger ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Disability:</strong></td>
                                        <td>{{ $info->disability ?: 'None' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Living Mode:</strong></td>
                                        <td>{{ is_array($info->living_mode) ? implode(', ', $info->living_mode) : $info->living_mode }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Submitted Date:</strong></td>
                                        <td>{{ $info->current_date_formatted }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Father's Information</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>{{ $info->father_name ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Age:</strong></td>
                                        <td>{{ $info->father_age ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Occupation:</strong></td>
                                        <td>{{ $info->father_occupation ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Place of Work:</strong></td>
                                        <td>{{ $info->father_place_work ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Mobile Number:</strong></td>
                                        <td>{{ $info->father_contact ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>FB/Messenger:</strong></td>
                                        <td>{{ $info->father_fb ?: 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>Mother's Information</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>{{ $info->mother_name ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Age:</strong></td>
                                        <td>{{ $info->mother_age ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Occupation:</strong></td>
                                        <td>{{ $info->mother_occupation ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Place of Work:</strong></td>
                                        <td>{{ $info->mother_place_work ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Mobile Number:</strong></td>
                                        <td>{{ $info->mother_contact ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>FB/Messenger:</strong></td>
                                        <td>{{ $info->mother_fb ?: 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($info->guardian_name)
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Guardian's Information</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>{{ $info->guardian_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Age:</strong></td>
                                        <td>{{ $info->guardian_age ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Occupation:</strong></td>
                                        <td>{{ $info->guardian_occupation ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Place of Work:</strong></td>
                                        <td>{{ $info->guardian_place_work ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Mobile Number:</strong></td>
                                        <td>{{ $info->guardian_contact ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>FB/Messenger:</strong></td>
                                        <td>{{ $info->guardian_fb ?: 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Agreements</h5>
                            <div class="alert alert-info">
                                <strong>Student Agreements:</strong>
                                <ul class="mb-0">
                                    <li>School Rules Agreement: {{ $info->student_agreement_1 ? 'Accepted' : 'Not Accepted' }}</li>
                                    <li>School Commitment Agreement: {{ $info->student_agreement_2 ? 'Accepted' : 'Not Accepted' }}</li>
                                </ul>
                                <strong>Parent/Guardian Agreements:</strong>
                                <ul class="mb-0">
                                    <li>Parent Duties Agreement: {{ $info->parent_agreement_1 ? 'Accepted' : 'Not Accepted' }}</li>
                                    <li>School Commitment Agreement: {{ $info->parent_agreement_2 ? 'Accepted' : 'Not Accepted' }}</li>
                                </ul>
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
</body>
</html>