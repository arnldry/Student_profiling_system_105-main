<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Archived Student Data</title>

        <!-- Site favicon -->
        <link rel="icon" type="image/png" sizes="16x16" href="/vendors/images/logo-ocnhs.png"/>

        <!-- Mobile Specific Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="/vendors/styles/core.css" />
        <link rel="stylesheet" type="text/css" href="/vendors/styles/icon-font.min.css" />
        <link rel="stylesheet" type="text/css" href="/src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
        <link rel="stylesheet" type="text/css" href="/src/plugins/datatables/css/responsive.bootstrap4.min.css" />
        <link rel="stylesheet" type="text/css" href="/vendors/styles/style.css" />

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            .data-table td {
                text-align: center;
            }
        </style>
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
                <h2 class="h3 mb-0 text-blue">Archived Student Data</h2>
            </div>

            <div class="card-box mb-30">
                <div class="pd-20">
                    <!-- Optional header content -->
                </div>

                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>School Year</th>
                                <th>Status</th>
                                <th>Archived Date</th> <!-- New column -->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeSchoolYears as $sy)
                                @php
                                    $archived = \App\Models\ArchivedStudentInformation::where('school_year_id', $sy->id)->first();
                                @endphp
                                <tr>
                                    <td class="font-weight-bold">{{ $sy->school_year }}</td>
                                    <td>
                                        <span class="badge {{ $archived ? 'btn btn-sm text-secondary' : 'btn btn-sm text-success' }}">
                                            {{ $archived ? 'Archived' : 'Active' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($archived)
                                            {{ \Carbon\Carbon::parse($archived->created_at)->format('F d, Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$archived)
                                            <button 
                                                type="button" 
                                                class="btn btn-warning btn-sm archive-btn"
                                                data-id="{{ $sy->id }}"
                                                data-year="{{ $sy->school_year }}">
                                                <i class="dw dw-archive"></i> Archive
                                            </button>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="dw dw-check"></i> Already Archived
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        No active school years with student data found.
                                    </td>
                                </tr>
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
            document.addEventListener('DOMContentLoaded', function () {
                // SweetAlert confirmation for archiving
                document.querySelectorAll('.archive-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const schoolYearId = this.getAttribute('data-id');
                        const schoolYear = this.getAttribute('data-year');

                        Swal.fire({
                            title: 'Archive Confirmation',
                            text: `Are you sure you want to archive all students from ${schoolYear}?`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, archive it!',
                            cancelButtonText: 'Cancel',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Create and submit form dynamically
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = `/superadmin/archived-student-data/${schoolYearId}/archive`;

                                const csrf = document.createElement('input');
                                csrf.type = 'hidden';
                                csrf.name = '_token';
                                csrf.value = '{{ csrf_token() }}';

                                form.appendChild(csrf);
                                document.body.appendChild(form);
                                form.submit();
                            }
                        });
                    });
                });

                // Success message after redirect
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: '{{ session('success') }}',
                        timer: 2000,
                        showConfirmButton: false
                    });
                @endif
            });
        </script>
    </body>
</html>
