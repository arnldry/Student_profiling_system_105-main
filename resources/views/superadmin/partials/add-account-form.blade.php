<div class="collapse {{ $errors->any() ? 'show' : '' }}" id="addAccountForm">
    <div class="card mt-3 mb-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">Create New Account</h5>

            <form action="{{ route('superadmin.admin-accounts.store') }}" method="POST" id="createAccountForm">
                @csrf

                <!-- Name & Email -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name"
                               class="form-control"
                               placeholder="Full name" 
                               value="{{ old('name') }}" 
                               pattern="[A-Za-zÑñ\s\-\']+" 
                               title="Only letters (including Ñ/ñ), spaces, hyphens, and apostrophes are allowed"
                               required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email"
                               class="form-control"
                               placeholder="Email" value="{{ old('email') }}" required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Password & Confirm Password -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input id="password" type="password" name="password"
                               class="form-control"
                               placeholder="Password" required>
                        <ul id="password-requirements" class="password-requirements mt-2 mb-0 pl-3 small text-muted">
                            <li data-rule="length">At least 8 characters</li>
                            <li data-rule="upperlower">Contains uppercase & lowercase letters</li>
                            <li data-rule="number">Contains at least one number</li>
                            <li data-rule="special">Contains at least one special character</li>
                        </ul>
                        @if ($errors->has('password'))
                            @foreach ($errors->get('password') as $error)
                                <small class="text-danger">{{ $error }}</small><br>
                            @endforeach
                        @endif
                    </div>

                    <div class="form-group col-md-6">
                        <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               class="form-control"
                               placeholder="Confirm Password" required>
                        <small id="confirm-password-error" class="text-danger mt-1" style="display:none;">
                            Passwords do not match.
                        </small>
                    </div>
                </div>

                <!-- Show Password Checkbox -->
                <div class="form-group form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="showPasswordCheckbox" onclick="togglePasswordVisibility()">
                    <label class="form-check-label" for="showPasswordCheckbox">Show Password</label>
                </div>

                <!-- Hidden Role Field - Automatically set to admin -->
                <input type="hidden" name="role" value="admin">

                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="dw dw-save"></i> Save Account
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* Simplified Password Requirements (no icons) */
.password-requirements li {
    list-style: none;
    margin-bottom: 3px;
    color: #6c757d;
    transition: color 0.3s ease;
}

/* Add red asterisk for required labels */
label { font-weight: 500; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const requirements = document.querySelectorAll('#password-requirements li');
    const confirmError = document.getElementById('confirm-password-error');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('createAccountForm');

    // Validate password
    function validatePassword() {
        const value = passwordInput.value;
        let valid = true;

        requirements.forEach(li => {
            switch (li.dataset.rule) {
                case 'length':
                    li.style.color = value.length >= 8 ? 'green' : 'red';
                    if (value.length < 8) valid = false;
                    break;
                case 'upperlower':
                    const upperLower = /[a-z]/.test(value) && /[A-Z]/.test(value);
                    li.style.color = upperLower ? 'green' : 'red';
                    if (!upperLower) valid = false;
                    break;
                case 'number':
                    const hasNumber = /\d/.test(value);
                    li.style.color = hasNumber ? 'green' : 'red';
                    if (!hasNumber) valid = false;
                    break;
                case 'special':
                    const hasSpecial = /[\W_]/.test(value);
                    li.style.color = hasSpecial ? 'green' : 'red';
                    if (!hasSpecial) valid = false;
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

        // Enable or disable submit button
        submitBtn.disabled = !valid;
    }

    // Event listeners
    passwordInput.addEventListener('input', validatePassword);
    confirmInput.addEventListener('input', validatePassword);

    // Show/hide password
    window.togglePasswordVisibility = function () {
        const type = document.getElementById('showPasswordCheckbox').checked ? 'text' : 'password';
        passwordInput.type = type;
        confirmInput.type = type;
    };

    // Reset password fields on error reload
    @if($errors->any())
        passwordInput.value = '';
        confirmInput.value = '';
    @endif

    // Name field validation - only letters (including Ñ/ñ), spaces, hyphens, and apostrophes
    const nameInput = document.getElementById('name');
    if (nameInput) {
        // Filter input in real-time
        nameInput.addEventListener('input', function() {
            // Allow only letters (including Ñ/ñ), spaces, hyphens, and apostrophes
            this.value = this.value.replace(/[^A-Za-zÑñ\s\-\']/g, '');
        });
        
        // Prevent typing invalid characters
        nameInput.addEventListener('keypress', function(e) {
            const char = String.fromCharCode(e.which || e.keyCode);
            // Allow letters (including Ñ/ñ), spaces, hyphens, apostrophes, and control keys (backspace, delete, etc.)
            if (!/[A-Za-zÑñ\s\-\']/.test(char) && !e.ctrlKey && !e.metaKey && e.keyCode !== 8 && e.keyCode !== 46 && e.keyCode !== 9) {
                e.preventDefault();
            }
        });
    }

    // SweetAlert confirmation before submit
    form.addEventListener('submit', function (e) {
        if (passwordInput.value !== confirmInput.value) {
            e.preventDefault();
            confirmError.style.display = 'block';
            confirmInput.focus();
            return;
        }

        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to create this account?",
            icon: 'Question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, create it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
