<!--  Edit User Modal -->
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            <!-- Header -->
            <div class="modal-header bg-white border-bottom">
                <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">
                    <i class="dw dw-edit2 mr-2 text-primary"></i> Edit User
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            
            <!-- Form -->
            <form action="{{ route('superadmin.admin-accounts.update', $user->id) }}" method="POST" onsubmit="return validateEmail{{ $user->id }}(this)">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    
                    <!-- Name -->
                    <div class="form-group">
                        <label for="name{{ $user->id }}">Name</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name{{ $user->id }}" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               pattern="[A-Za-zÑñ\s\-\']+" 
                               title="Only letters (including Ñ/ñ), spaces, hyphens, and apostrophes are allowed"
                               required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email{{ $user->id }}">Email</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email{{ $user->id }}" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               {{ $user->role == 'superadmin' ? 'disabled' : '' }} 
                               required>
                        <div id="emailError{{ $user->id }}" class="invalid-feedback" style="display:none;"></div>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <!-- Role -->
                    <div class="form-group">
                        <label for="role{{ $user->id }}">Role</label>
                        <select class="form-control" 
                                id="role{{ $user->id }}" 
                                name="role" 
                                {{ $user->role == 'superadmin' ? 'disabled' : '' }} 
                                required>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            
                        </select>
                    </div>
                    
                </div>
                
              <!-- Footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="dw dw-diskette"></i> Save Changes
                </button>
            </div>

            </form>
        </div>
    </div>
</div>

<script>
    // Name field validation - only letters (including Ñ/ñ), spaces, hyphens, and apostrophes
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name{{ $user->id }}');
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
    });

    function validateEmail{{ $user->id }}(form) {
        let emailInput = form.email;
        let email = emailInput.value.trim();
        let regex = /^[\w.%+-]+@(gmail|yahoo)\.com$/i;
        let errorDiv = document.getElementById("emailError{{ $user->id }}");

        // Reset state
        emailInput.classList.remove("is-invalid");
        errorDiv.style.display = "none";
        errorDiv.innerText = "";

        if (!regex.test(email)) {
            emailInput.classList.add("is-invalid");
            errorDiv.innerText = "Only Gmail or Yahoo email addresses are allowed.";
            errorDiv.style.display = "block";
            return false; 
        }
        return true;
    }
</script>
