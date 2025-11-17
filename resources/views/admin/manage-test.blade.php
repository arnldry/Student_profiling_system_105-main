<!DOCTYPE html>
<html>
<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Manage Test</title>

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
#result_3_block table {
  width: 100%;
  border-collapse: collapse;
}

#result_3_block td {
  padding: 10px 8px;
  font-size: 15px;
  border-bottom: 1px solid #eee;
}

.riasecResult_chars {
  font-weight: bold;
  font-size: 18px;
  color: #1cc2f2;
  width: 30px;
  text-align: center;
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
            <div class="min-height-200px">
                <div class="title pb-20">
                    <h2 class="h3 mb-0">Test Management</h2>
                </div>

                <div class="card-box mb-30">
                <div class="pd-20">
                        <p class="mb-0" style="font-weight: bold;">Test Availability Settings</p>
                        <p class="mb-0">Control which tests are available to students</p>
                </div>

                    <div class="row" style="margin-bottom: 20px;">
                        <!-- RIASEC Test Toggle -->
                        <div class="col-md-6">
                            <div class="card border-primary" style="background: #f8f9fa; border-radius: 20px; padding: 10px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); border: 2px solid #e9ecef;">
                                <div class="card-body text-center" style="padding: 15px;">
                                    <h5 class="card-title text-primary">RIASEC Career Test</h5>



                                    <button type="button" class="btn btn-outline-{{ $riasecEnabled ? 'primary' : 'secondary' }} mt-3 toggle-btn"
                                            data-test-type="riasec"
                                            data-current-status="{{ $riasecEnabled }}">
                                        <i class="fa fa-toggle-{{ $riasecEnabled ? 'on' : 'off' }}"></i>
                                        {{ $riasecEnabled ? 'Disable' : 'Enable' }}
                                    </button>
                                </div>
                            </div>
                        
                        </div>

                        <!-- Life Values Test Toggle -->
                        <div class="col-md-6">
                            <div class="card border-success" style="background: #f8f9fa; border-radius: 20px; padding: 10px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); border: 2px solid #e9ecef;">
                                <div class="card-body text-center" style="padding: 15px;">
                                    <h5 class="card-title text-success">Life Values Inventory</h5>



                                    <button type="button" class="btn btn-outline-{{ $lifeValuesEnabled ? 'success' : 'secondary' }} mt-3 toggle-btn"
                                            data-test-type="life_values"
                                            data-current-status="{{ $lifeValuesEnabled }}">
                                        <i class="fa fa-toggle-{{ $lifeValuesEnabled ? 'on' : 'off' }}"></i>
                                        {{ $lifeValuesEnabled ? 'Disable' : 'Enable' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6><i class="fa fa-info-circle"></i> Information</h6>
                                <ul class="mb-0">
                                    <li>Students can normally retake the test after 1 year</li>
                                    <li>Clicking "Allow Retake" immediately enables the student to retake the test</li>

                                </ul>
                            </div>
                        </div>
                    </div>

                <div class="test-tables-wrapper">
                <!-- RIASEC Student List -->
        <div class="card-box mb-30">
                <div class="pd-20">
                    <p class="mb-0" style="font-weight: bold;">RIASEC test retake permissions for students</p>
                </div>

                    <div id="result_3_block" class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th>LRN</th>
                                    <th>Student Name</th>
                                    <th>Grade & Section</th>
                                    <th>Curriculum</th>
                                    <th>Last Taken</th>
                                    <th>Status</th>
                                    <th class="datatable-nosort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($studentsWithRiasec as $student)
                                <tr>
                                    <td>{{ $student['lrn'] ?? 'N/A' }}</td>
                                    <td>{{ $student['name'] }}</td>
                                    <td>{{ $student['grade'] ?? 'N/A' }} / {{ $student['section'] ?? 'N/A' }}</td>
                                    <td>{{ $student['curriculum'] ?? 'N/A' }}</td>
                                    <td>{{ $student['last_taken'] ? \Carbon\Carbon::parse($student['last_taken'])->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        @if($student['admin_reopened'])
                                            <span class="text-success">Retake Allowed</span>
                                        @elseif($student['can_retake'])
                                            <span class="text-warning">Eligible for Retake</span>
                                        @else
                                            <span class="text-muted">Not Eligible</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$student['admin_reopened'])
                                            <button type="button" class="btn btn-sm btn-primary retake-btn"
                                                    data-student-id="{{ $student['id'] }}"
                                                    data-student-name="{{ $student['name'] }}">
                                                <i class="fa fa-refresh"></i> Allow Retake
                                            </button>
                                        @else
                                            <span class="text-success"><i class="fa fa-check"></i> Retake Enabled</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No students have taken the RIASEC test yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- Life Values Student List -->
        <div class="card-box mb-30">
                <div class="pd-20">
                    <p class="mb-0" style="font-weight: bold;">Life Values test retake permissions for students</p>
                </div>

                    <div id="result_4_block" class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th>LRN</th>
                                    <th>Student Name</th>
                                    <th>Grade & Section</th>
                                    <th>Curriculum</th>
                                    <th>Last Taken</th>
                                    <th>Status</th>
                                    <th class="datatable-nosort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($studentsWithLifeValues as $student)
                                <tr>
                                    <td>{{ $student['lrn'] ?? 'N/A' }}</td>
                                    <td>{{ $student['name'] }}</td>
                                    <td>{{ $student['grade'] ?? 'N/A' }} / {{ $student['section'] ?? 'N/A' }}</td>
                                    <td>{{ $student['curriculum'] ?? 'N/A' }}</td>
                                    <td>{{ $student['last_taken'] ? \Carbon\Carbon::parse($student['last_taken'])->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        @if($student['admin_reopened'])
                                            <span class="text-success">Retake Allowed</span>
                                        @elseif($student['can_retake'])
                                            <span class="text-warning">Eligible for Retake</span>
                                        @else
                                            <span class="text-muted">Not Eligible</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$student['admin_reopened'])
                                            <button type="button" class="btn btn-sm btn-success life-values-retake-btn"
                                                    data-student-id="{{ $student['id'] }}"
                                                    data-student-name="{{ $student['name'] }}">
                                                <i class="fa fa-refresh"></i> Allow Retake
                                            </button>
                                        @else
                                            <span class="text-success"><i class="fa fa-check"></i> Retake Enabled</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No students have taken the Life Values test yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
                </div> <!-- End test-tables-wrapper -->
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        document.addEventListener('DOMContentLoaded', function() {
            // Handle toggle buttons
            document.querySelectorAll('.toggle-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const testType = this.getAttribute('data-test-type');
                    const currentStatus = this.getAttribute('data-current-status') === '1';
                    const newStatus = !currentStatus;

                    // Show confirmation dialog
                    Swal.fire({
                        title: 'Confirm Action',
                        text: `Are you sure you want to ${newStatus ? 'enable' : 'disable'} the ${testType.replace('_', ' ')} test?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: newStatus ? '#28a745' : '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: `Yes, ${newStatus ? 'enable' : 'disable'} it!`
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send AJAX request to toggle test status
                            fetch('{{ route("admin.manage-test.toggle") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                credentials: 'same-origin',
                                body: JSON.stringify({
                                    test_type: testType,
                                    enabled: newStatus
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update UI
                                    this.setAttribute('data-current-status', newStatus ? '1' : '0');
                                    this.innerHTML = `<i class="fa fa-toggle-${newStatus ? 'on' : 'off'}"></i> ${newStatus ? 'Disable' : 'Enable'}`;
                                    const baseClass = testType === 'riasec' ? 'primary' : 'success';
                                    this.className = `btn btn-outline-${newStatus ? baseClass : 'secondary'} mt-3 toggle-btn`;

                                    // Update checkbox
                                    const checkbox = document.getElementById(testType + 'Toggle');
                                    if (checkbox) {
                                        checkbox.checked = newStatus;
                                        const label = checkbox.nextElementSibling;
                                        label.textContent = newStatus ? 'Enabled' : 'Disabled';
                                    }

                                    // Show success message
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    throw new Error(data.message || 'Failed to update test status');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to update test status. Please try again.'
                                });
                            });
                        }
                    });
                });
            });

            // Handle retake buttons
            document.querySelectorAll('.retake-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const studentId = this.getAttribute('data-student-id');
                    const studentName = this.getAttribute('data-student-name');

                    // Show confirmation dialog
                    Swal.fire({
                        title: 'Allow Retake',
                        text: `Are you sure you want to allow ${studentName} to retake the RIASEC test?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#007bff',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, allow retake'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send AJAX request to allow retake
                            fetch(`{{ url('/admin/reopen-riasec') }}/${studentId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                credentials: 'same-origin'
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update the UI immediately
                                    const button = document.querySelector(`button[data-student-id="${studentId}"].retake-btn`);
                                    if (button) {
                                        // Hide the button
                                        button.style.display = 'none';

                                        // Update the status badge
                                        const statusCell = button.closest('tr').querySelector('td:nth-child(6)'); // Status column
                                        if (statusCell) {
                                            statusCell.innerHTML = '<span class="badge badge-success">Retake Allowed</span>';
                                        }

                                        // Update the action cell
                                        const actionCell = button.closest('td');
                                        if (actionCell) {
                                            actionCell.innerHTML = '<span class="text-success"><i class="fa fa-check"></i> Retake Enabled</span>';
                                        }
                                    }

                                    // Show success message
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: data.message || 'RIASEC test has been reopened for the student.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    throw new Error(data.message || 'Failed to allow retake');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to allow retake. Please try again.'
                                });
                            });
                        }
                    });
                });
            });

            // Handle Life Values retake buttons
            document.querySelectorAll('.life-values-retake-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const studentId = this.getAttribute('data-student-id');
                    const studentName = this.getAttribute('data-student-name');

                    // Show confirmation dialog
                    Swal.fire({
                        title: 'Allow Retake',
                        text: `Are you sure you want to allow ${studentName} to retake the Life Values test?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, allow retake'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send AJAX request to allow retake
                            fetch(`{{ url('/admin/reopen-life-values') }}/${studentId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                credentials: 'same-origin'
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update the UI immediately
                                    const button = document.querySelector(`button[data-student-id="${studentId}"].life-values-retake-btn`);
                                    if (button) {
                                        // Hide the button
                                        button.style.display = 'none';

                                        // Update the status badge
                                        const statusCell = button.closest('tr').querySelector('td:nth-child(6)'); // Status column
                                        if (statusCell) {
                                            statusCell.innerHTML = '<span class="badge badge-success">Retake Allowed</span>';
                                        }

                                        // Update the action cell
                                        const actionCell = button.closest('td');
                                        if (actionCell) {
                                            actionCell.innerHTML = '<span class="text-success"><i class="fa fa-check"></i> Retake Enabled</span>';
                                        }
                                    }

                                    // Show success message
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: data.message || 'Life Values test has been reopened for the student.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    throw new Error(data.message || 'Failed to allow retake');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to allow retake. Please try again.'
                                });
                            });
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>