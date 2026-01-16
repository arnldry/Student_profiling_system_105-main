<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Email Verification</title>

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
    </style>
</head>
<body class="login-page">
<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 col-lg-5 mx-auto">
                <div class="login-box bg-white box-shadow border-radius-10">
                    <div class="text-center mb-3">
                        <img src="/vendors/images/logo-ocnhs.png" alt="OCNHS Logo" style="max-width: 150px;" />
                    </div>
                    <div class="login-title mb-30">
                        <h2 class="text-center text-primary">Create Your Password</h2>
                        <p class="text-center text-muted">Welcome {{ $pendingUser->name }}!</p>
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

                    <form id="passwordForm" method="POST" action="{{ route('email.verify') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Email Display -->
                        <div class="alert alert-info mb-3">
                            <i class="dw dw-email"></i>
                            <strong>Email:</strong> {{ $pendingUser->email }}
                        </div>

                        <!-- Password -->
                        <label for="password">Password</label>
                        <div class="input-group custom mb-3">
                            <input id="password" type="password" name="password" class="form-control form-control-lg" placeholder="Create Password" required />
                            <div class="input-group-append custom">
                                <span class="input-group-text"><i class="dw dw-lock"></i></span>
                            </div>
                        </div>

                        <ul id="password-requirements" class="password-requirements">
                            <li data-rule="length">At least 8 characters</li>
                            <li data-rule="upperlower">Contains uppercase & lowercase letters</li>
                            <li data-rule="number">Contains at least one number</li>
                            <li data-rule="special">Contains at least one special character</li>
                        </ul>

                        <!-- Confirm Password -->
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="input-group custom mb-3">
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="Confirm Password" required />
                            <div class="input-group-append custom">
                                <span class="input-group-text"><i class="dw dw-lock"></i></span>
                            </div>
                        </div>

                        <small id="confirm-password-error" class="text-danger mb-2" style="display:none;">
                            Passwords do not match.
                        </small>

                        <!-- Show Password Checkbox -->
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="showPasswordCheckbox" onclick="togglePasswordVisibility()">
                            <label class="custom-control-label" for="showPasswordCheckbox">Show Password</label>
                        </div>

                        <!-- Submit -->
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" id="submitBtn" class="btn btn-primary btn-lg btn-block" disabled>Create Password & Verify Email</button>
                            </div>
                        </div>
                    </form>
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
const passwordInput = document.getElementById('password');
const confirmInput = document.getElementById('password_confirmation');
const requirements = document.querySelectorAll('#password-requirements li');
const confirmError = document.getElementById('confirm-password-error');
const submitBtn = document.getElementById('submitBtn');

function validatePassword() {
    const value = passwordInput.value;
    let valid = true;

    requirements.forEach(li => {
        switch(li.dataset.rule) {
            case 'length':
                li.style.color = value.length >= 8 ? 'green' : 'red';
                if(value.length < 8) valid = false;
                break;
            case 'upperlower':
                const upperLower = /[a-z]/.test(value) && /[A-Z]/.test(value);
                li.style.color = upperLower ? 'green' : 'red';
                if(!upperLower) valid = false;
                break;
            case 'number':
                const hasNumber = /\d/.test(value);
                li.style.color = hasNumber ? 'green' : 'red';
                if(!hasNumber) valid = false;
                break;
            case 'special':
                const hasSpecial = /[\W_]/.test(value);
                li.style.color = hasSpecial ? 'green' : 'red';
                if(!hasSpecial) valid = false;
                break;
        }
    });

    // Check confirm password
    if(confirmInput.value !== '' && passwordInput.value !== confirmInput.value){
        confirmError.style.display = 'block';
        valid = false;
    } else {
        confirmError.style.display = 'none';
    }

    // Enable or disable Submit button
    submitBtn.disabled = !valid;
}

// Event listeners
passwordInput.addEventListener('input', validatePassword);
confirmInput.addEventListener('input', validatePassword);

// Toggle password visibility
function togglePasswordVisibility() {
    const type = document.getElementById('showPasswordCheckbox').checked ? 'text' : 'password';
    passwordInput.type = type;
    confirmInput.type = type;
}

// Final check before submit
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    if(passwordInput.value !== confirmInput.value){
        e.preventDefault();
        confirmError.style.display = 'block';
        confirmInput.focus();
    }
});
</script>
</body>
</html>
