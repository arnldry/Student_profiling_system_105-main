@php
use Illuminate\Support\Facades\Auth;
use App\Models\AdditionalInformation;

// Check if the logged-in user has Additional Information
$hasAdditionalInfo = $hasAdditionalInfo ?? (
    Auth::check() && Auth::user()->role === 'student'
        ? AdditionalInformation::where('learner_id', Auth::id())->exists()
        : false
);
@endphp

<style>
.disabled-link {
    pointer-events: none;
    opacity: 0.5;
    cursor: not-allowed;
}
#accordion-menu a.active {
    background-color: #007bff;
    color: #fff !important;
    font-weight: bold;
}
</style>

<div class="left-side-bar">
    <div class="brand-logo {{ !$hasAdditionalInfo ? 'disabled-link' : '' }}">
        <a href="{{ route('student.dashboard') }}">
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
                    <a href="{{ route('student.dashboard') }}"
                       class="dropdown-toggle no-arrow {{ !$hasAdditionalInfo ? 'disabled-link' : '' }}">
                        <span class="micon bi bi-house"></span>
                        <span class="mtext">Dashboard</span>
                    </a>
                </li>

                {{-- Test --}}
                <li>
                    <a href="{{ route('student.testingdash') }}"
                       class="dropdown-toggle no-arrow {{ !$hasAdditionalInfo ? 'disabled-link' : '' }}">
                        <span class="micon bi bi-clipboard-data"></span>
                        <span class="mtext">Test</span>
                    </a>
                </li>

                {{-- Additional Information --}}
                <li>
                    <a href="{{ route('student.additional-info') }}"
                       class="dropdown-toggle no-arrow {{ $hasAdditionalInfo ? 'disabled-link' : '' }}">
                        <span class="micon dw dw-information"></span>
                        <span class="mtext">Additional Information</span>
                    </a>
                </li>

                {{-- Profile --}}
                <li>
                    <a href="{{ route('student.update-profile') }}"
                       class="dropdown-toggle no-arrow {{ !$hasAdditionalInfo ? 'disabled-link' : '' }}">
                        <span class="micon bi bi-person-circle"></span>
                        <span class="mtext">Change Password</span>
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

{{-- SweetAlert and Active Menu --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const currentUrl = window.location.href;
    const menuLinks = document.querySelectorAll("#accordion-menu a");

    // Highlight active menu link
    menuLinks.forEach(link => {
        if (link.href === currentUrl) {
            link.classList.add("active");
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
