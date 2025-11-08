<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Reset Password</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>

        <!-- Favicon -->
        <link href="landing-pages/assets/img/logo-ocnhs.png" rel="icon">>

        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/icon-font.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}" />
    </head>

<body>
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center"></div>
    </div>

        <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <img src="{{ asset('vendors/images/forgot-password.png') }}" alt="Reset Password"/>
                    </div>
                    <div class="col-md-6">
                        <div class="login-box bg-white box-shadow border-radius-10 p-4">
                            <div class="login-title mb-3">
                                <h2 class="text-center text-primary">Reset Password</h2>
                            </div>

                            @php
                                $emailError = $errors->first('email') ?? '';
                                $isTokenError = str_contains($emailError, 'expired') || str_contains($emailError, 'invalid');
                            @endphp

                            @if ($isTokenError)
                                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                    <strong>Oops!</strong> {{ $emailError }}
                                    <br>
                                    <a href="{{ route('password.request') }}" class="text-primary font-weight-bold">
                                        Request another reset link
                                    </a>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <!-- FORM -->
                            <form id="resetPasswordForm" method="POST" action="{{ route('password.store') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                <input type="hidden" name="email" value="{{ $request->email }}">

                                <!-- New Password Field with Icon -->
                                <div class="input-group custom mb-3">
                                    <input id="password" type="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        name="password" required
                                        placeholder="New Password"/>
                                    <div class="input-group-append custom">
                                        <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                    </div>
                                </div>
                                @error('password')
                                <div class="text-danger mb-2">{{ $message }}</div>
                                @enderror

                                <!-- Password Requirements -->
                                <ul id="password-requirements" class="password-requirements mt-0 mb-3 pl-3 small text-muted">
                                    <li data-rule="length">At least 8 characters</li>
                                    <li data-rule="upperlower">Contains uppercase & lowercase letters</li>
                                    <li data-rule="number">Contains at least one number</li>
                                    <li data-rule="special">Contains at least one special character</li>
                                </ul>

                                <!-- Confirm Password Field with Icon -->
                                <div class="input-group custom mb-3">
                                    <input id="password_confirmation" type="password"
                                        class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                        name="password_confirmation" required
                                        placeholder="Confirm New Password"/>
                                    <div class="input-group-append custom">
                                        <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                    </div>
                                </div>

                                <!-- Error message placeholder -->
                                <small id="confirm-password-error" class="text-danger mt-1" style="display:none;">
                                    Passwords do not match.
                                </small>
                                @error('password_confirmation')
                                <div class="text-danger mb-2">{{ $message }}</div>
                                @enderror

                                <!-- Show Password Checkbox -->
                                <div class="form-group form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="showPasswordCheckbox" onclick="togglePasswordVisibility()">
                                    <label class="form-check-label" for="showPasswordCheckbox">Show Password</label>
                                </div>

                                <!-- Submit Button -->
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <button type="submit" id="resetPasswordBtn" class="btn btn-primary btn-lg btn-block" disabled>
                                            Reset Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <!-- END FORM -->

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JS -->
        <script src="{{ asset('vendors/scripts/core.js') }}"></script>
        <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
        <script src="{{ asset('vendors/scripts/process.js') }}"></script>
        <script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>

        <script>
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const requirements = document.querySelectorAll('#password-requirements li');
        const confirmError = document.getElementById('confirm-password-error');
        const resetBtn = document.getElementById('resetPasswordBtn');

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

            // Enable/disable submit button
            resetBtn.disabled = !valid;
        }

        // Event listeners
        passwordInput.addEventListener('input', validatePassword);
        confirmInput.addEventListener('input', validatePassword);

        // Toggle password visibility
        function togglePasswordVisibility() {
            if (document.getElementById('showPasswordCheckbox').checked) {
                passwordInput.type = 'text';
                confirmInput.type = 'text';
            } else {
                passwordInput.type = 'password';
                confirmInput.type = 'password';
            }
        }

        // Final check on submit
        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            if(passwordInput.value !== confirmInput.value){
                e.preventDefault();
                confirmError.style.display = 'block';
                confirmInput.focus();
            }
        });
        </script>
    </body>
</html>
