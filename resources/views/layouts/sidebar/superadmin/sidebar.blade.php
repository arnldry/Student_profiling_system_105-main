<div class="left-side-bar">
	<div class="brand-logo">
		<a href="{{ route('superadmin.dashboard') }}">
            <img src="/vendors/images/Wireframe_-_1__5_-removebg-preview.png" alt="" class="dark-logo"/>
            <img src="/vendors/images/Wireframe_-_1__5_-removebg-preview.png" alt="" class="light-logo" />
		</a>
		<div class="close-sidebar" data-toggle="left-sidebar-close">
			<i class="ion-close-round"></i>
		</div>
	</div>

	<div class="menu-block customscroll">
		<div class="sidebar-menu">
			<ul id="accordion-menu">
				<li>
					<a href="{{ route('superadmin.dashboard') }}" class="dropdown-toggle no-arrow">
						<span class="micon bi bi-house"></span>
						<span class="mtext">Dashboard</span>
					</a>
				</li>

				

				<!-- User Management Section -->
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon bi bi-people"></span>
						<span class="mtext">User Management</span>
					</a>
					<ul class="submenu">
						<li><a href="{{ route('superadmin.admin-accounts') }}">Admin Account</a></li>
						<li><a href="{{ route('superadmin.student-accounts') }}">Student Account</a></li>
					</ul>
				</li>

				<!-- Settings Section -->
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon bi bi-gear-fill"></span>
						<span class="mtext">Settings</span>
					</a>
					<ul class="submenu">
						<li><a href="{{ route('superadmin.backup-restore') }}">Backup & Restore</a></li>
						<li><a href="{{ route('superadmin.school-year') }}">Manage School Year</a></li>
						<li><a href="{{ route('superadmin.curriculum') }}">Manage Curriculum</a></li>
						<li><a href="{{ route('superadmin.archived-student-data')}}">Archived Student Data</a></li>
					</ul>
				</li>

				<!-- Archived Section -->
				<!-- <li>
					<a href="{{ route('superadmin.archived-files')}}" class="dropdown-toggle no-arrow">
						<span class="micon bi bi-archive"></span>
						<span class="mtext">Archived</span>
					</a>
				</li> -->

				<li>
					<a href="{{ route('superadmin.update-profile') }}" class="dropdown-toggle no-arrow">
						<span class="micon bi bi-person"></span>
						<span class="mtext">Profile</span>
					</a>
				</li>

				<!-- Logout Section -->	
				<li>
					<a href="javascript:void(0);" id="logoutLink" class="dropdown-toggle no-arrow">
						<span class="micon bi bi-box-arrow-right"></span>
						<span class="mtext">Logout</span>
					</a>
					<form id="logout-form" action="{{ route('recovery.logout') }}" method="POST" style="display: none;">
						@csrf
					</form>
				</li>
			</ul>
		</div>
	</div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const currentUrl = window.location.href;
    const menuLinks = document.querySelectorAll("#accordion-menu a");

    // Highlight active link
    menuLinks.forEach(link => {
        if (link.href === currentUrl) {
            link.classList.add("active");

            const parentDropdown = link.closest(".dropdown");
            if (parentDropdown) {
                const submenu = parentDropdown.querySelector(".submenu");
                submenu.style.display = "block";
                parentDropdown.querySelector("a.dropdown-toggle").classList.add("active");
            }
        }
    });

    // SweetAlert logout confirmation
    const logoutLink = document.getElementById("logoutLink");
    const logoutForm = document.getElementById("logout-form");

    logoutLink.addEventListener("click", function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Are you sure you want to logout?",
            text: "You will be redirected to Homepage.",
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

<style>
/* Active page styling */
#accordion-menu a.active {
	background-color: #007bff;
	color: #fff !important;
	font-weight: bold;
}
</style>
