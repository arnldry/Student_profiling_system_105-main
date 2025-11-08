<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Update Profile</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="16x16" href="/vendors/images/logo-ocnhs.png"/>
        

        <!-- Mobile Metas -->s
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>

        <!-- CSS -->
       	<link rel="stylesheet" type="text/css" href="/vendors/styles/core.css" />
		<link rel="stylesheet" type="text/css" href="/vendors/styles/icon-font.min.css"/>
		<link rel="stylesheet" type="text/css" href="/src/plugins/datatables/css/dataTables.bootstrap4.min.css"/>
		<link rel="stylesheet" type="text/css" href="/src/plugins/datatables/css/responsive.bootstrap4.min.css"/>
		<link rel="stylesheet" type="text/css" href="/vendors/styles/style.css" />

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
        /* Password requirements styling */
        .password-requirements li {
            list-style: none;
            position: relative;
            padding-left: 18px;
            margin-bottom: 3px;
            color: #6c757d;
        }
        .password-requirements li::before { content: ""; position: absolute; left: 0; }
        .password-requirements li.valid { color: #28a745; }
        .password-requirements li.valid::before { content: "✓"; color: #28a745; font-weight: bold; }
        .password-requirements li.invalid { color: #dc3545; }
        .password-requirements li.invalid::before { content: "✗"; color: #dc3545; font-weight: bold; }

        /* Red asterisk for required fields only */
        label.required-label::after {
            content: "*";
            color: red;
            margin-left: 2px;
        }

        /* Positioning Show Password button (if using a button icon) */
        .show-pass-btn {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
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
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <h4>Update Profile</h4>
                </div>

                <div class="row">
                    <!-- User Info -->
                    <div class="col-md-5">
                        <div class="card-box p-4 text-center">
                            <img src="{{ $user->avatar ?? asset('images/default-image.png') }}" 
                                alt="User Avatar" class="rounded-circle shadow" width="160" height="200">
                            <h5 class="mt-2 font-weight-bold">{{ $user->name }}</h5>
                            <p class="text-muted mb-1">{{ $user->email }}</p>
                            <span class="badge badge-primary text-capitalize px-3 py-2">{{ $user->role }}</span>
                        </div>
                    </div>

                    <!-- Update Form -->
                    <div class="col-md-7">
                        <div class="card-box p-4">
                            <h5 class="mb-3">Update Account</h5>
                            <form id="updateProfileForm" action="{{ route('admin.update-profile.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- Name -->
                                <div class="form-group">
                                    <label for="name" class="required-label">Name</label>
                                    <input type="text" name="name" id="name" class="form-control form-control-lg" 
                                        value="{{ old('name', $user->name ?? '') }}" required>
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email" class="required-label">Email</label>
                                    <input type="email" name="email" id="email" 
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email ?? '') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Current Password -->
                                <div class="form-group">
                                    <label for="current_password" class="required-label">Current Password</label>
                                    <input type="password" name="current_password" id="current_password" 
                                        class="form-control form-control-lg @error('current_password') is-invalid @enderror"
                                        placeholder="Enter your current password" required>
                                    @error('current_password')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="form-group position-relative">
                                    <label for="password" class="required-label">Password</label>
                                    <input type="password" name="password" id="password" 
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        placeholder="Password">

                                    <small class="form-text text-muted">Leave blank if you do not want to change the password</small>
                                    
                                    <ul id="password-requirements" class="password-requirements mt-2 mb-0 small text-muted" style="padding-left: 0; margin-left: 0;">
                                        <li data-rule="length">At least 8 characters</li>
                                        <li data-rule="upperlower">Contains uppercase & lowercase letters</li>
                                        <li data-rule="number">Contains at least one number</li>
                                        <li data-rule="special">Contains at least one special character</li>
                                    </ul>

                                    @error('password')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>


                                <!-- Confirm Password -->
                                <div class="form-group position-relative">
                                    <label for="password_confirmation" class="required-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                        class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                        placeholder="Confirm Password">
                                
                                    <small id="confirm-password-error" class="text-danger mt-1" style="display:none;">
                                        Passwords do not match.
                                    </small>
                                    @error('password_confirmation')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Show Password Checkbox (no asterisk) -->
                                <div class="form-group form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="showPasswordCheckbox" onclick="togglePasswordVisibility()">
                                    <label class="form-check-label" for="showPasswordCheckbox">Show Password</label>
                                </div>

                                <!-- Submit -->
                                <button type="submit" id="submitBtn" class="btn btn-primary btn-lg btn-block mt-3">
                                    <i class="dw dw-save"></i> Update Account
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const requirements = document.querySelectorAll('#password-requirements li');
        const confirmError = document.getElementById('confirm-password-error');
        const updateBtn = document.getElementById('submitBtn');

        function validatePassword() {
            const value = passwordInput.value;
            let valid = true;

            requirements.forEach(li => {
                switch(li.dataset.rule) {
                    case 'length':
                        li.style.color = value.length >= 8 ? 'green' : '#6c757d';
                        if(value.length < 8 && value.length > 0) li.style.color = 'red';
                        if(value.length < 8) valid = false;
                        break;
                    case 'upperlower':
                        const upperLower = /[a-z]/.test(value) && /[A-Z]/.test(value);
                        li.style.color = upperLower ? 'green' : '#6c757d';
                        if(!upperLower && value.length > 0) li.style.color = 'red';
                        if(!upperLower) valid = false;
                        break;
                    case 'number':
                        const hasNumber = /\d/.test(value);
                        li.style.color = hasNumber ? 'green' : '#6c757d';
                        if(!hasNumber && value.length > 0) li.style.color = 'red';
                        if(!hasNumber) valid = false;
                        break;
                    case 'special':
                        const hasSpecial = /[\W_]/.test(value);
                        li.style.color = hasSpecial ? 'green' : '#6c757d';
                        if(!hasSpecial && value.length > 0) li.style.color = 'red';
                        if(!hasSpecial) valid = false;
                        break;
                }
            });

            // Confirm password check
            if (confirmInput.value !== '' && passwordInput.value !== confirmInput.value) {
                confirmError.style.display = 'block';
                valid = false;
            } else {
                confirmError.style.display = 'none';
            }

            // Enable or disable button
            updateBtn.disabled = !valid && passwordInput.value !== '';
        }

        // Event listeners
        passwordInput.addEventListener('input', validatePassword);
        confirmInput.addEventListener('input', validatePassword);

        // Toggle password visibility
        function togglePasswordVisibility() {
            const type = document.getElementById('showPasswordCheckbox').checked ? 'text' : 'password';
            const currentPasswordInput = document.getElementById('current_password');
            passwordInput.type = type;
            confirmInput.type = type;
            currentPasswordInput.type = type;
        }

        // SweetAlert confirmation before submitting
        document.getElementById('updateProfileForm').addEventListener('submit', function(e) {
            if (passwordInput.value !== confirmInput.value) {
                e.preventDefault();
                confirmError.style.display = 'block';
                confirmInput.focus();
                return;
            }

            e.preventDefault();
            Swal.fire({
                title: 'Confirm Update',
                text: "Are you sure you want to update your profile?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        });
        </script>

        <!-- Success message -->
        @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        </script>
        @endif


        		<script src="/vendors/scripts/core.js"></script>
		<script src="/vendors/scripts/script.min.js"></script>
		<script src="/vendors/scripts/process.js"></script>
		<script src="/vendors/scripts/layout-settings.js"></script>
		<script src="/src/plugins/apexcharts/apexcharts.min.js"></script>
		<script src="/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		<script src="/vendors/scripts/dashboard3.js"></script>
    </body>
</html>
