<!DOCTYPE html>
	<html>
		<head>
			<!-- Basic Page Info -->
			<meta charset="utf-8" />
			<title>Student Accounts</title>

			<!-- Site favicon -->
			<link rel="icon" type="image/png" sizes="16x16" href="/vendors/images/logo-ocnhs.png"/>

			<!-- Mobile Specific Metas -->
			<meta name="viewport"
				content="width=device-width, initial-scale=1, maximum-scale=1"
			/>

			<!-- Google Font -->
			<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>

			<!-- CSS -->
			<link rel="stylesheet" type="text/css" href="/vendors/styles/core.css" />
			<link rel="stylesheet" type="text/css" href="/vendors/styles/icon-font.min.css"/>
			<link rel="stylesheet" type="text/css" href="/src/plugins/datatables/css/dataTables.bootstrap4.min.css"/>
			<link rel="stylesheet" type="text/css" href="/src/plugins/datatables/css/responsive.bootstrap4.min.css"/>
			<link rel="stylesheet" type="text/css" href="/vendors/styles/style.css" />
		
			</head>
		<body>
			<!-- header -->
			<div class="header">
				@include('layouts.navbar.superadmin.navbar')
			</div>
			<div class="left-side-bar">
				@include('layouts.sidebar.superadmin.sidebar')
			</div>

			<!-- main container -->
			<div class="main-container">
				<div class="xs-pd-20-10 pd-ltr-20">
					<div class="title pb-20">
						<h2 class="h3 mb-0">
						Student Accounts
						</h2>
					</div>

					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">Students account here.</p>
						</div>

						

						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
										<th>Name</th>
										<th>Email</th>
										
										<th class="datatable-nosort">Status</th>
										 <!-- <th>Status</th> -->
									</tr>
								</thead>
								<tbody>
									@foreach($users as $user)
										@if($user->role === 'student') {{-- Show only students --}}
										<tr>
											<td>{{ $user->name }}</td>
											<td>{{ $user->email }}</td>
											

											{{-- Actions --}}
											<!-- <td>
												<a href="" class="btn btn-sm btn-primary">
													<i class="dw dw-edit2"></i> Edit
												</a>
											</td> -->
											
											<!-- Status Toggle Button -->
											<td>
												<form action="{{ route('superadmin.users.toggleStatus', $user->id) }}" 
													method="POST" 
													class="d-inline toggle-status-form">
													@csrf
													@method('PATCH')
													@if($user->status === 'active')
														<button type="submit" class="btn btn-sm btn-success" data-status="active">
															<i class="dw dw-unlock"></i> Active
														</button>
													@else
														<button type="submit" class="btn btn-sm btn-secondary" data-status="inactive">
															<i class="dw dw-lock"></i> Inactive
														</button>
													@endif
												</form>
											</td> 
										</tr>

										@include('superadmin.partials.edit-profile', ['user' => $user])

										@endif
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>        
			</div>
			<!-- welcome modal start -->
			
		<!-- welcome modal end -->
		<!-- js -->
		<script src="/vendors/scripts/core.js"></script>
		<script src="/vendors/scripts/script.min.js"></script>
		<script src="/vendors/scripts/process.js"></script>
		<script src="/vendors/scripts/layout-settings.js"></script>
		<script src="/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		<!-- buttons for Export datatable -->
		<script src="/src/plugins/datatables/js/dataTables.buttons.min.js"></script>
		<script src="/src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
		<script src="/src/plugins/datatables/js/buttons.print.min.js"></script>
		<script src="/src/plugins/datatables/js/buttons.html5.min.js"></script>
		<script src="/src/plugins/datatables/js/buttons.flash.min.js"></script>
		<script src="/src/plugins/datatables/js/pdfmake.min.js"></script>
		<script src="/src/plugins/datatables/js/vfs_fonts.js"></script>
		<!-- Datatable Setting js -->
		<script src="/vendors/scripts/datatable-setting.js"></script>
	
		<!-- SweetAlert2 -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
			<script>
				// Delete confirmation
				$(document).ready(function(){
					$('.delete-user-form').on('submit', function(e){
						e.preventDefault(); // prevent default form submission
						let form = this;

						Swal.fire({
							title: 'Are you sure?',
							text: "This action cannot be undone!",
							icon: 'warning',
							showCancelButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Yes, delete it!'
						}).then((result) => {
							if (result.isConfirmed) {
								form.submit(); // submit form if confirmed
							}
						})
					});
				});

				// Flash messages
				@if (session('success'))
				Swal.fire({
					icon: 'success',
					title: 'Success',
					text: '{{ session('success') }}',
					timer: 2000,
					showConfirmButton: false
				});
				@endif

					@if (session('error'))
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: '{{ session('error') }}',
					});
				@endif


				// Toggle status confirmation for Activate/Deactivate
				$(document).ready(function(){
				$('.toggle-status-form').on('submit', function(e){
					e.preventDefault();
					let form = this;
					let status = $(this).find('button[type="submit"]').data('status'); 

					let actionText = status === 'active' 
						? "Are you sure you want to Deactivate this user?" 
						: "Are you sure you want to Activate this user?";

					Swal.fire({
						title: 'Confirm Action',
						text: actionText,
						icon: 'question',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, proceed'
					}).then((result) => {
						if (result.isConfirmed) {
							form.submit();
						}
					});
				});
			});

			// Flash messages
			@if (session('success'))
			Swal.fire({
				icon: 'success',
				title: 'Success',
				text: '{{ session('success') }}',
				timer: 2000,
				showConfirmButton: false
			});
			@endif
		</script>
	</body>
</html>
