<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Manage School Years</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="16x16" href="/vendors/images/logo-ocnhs.png"/>

        <!-- Mobile Specific Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>

        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="/vendors/styles/core.css" />
        <link rel="stylesheet" type="text/css" href="/vendors/styles/icon-font.min.css" />
        <link rel="stylesheet" type="text/css" href="/src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
        <link rel="stylesheet" type="text/css" href="/src/plugins/datatables/css/responsive.bootstrap4.min.css" />
        <link rel="stylesheet" type="text/css" href="/vendors/styles/style.css" />

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    
<body>
    <div class="header">
        @include('layouts.navbar.superadmin.navbar')
    </div>
    <div class="left-side-bar">
        @include('layouts.sidebar.superadmin.sidebar')
    </div>

        <div class="main-container">
            <div class="xs-pd-20-10 pd-ltr-20">
                <div class="title pb-20">
                    <h2 class="h3 mb-0">Manage School Years</h2>
                </div>

                <!-- Add School Year Container -->
                <div class="card-box mb-30">
                    <div class="pd-20 d-flex justify-content-end align-items-center">
                        <button id="toggleAddSY" class="btn btn-success btn-sm" type="button" data-toggle="collapse" data-target="#addSYForm" aria-expanded="false" aria-controls="addSYForm">
                            <i class="dw dw-add"></i> Add School Year
                        </button>
                    </div>

                    <!-- Add School Year Form -->
                    <div class="collapse @error('school_year') show @enderror" id="addSYForm">
                        <div class="pd-20">
                            <form id="addSchoolYearForm" action="{{ route('superadmin.school-year.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="school_year">School Year</label>
                                    <select id="school_year" name="school_year"
                                        class="form-control @error('school_year') is-invalid @enderror" required>
                                        <option value="">Select School Year</option>
                                        @php
                                            $currentYear = date('Y');
                                            for ($i = 0; $i < 5; $i++) {
                                                $startYear = $currentYear + $i;
                                                $endYear = $startYear + 1;
                                                $schoolYear = $startYear . '-' . $endYear;
                                                $selected = old('school_year') == $schoolYear ? 'selected' : '';
                                                echo "<option value=\"$schoolYear\" $selected>$schoolYear</option>";
                                            }
                                        @endphp
                                    </select>
                                    <small class="text-muted">Note: School year options update annually to show the next 5 years.</small>
                                    @error('school_year')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Active School Years -->
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-blue h4">Active School Years</h4>
                    </div>
                    <div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th>School Year</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeSchoolYears as $sy)
                                    <tr>
                                        <td>{{ $sy->school_year }}</td>
                                        <td><strong class="text-success">Active</strong></td>
                                        <td>
                                            <form action="{{ route('superadmin.school-year.archive', $sy->id) }}" method="POST" class="d-inline archive-form">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="dw dw-folder"></i> Archive
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center">No active school years found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Archived School Years -->
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-blue h4">Archived School Years</h4>
                    </div>
                    <div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th>School Year</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($archivedSchoolYears as $sy)
                                    <tr>
                                        <td>{{ $sy->school_year }}</td>
                                        <td><strong class="text-danger">Inactive</strong></td>

                                        <td>
                                            <form action="{{ route('superadmin.school-year.unarchive', $sy->id) }}" method="POST" class="d-inline unarchive-form">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="dw dw-folder"></i> Unarchive
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center">No archived school years found.</td></tr>
                                @endforelse
                            </tbody>
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
        <script src="/vendors/scripts/datatable-setting.js"></script>

        <script>
            // Confirmation for Add School Year
            document.getElementById('addSchoolYearForm').addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to add this new school year?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, add it!'
                }).then((result) => {
                    if (result.isConfirmed) this.submit();
                });
            });

            // Confirmation for Archive
            document.querySelectorAll('.archive-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This school year will be archived!",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, archive it!'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // Confirmation for Unarchive
            document.querySelectorAll('.unarchive-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Restore School Year?',
                        text: "This school year will be active again!",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, unarchive it!'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // Success Message
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            // Error Message
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    showConfirmButton: true,
                });
            @endif
        </script>
    </body>
</html>
