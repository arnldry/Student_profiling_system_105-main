<div class="left-side-bar">
	<div class="brand-logo">
		<a href="{{ route('admin.dashboard') }}">
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
				{{-- Dashboard --}}
				<li>
					<a href="{{ route('admin.dashboard') }}" class="dropdown-toggle no-arrow">
						<span class="micon bi bi-house"></span>
						<span class="mtext">Dashboard</span>
					</a>
				</li>

				{{-- Student Profile --}}
				<li>
					<a href="{{ route('admin.student-profile') }}" class="dropdown-toggle no-arrow">
						<span class="micon bi bi-people"></span>
						<span class="mtext">Student Profile</span>
					</a>
				</li>

				{{-- Student Accounts --}}
				<li>
					<a href="{{ route('admin.student-accounts') }}" class="dropdown-toggle no-arrow">
						<span class="micon bi bi-person-lines-fill"></span>
						<span class="mtext">Student Accounts</span>
					</a>
				</li>


				{{-- Testing --}}
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<span class="micon bi bi-clipboard-data"></span>
						<span class="mtext">Testing</span>
					</a>
					<ul class="submenu">
						<li>
							<a href="{{ route('admin.test-results') }}">Test Results</a>
						</li>
						<li>
							<a href="{{ route('admin.manage-test') }}">Manage Test</a>
						</li>
					</ul>
				</li>

				{{-- Activity Log --}}
				<li>
					<a href="{{ route('admin.activity-log') }}" class="dropdown-toggle no-arrow">
						<span class="micon bi bi-journal-text"></span>
						<span class="mtext">Activity Log</span>
					</a>
				</li>

				{{-- Archived Files --}}
				<li>
					<a href="{{ route('admin.archived-files-data') }}" class="dropdown-toggle no-arrow">
						<span class="micon bi bi-archive"></span>
						<span class="mtext">Archived</span>
					</a>
				</li>

				{{-- Profile --}}
				<li>
					<a href="{{ route('admin.update-profile') }}" class="dropdown-toggle no-arrow">
						<span class="micon bi bi-person"></span>
						<span class="mtext">Profile</span>
					</a>
				</li>

				{{-- Logout --}}
				<li>
					<a href="{{ route('logout') }}" id="logoutLink" class="dropdown-toggle no-arrow">
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

{{-- SweetAlert and Active Link Script --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const currentUrl = window.location.href;
    const menuLinks = document.querySelectorAll("#accordion-menu a");

    // ✅ Highlight current page
    menuLinks.forEach(link => {
        if (link.href === currentUrl) {
            link.classList.add("active");
            const parentDropdown = link.closest(".dropdown");
            if (parentDropdown) {
                const submenu = parentDropdown.querySelector(".submenu");
                if (submenu) submenu.style.display = "block";
                parentDropdown.querySelector("a.dropdown-toggle").classList.add("active");
            }
        }
    });

    // ✅ SweetAlert Logout Confirmation
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
/* ✅ Active menu styling */
#accordion-menu a.active {
	background-color: #007bff;
	color: #fff !important;
	font-weight: bold;
}
</style>
