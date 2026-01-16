<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Confirm Password</title>

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
							<h2 class="text-center text-primary">Confirm Password</h2>
						</div>

						<p class="text-center mb-4">This is a secure area of the application. Please confirm your password before continuing.</p>

						<form method="POST" action="{{ route('password.confirm') }}">
							@csrf

							<div class="input-group custom">
								<input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Password" required autocomplete="current-password" />
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>
							</div>

							@if($errors->has('password'))
								<div class="text-danger mt-2">{{ $errors->first('password') }}</div>
							@endif

							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
										<button type="submit" class="btn btn-primary btn-lg btn-block">Confirm</button>
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
</body>
</html>
