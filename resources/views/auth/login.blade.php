<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Login</title>

		<!-- Site favicon -->
		<link href="landing-pages/assets/img/logo-ocnhs.png" rel="icon">

		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

		<!-- Google Font -->
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="/vendors/styles/core.css" />
		<link rel="stylesheet" type="text/css" href="/vendors/styles/icon-font.min.css" />
		<link rel="stylesheet" type="text/css" href="/vendors/styles/style.css" />

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
						<div class="login-title">
							<h2 class="text-center text-primary">Login</h2>
						</div>

						<!-- SPECIFIC ERROR FOR INVALID CREDENTIALS - NASA TAAS -->
						@error('invalid_credentials')
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								{{ $message }}
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
						@enderror

						<!-- OTHER ERROR MESSAGES -->
						@if($errors->has('email') && !$errors->has('invalid_credentials'))
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								{{ $errors->first('email') }}
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
						@endif

						<!-- SUCCESS MESSAGES -->
						@if(session('success'))
							<div class="alert alert-success alert-dismissible fade show" role="alert">
								{{ session('success') }}
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
						@endif

						@if (session('reset'))
							<div class="alert alert-success alert-dismissible fade show" role="alert">
								{{ session('reset') }}
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
						@endif

						@if(session('register'))
							<div class="alert alert-success alert-dismissible fade show" role="alert">
								{{ session('register') }}
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
						@endif

						{{-- Laravel Login Form --}}
						<form method="POST" action="{{ route('login') }}">
							@csrf
							
							<div class="input-group custom">
								<input type="email" name="email" class="form-control form-control-lg" placeholder="Email" value="{{ old('email') }}" required autofocus />
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
								</div>
							</div>

							<div class="input-group custom mt-3">
								<input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="**********" required />
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>
							</div>

							<div class="row pb-30 mt-3">
								<div class="col-6">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" id="showPasswordCheckbox" onclick="togglePasswordVisibility()">
										<label class="custom-control-label" for="showPasswordCheckbox">Show Password</label>
									</div>
								</div>
								<div class="col-6 text-right">
									@if (Route::has('password.request'))
										<a href="{{ route('password.request') }}">Forgot Password?</a>
									@endif
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
										<button type="submit" class="btn btn-primary btn-lg btn-block">Login</button>
									</div>

									<div class="text-center mt-3">
										<span class="font-16 weight-600" data-color="#707373">
											Don't have Student Account? 
										</span>
										<a href="{{ route('register') }}" class="font-16 weight-600 text-primary">
											Sign up
										</a>
									</div>

									<div class="text-center mt-3">											
										<a href="{{ route('landing') }}" class="font-16 weight-600 text-secondary">
											<i class="fa fa-arrow-left me-2"></i> Go back to Homepage
										</a>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="/vendors/scripts/core.js"></script>
	<script src="/vendors/scripts/script.min.js"></script>
	<script src="/vendors/scripts/process.js"></script>
	<script src="/vendors/scripts/layout-settings.js"></script>

	<script>
	function togglePasswordVisibility() {
		const passwordField = document.getElementById('password');
		const checkbox = document.getElementById('showPasswordCheckbox');
		
		if (checkbox.checked) {
			passwordField.type = 'text';
		} else {
			passwordField.type = 'password';
		}
	}

	// Auto-dismiss alerts after 5 seconds
	document.addEventListener('DOMContentLoaded', function() {
		setTimeout(function() {
			const alerts = document.querySelectorAll('.alert');
			alerts.forEach(function(alert) {
				const closeButton = alert.querySelector('.close');
				if (closeButton) {
					closeButton.click();
				}
			});
		}, 5000);
	});
	</script>
</body>
</html>