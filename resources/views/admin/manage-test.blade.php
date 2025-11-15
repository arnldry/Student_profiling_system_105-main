<!DOCTYPE html>
<html>
<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Manage Test</title>

    <!-- Site favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="/vendors/images/logo-ocnhs.png"/>

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/icon-font.min.css"/>
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
            <div class="min-height-200px">
                <div class="page-header text-center">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="title">
                                <h1>Test Management</h1>
                            </div>
                            <h4>Enable or Disable Tests for Students</h4>
                        </div>
                    </div>
                </div>

                <div class="card-box mb-30 p-4">
                    <div class="clearfix mb-30">
                        <h4 class="text-blue h4">Test Availability Settings</h4>
                        <p class="mb-0">Control which tests are available to students</p>
                    </div>

                    <div class="row">
                        <!-- RIASEC Test Toggle -->
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-primary">RIASEC Career Test</h5>
                                    <p class="card-text">RIASEC (Realistic, Investigative, Artistic, Social, Enterprising, Conventional) personality assessment</p>

                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="riasecToggle"
                                               {{ $riasecEnabled ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="riasecToggle">
                                            {{ $riasecEnabled ? 'Enabled' : 'Disabled' }}
                                        </label>
                                    </div>

                                    <button type="button" class="btn btn-primary mt-3 toggle-btn"
                                            data-test-type="riasec"
                                            data-current-status="{{ $riasecEnabled }}">
                                        <i class="fa fa-toggle-{{ $riasecEnabled ? 'on' : 'off' }}"></i>
                                        {{ $riasecEnabled ? 'Disable' : 'Enable' }} RIASEC Test
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Life Values Test Toggle -->
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-success">Life Values Inventory</h5>
                                    <p class="card-text">Assessment of personal values and life priorities</p>

                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="lifeValuesToggle"
                                               {{ $lifeValuesEnabled ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="lifeValuesToggle">
                                            {{ $lifeValuesEnabled ? 'Enabled' : 'Disabled' }}
                                        </label>
                                    </div>

                                    <button type="button" class="btn btn-success mt-3 toggle-btn"
                                            data-test-type="life_values"
                                            data-current-status="{{ $lifeValuesEnabled }}">
                                        <i class="fa fa-toggle-{{ $lifeValuesEnabled ? 'on' : 'off' }}"></i>
                                        {{ $lifeValuesEnabled ? 'Disable' : 'Enable' }} Life Values Test
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6><i class="fa fa-info-circle"></i> Information</h6>
                                <ul class="mb-0">
                                    <li>When a test is disabled, students will not be able to access or take that test</li>
                                    <li>Existing test results will remain in the system</li>
                                    <li>Changes take effect immediately</li>
                                    <li>All test toggles are logged in the Activity Log</li>
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
                                    this.innerHTML = `<i class="fa fa-toggle-${newStatus ? 'on' : 'off'}"></i> ${newStatus ? 'Disable' : 'Enable'} ${testType.replace('_', ' ')} Test`;
                                    this.className = `btn btn-${newStatus ? 'primary' : 'secondary'} mt-3 toggle-btn`;

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
        });
    </script>
</body>
</html>