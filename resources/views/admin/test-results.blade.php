<!DOCTYPE html>
<html>
<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Test Results</title>

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
                <h2 class="h3 mb-0">Test Result</h2>
            </div>

            <div class="card-box mb-30">
                <div class="pd-20">
                    <p class="mb-0">Test results here</p>
                </div>

                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            
                                <tr>
                                    <th>LRN</th>
                                    <th>Student Name</th>
                                    <th>RIASEC Test</th>
                                    <th>Life Values Test</th>
                                    <th>Test Results</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($studentsWithTests as $student)
                                <tr>
                                <td>
                                        @php
                                            $info = \App\Models\AdditionalInformation::where('learner_id', $student)->first();
                                        @endphp
                                        {{ $info ? $info->lrn : '-' }}
                                    </td>
                                    <td>{{ $student['name'] }}</td>
                                    <td>
                                        @if($student['has_riasec'])
                                            <span class="text-success">Completed</span>
                                        @else
                                            <span class="text-muted">Not Taken</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($student['has_life_values'])
                                            <span class="text-success">Completed</span>
                                        @else
                                            <span class="text-muted">Not Taken</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($student['has_riasec'])
                                            <a href="{{ route('admin.student-riasec', $student['id']) }}" class="btn btn-sm btn-info">View RIASEC</a>
                                        @endif
                                        @if($student['has_life_values'])
                                            <a href="{{ route('admin.student-life-values', $student['id']) }}" class="btn btn-sm btn-success">View Life Values</a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No students have taken any tests yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
</body>
</html>