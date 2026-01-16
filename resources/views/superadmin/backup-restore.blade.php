<!DOCTYPE html>
<html>
    <head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Back up & Restore</title>

		<!-- Site favicon -->
        <link rel="icon" type="image/png" sizes="16x16" href="/vendors/images/logo-ocnhs.png"/>

        <!-- Mobile Specific Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>

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
	<!-- header -->
    <div class="header">
        @include('layouts.navbar.superadmin.navbar')
    </div>
    <div class="left-side-bar">
        @include('layouts.sidebar.superadmin.sidebar')
    </div>

    
    <div class="main-container">
            <div class="xs-pd-20-10 pd-ltr-20">
                <!-- Page Title -->
                <div class="title pb-20">
                    <h2 class="h3 mb-0 text-dark">Database Backup & Restore</h2>
                </div>

                <div class="row">
                    <!-- Download Backup Card -->
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-30">
                        <div class="card-box height-100-p pd-20 shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-primary">
                                    <i class=""></i>Download Backup
                                </h5>                    
                            </div>
                            <p class="text-muted">Download encrypted backup of your database tables for secure storage.</p>
                            <button id="downloadBackup" class="btn btn-primary btn-block">
                                <i class="bi bi-download me-1"></i> Download Encrypted Backup                                    
                            </button>
                        </div>
                    </div>

                    <!-- Upload Backup Card -->
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-30">
                        <div class="card-box height-100-p pd-20 shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-success">
                                    <i class=""></i>Upload Backup
                                </h5>
                            </div>
                            <p class="text-muted">Upload an encrypted backup file (.enc) or SQL file to restore your database.</p>
                            <form action="{{ route('superadmin.upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-3">
                                    <input type="file" name="sql_file" class="form-control" accept=".sql,.enc,.txt" required>
                                </div>
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="bi bi-upload me-1"></i> Upload SQL File
                                </button>
                            </form>

                            @if($errors->any())
                                <div class="alert alert-danger mt-3">
                                    @foreach ($errors->all() as $error)
                                        <p class="mb-0">{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- welcome modal end -->
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


<script>
$(document).ready(function() {
    // SweetAlert2 confirmation for "Download Backup" button
    $('#downloadBackup').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "This will download an encrypted backup file. Proceed with download?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, download it!',
            cancelButtonText: 'No, cancel!',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('superadmin.download') }}";
            }
        });
    });

    // SweetAlert2 confirmation for "Upload SQL File" button
    $('form').on('submit', function(e) {
        e.preventDefault();
        const form = this; // ✅ Keep reference to the form

        Swal.fire({
            title: 'Are you sure?',
            text: "This operation involves confidential information. Are you sure you want to continue?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, upload it!',
            cancelButtonText: 'No, cancel!',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // ✅ Submit the form after confirmation
            }
        });
    });

    // Display success alert if the upload is successful
    @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif
});
</script>


        <script>
            $(document).ready(function() {
                // Toggle sidebar menu
                $('.nav-link').click(function() {
                    var parent = $(this).parent();
                    if ($(parent).hasClass('menu-open')) {
                        $(parent).removeClass('menu-open');
                    } else {
                        $(parent).addClass('menu-open');
                    }
                });
            });
        </script>
	</body>
</html>
