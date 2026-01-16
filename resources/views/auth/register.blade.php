<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Register</title>

    <!-- Site favicon -->
    <link href="landing-pages/assets/img/logo-ocnhs.png" rel="icon">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/style.css" />

    <style>
        /* Form padding fix */
        .form-control.pr-5 { padding-right: 2.2rem !important; }

        /* Password requirements */
        .password-requirements {
            margin-top: 0;
            margin-bottom: 1rem;
            padding-left: 0;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .password-requirements li {
            list-style: none; /* remove bullets */
            margin-bottom: 4px;
        }

        /* Disabled submit button style */
        button:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }

        /* Required field asterisk */
        label[for] {
            position: relative;
        }
        label[for]:has(+ .input-group > input[required])::after {
            content: " *";
            color: #dc3545;
            margin-left: 2px;
        }

        /* Error message styling */
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #dc3545;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        /* Increase login box width */
        .login-box {
            max-width: 900px;
        }
    </style>
</head>
<body class="login-page">
<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="login-box bg-white box-shadow border-radius-10">
                    <div class="text-center mb-3">
                        <img src="landing-pages/assets/img/logo-ocnhs.png" alt="OCNHS Logo" style="max-width: 150px; height: auto;">
                    </div>
                    <div class="login-title mb-30">
                        <h2 class="text-center text-primary">
                            @if(session('success'))
                                Registration Successful
                            @else
                                Create Student Account
                            @endif
                        </h2>
                    </div>

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="dw dw-check"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="text-center mb-3">
                            <a href="{{ route('login') }}" class="font-16 weight-600 text-primary">
                                Back to Login
                            </a>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="dw dw-warning"></i>
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="dw dw-info"></i>
                            {{ session('info') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- VALIDATION ERRORS SECTION - ADD THIS -->
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <!-- <i class="dw dw-warning"></i>
                            <strong>Please fix the following errors:</strong> -->
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(!session('success'))
                    <form id="registerForm" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Name -->
                                <label for="first_name">First Name</label>
                                <div class="input-group custom mb-3">
                                    <input type="text" id="first_name" name="first_name" class="form-control form-control-lg @error('first_name') is-invalid @enderror" placeholder="First Name" value="{{ old('first_name') }}" pattern="[A-Za-zÑñ\s\-\']+" title="Only letters (including Ñ/ñ), spaces, hyphens, and apostrophes are allowed" required autofocus />
                                    <div class="input-group-append custom">
                                        <span class="input-group-text"><i class="dw dw-user1"></i></span>
                                    </div>
                                    @error('first_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Last Name -->
                                <label for="last_name">Last Name</label>
                                <div class="input-group custom mb-3">
                                    <input type="text" id="last_name" name="last_name" class="form-control form-control-lg @error('last_name') is-invalid @enderror" placeholder="Last Name" value="{{ old('last_name') }}" pattern="[A-Za-zÑñ\s\-\']+" title="Only letters (including Ñ/ñ), spaces, hyphens, and apostrophes are allowed" required />
                                    <div class="input-group-append custom">
                                        <span class="input-group-text"><i class="dw dw-user1"></i></span>
                                    </div>
                                    @error('last_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Middle Name -->
                                <label for="middle_name">Middle Name (Optional)</label>
                                <div class="input-group custom mb-3">
                                    <input type="text" id="middle_name" name="middle_name" class="form-control form-control-lg @error('middle_name') is-invalid @enderror" placeholder="Middle Name (Optional)" value="{{ old('middle_name') }}" pattern="[A-Za-zÑñ\s\-\']*" title="Only letters (including Ñ/ñ), spaces, hyphens, and apostrophes are allowed" />
                                    <div class="input-group-append custom">
                                        <span class="input-group-text"><i class="dw dw-user1"></i></span>
                                    </div>
                                    @error('middle_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Suffix -->
                                <label for="suffix">Suffix (Optional)</label>
                                <div class="input-group custom mb-3">
                                    <input type="text" id="suffix" name="suffix" class="form-control form-control-lg @error('suffix') is-invalid @enderror" placeholder="Suffix (Optional)" value="{{ old('suffix') }}" pattern="[A-Za-zÑñ\s\-\']*" title="Only letters (including Ñ/ñ), spaces, hyphens, and apostrophes are allowed" />
                                    <div class="input-group-append custom">
                                        <span class="input-group-text"><i class="dw dw-user1"></i></span>
                                    </div>
                                    @error('suffix')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="row">
                            <div class="col-12">
                                <label for="email">Email Address</label>
                                <div class="input-group custom mb-3">
                                    <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Email Address" value="{{ old('email') }}" required />
                                    <div class="input-group-append custom">
                                        <span class="input-group-text"><i class="dw dw-email"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Information about email verification -->
                        <div class="alert alert-info mb-3">
                            <i class="dw dw-email"></i>
                            <strong>Email Verification Required</strong><br>
                            After registration, you will receive an email with a verification link to create your password.
                        </div>

                        <!-- Submit -->
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" id="registerBtn" class="btn btn-primary btn-lg btn-block">Register</button>
                                <div class="text-center mt-3">
                                    <span class="font-16 weight-600" data-color="#707373">
                                        Already have an account?
                                    </span>
                                    <a href="{{ route('login') }}" class="font-16 weight-600 text-primary">
                                        Login
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                    @endif

                    <!-- Resend Verification Form -->
                    <div class="mt-4">
                        <div class="text-center">
                            <span class="font-16 weight-600" data-color="#707373">
                                Didn't receive verification email?
                            </span>
                        </div>
                        <form method="POST" action="{{ route('email.verification.resend') }}" class="mt-2">
                            @csrf
                            <div class="input-group custom mb-3">
                                <input type="email" name="email" class="form-control form-control-lg @error('resend_email') is-invalid @enderror" placeholder="Enter your email address" value="{{ old('resend_email') }}" required />
                                <div class="input-group-append custom">
                                    <button type="submit" class="btn btn-outline-primary">Resend</button>
                                </div>
                                @error('resend_email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="/vendors/scripts/core.js"></script>
<script src="/vendors/scripts/script.min.js"></script>
<script src="/vendors/scripts/process.js"></script>
<script src="/vendors/scripts/layout-settings.js"></script>

<script>
// Simple form validation for required fields
const registerForm = document.getElementById('registerForm');
const requiredFields = registerForm.querySelectorAll('input[required]');

function validateForm() {
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
        }
    });
    
    return isValid;
}

// Enable submit button when form is valid
requiredFields.forEach(field => {
    field.addEventListener('input', function() {
        const registerBtn = document.getElementById('registerBtn');
        registerBtn.disabled = !validateForm();
    });
});

// Initial validation
document.addEventListener('DOMContentLoaded', function() {
    const registerBtn = document.getElementById('registerBtn');
    registerBtn.disabled = !validateForm();
});

// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const closeButton = alert.querySelector('.close');
            if (closeButton) {
                closeButton.click();
            }
        });
    }, 5000);

    // Name field validation - only letters, spaces, hyphens, and apostrophes
    const nameFields = ['first_name', 'last_name', 'middle_name', 'suffix'];
    
    nameFields.forEach(function(fieldId) {
        const field = document.getElementById(fieldId);
        if (field) {
            // Filter input in real-time
            field.addEventListener('input', function() {
                // Allow only letters (including Ñ/ñ), spaces, hyphens, and apostrophes
                this.value = this.value.replace(/[^A-Za-zÑñ\s\-\']/g, '');
            });
            
            // Prevent typing invalid characters
            field.addEventListener('keypress', function(e) {
                const char = String.fromCharCode(e.which || e.keyCode);
                // Allow letters (including Ñ/ñ), spaces, hyphens, apostrophes, and control keys (backspace, delete, etc.)
                if (!/[A-Za-zÑñ\s\-\']/.test(char) && !e.ctrlKey && !e.metaKey && e.keyCode !== 8 && e.keyCode !== 46 && e.keyCode !== 9) {
                    e.preventDefault();
                }
            });
        }
    });
});
</script>
</body>
</html>