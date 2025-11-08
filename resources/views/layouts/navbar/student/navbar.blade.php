<div class="header">
	<div class="header-left">
		<div class="menu-icon bi bi-list"></div>
		<div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
	</div>

	<div class="header-right">
		<div class="user-info-dropdown">
			<div class="dropdown">
				<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
					<span class="user-icon" style="background: transparent; border: none; display: inline-block;">
						<i class="bi bi-person-circle" style="color:black"></i>
					</span>
					<span class="user-name">{{ Auth::user()->name }}!</span>
				</a>

				<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
					<a class="dropdown-item" href="{{ route('student.update-profile') }}">
						<i class="dw dw-user1"></i> Profile
					</a>

					<a href="#" id="studentLogout" class="dropdown-item">
						<i class="dw dw-logout"></i> Logout
					</a>

					<form id="studentLogoutForm" method="POST" action="{{ route('recovery.logout') }}" style="display: none;">
						@csrf
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
	const logoutBtn = document.getElementById("studentLogout");
	const logoutForm = document.getElementById("studentLogoutForm");

	logoutBtn.addEventListener("click", function(e) {
		e.preventDefault();
		Swal.fire({
			title: "Are you sure you want to logout?",
			text: "You will be redirected to the Homepage.",
			icon: "question",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes, logout",
			cancelButtonText: "Cancel"
		}).then((result) => {
            if (result.isConfirmed) {
                logoutForm.submit();
            }
		});
	});
});
</script>
