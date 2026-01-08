<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>System Recovery</title>
<link rel="icon" href="/vendors/images/logo-ocnhs.png">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="/vendors/styles/core.css" />
<link rel="stylesheet" href="/vendors/styles/icon-font.min.css"/>
<link rel="stylesheet" href="/vendors/styles/style.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
body { font-family: 'Inter', sans-serif; background: #f3f4f6; display:flex; align-items:center; justify-content:center; height:100vh; margin:0; }
.recovery-card { background:#fff; border-radius:16px; width:420px; padding:40px 30px; box-shadow:0 8px 25px rgba(0,0,0,0.1); text-align:center; }
.recovery-card h2 { color:#e11d48; font-size:24px; margin-bottom:20px; }
.recovery-card p { color:#4b5563; font-size:14px; margin-bottom:20px; }
.recovery-card .form-control { border-radius:8px; height:45px; font-size:14px; }
.recovery-card button { border-radius:8px; height:45px; font-size:15px; font-weight:600; transition:all 0.2s ease; }
.btn-login { background:#3b82f6; color:white; } .btn-login:hover { background:#2563eb; }
.btn-upload { background:#10b981; color:white; } .btn-upload:hover { background:#059669; }
.btn-logout { background:#ef4444; color:white; } .btn-logout:hover { background:#b91c1c; }
.show-password { text-align:left; font-size:13px; margin-bottom:15px; }
.alert { font-size:13px; padding:8px 12px; margin-top:10px; color:red; }
</style>
</head>
<body>

<div class="recovery-card">
    <h2><i class="icon-copy dw dw-warning"></i> System Database Missing</h2>

    {{-- Login form --}}
    @if($dbMissing && !$staticLogged)
        <p>Login as System Admin to restore the database:</p>
        @if ($errors->any()) <div class="alert">{{ $errors->first() }}</div> @endif
        <form method="POST" action="{{ route('recovery.login') }}">
            @csrf
            <input type="text" name="username" placeholder="Username" class="form-control mb-3" required>
            <input type="password" name="password" placeholder="Password" class="form-control mb-3" required>
            <button type="submit" class="btn btn-login btn-block">Login</button>
        </form>
    @endif

    {{-- Upload form --}}
    @if($staticLogged)
        <p>Upload encrypted backup (.enc) or SQL file to restore the database:</p>
        @if ($errors->any()) <div class="alert">{{ $errors->first() }}</div> @endif
        <form id="uploadBackupForm" method="POST" action="{{ route('recovery.upload') }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="sql_file" accept=".sql,.txt,.enc" class="form-control mb-3" required>
            <button type="submit" class="btn btn-upload btn-block">⬆ Upload Backup</button>
        </form>
    @endif
</div>

{{-- Auto logout after upload --}}
@if(session('auto_logout'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: '{{ session('success') }} You will be logged out in 3 seconds.',
    timer: 3000,
    timerProgressBar: true,
    showConfirmButton: false
});
setTimeout(function() {
    let form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('recovery.logout') }}";
    let token = document.createElement('input');
    token.type = 'hidden';
    token.name = '_token';
    token.value = '{{ csrf_token() }}';
    form.appendChild(token);
    document.body.appendChild(form);
    form.submit();
}, 3000);
</script>
@endif


<script>
function togglePasswordVisibility() {
    const passwordField = document.getElementById('password');
    const checkbox = document.getElementById('showPasswordCheckbox');
    passwordField.type = checkbox.checked ? 'text' : 'password';
}

document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('uploadBackupForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'This operation involves confidential information. Continue?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, upload!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) this.submit();
            });
        });
    }
});
</script>

<script src="/vendors/scripts/core.js"></script>
<script src="/vendors/scripts/script.min.js"></script>
<script src="/vendors/scripts/process.js"></script>
<script src="/vendors/scripts/layout-settings.js"></script>
</body>
</html>
