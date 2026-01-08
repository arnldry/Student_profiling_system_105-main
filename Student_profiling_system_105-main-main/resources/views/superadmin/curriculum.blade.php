<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Manage Curriculum</title>

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
    <div class="header">
        @include('layouts.navbar.superadmin.navbar')
    </div>
    <div class="left-side-bar">
        @include('layouts.sidebar.superadmin.sidebar')
    </div>

    <div class="main-container">
        <div class="xs-pd-20-10 pd-ltr-20">
            <div class="title pb-20">
                <h2 class="h3 mb-0">Manage Curriculum</h2>
            </div>

            {{-- Add Curriculum --}}
            <div class="card-box mb-30">
                <div class="pd-20 d-flex justify-content-end align-items-center">
                    <button class="btn btn-success btn-sm" type="button" data-toggle="collapse" data-target="#addCurriculumForm" aria-expanded="false" aria-controls="addCurriculumForm">
                        <i class="dw dw-add"></i> Add Curriculum
                    </button>
                </div>

                <div class="collapse @error('name') show @enderror" id="addCurriculumForm">
                    <div class="pd-20">
                        <form action="{{ route('superadmin.curriculum.store') }}" method="POST" id="addCurriculumFormElement">
                            @csrf
                            <div class="form-group">
                                <label for="name">Curriculum Name</label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter Curriculum Name" required
                                    value="{{ old('name') }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Curriculums Table --}}
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Active Curriculums</h4>
                </div>
                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($curriculums as $curriculum)
                                <tr>
                                    <td>{{ $curriculum->name }}</td>
                                    <td>
                                        @if($curriculum->is_archived)
                                            <span class="btn btn-sm btn-danger">Archived</span>
                                        @else
                                            <span class="btn btn-sm text-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Edit Button --}}
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editCurriculumModal{{ $curriculum->id }}">
                                            <i class="dw dw-edit2"></i> Edit
                                        </button>

                                        {{-- Archive / Unarchive --}}
                                        @if(!$curriculum->is_archived)
                                            <form action="{{ route('superadmin.curriculum.archive', $curriculum->id) }}" method="POST" class="d-inline archive-form">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="dw dw-folder"></i> Archive
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('superadmin.curriculum.unarchive', $curriculum->id) }}" method="POST" class="d-inline unarchive-form">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="dw dw-rotate-left"></i> Unarchive
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Edit Curriculum Modal --}}
                                <div class="modal fade" id="editCurriculumModal{{ $curriculum->id }}" tabindex="-1" aria-labelledby="editCurriculumModalLabel{{ $curriculum->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-white border-bottom">
                                                <h5 class="modal-title" id="editCurriculumModalLabel{{ $curriculum->id }}">
                                                    <i class="dw dw-edit2 mr-2 text-primary"></i> Edit Curriculum
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('superadmin.curriculum.update', $curriculum->id) }}" method="POST" id="editCurriculumForm{{ $curriculum->id }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="name{{ $curriculum->id }}">Curriculum Name</label>
                                                        <input type="text" id="name{{ $curriculum->id }}" name="name"
                                                               class="form-control @error('name') is-invalid @enderror"
                                                               value="{{ old('name', $curriculum->name) }}" required>
                                                        @error('name')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="dw dw-diskette"></i> Save Changes
                                                    </button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr><td colspan="3" class="text-center">No curriculums found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Archived Curriculums Table --}}    
                 <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-blue h4">Archived Curriculums</h4>
                    </div>
                    <div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th>Curriculum Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($archivedCurriculums as $curriculum)
                                    <tr>
                                        <td>{{ $curriculum->name }}</td>
                                        <td><span class="btn btn-sm text-danger">Inactive</span></td>
                                        <td>
                                            <form action="{{ route('superadmin.curriculum.unarchive', $curriculum->id) }}" 
                                                method="POST" class="d-inline unarchive-form">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="dw dw-rotate-left"></i> Unarchive
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center">No archived curriculums found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>

    


    {{-- JS --}}
    <script src="/vendors/scripts/core.js"></script>
    <script src="/vendors/scripts/script.min.js"></script>
    <script src="/vendors/scripts/process.js"></script>
    <script src="/vendors/scripts/layout-settings.js"></script>
    <script src="/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <script src="/src/plugins/datatables/js/dataTables.buttons.min.js"></script>
    <script src="/src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
    <script src="/src/plugins/datatables/js/buttons.print.min.js"></script>
    <script src="/src/plugins/datatables/js/buttons.html5.min.js"></script>
    <script src="/src/plugins/datatables/js/pdfmake.min.js"></script>
    <script src="/src/plugins/datatables/js/vfs_fonts.js"></script>
    <script src="/vendors/scripts/datatable-setting.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // SweetAlert confirmation for Add Curriculum
            document.getElementById('addCurriculumFormElement').addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Add Curriculum?',
                    text: "Are you sure you want to add this curriculum?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, add it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });

            // SweetAlert confirmation for Edit Curriculum
            document.querySelectorAll('[id^="editCurriculumForm"]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Update Curriculum?',
                        text: "Are you sure you want to update this curriculum?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, update it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            // SweetAlert confirmation for Archive
            document.querySelectorAll('.archive-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This curriculum will be archived!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, archive it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // SweetAlert confirmation for Unarchive
            document.querySelectorAll('.unarchive-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Restore Curriculum?',
                        text: "This curriculum will be active again!",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, unarchive it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Success message
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            // Error message
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    showConfirmButton: true
                });
            @endif
        </script>
    </body>
</html>
