   
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="some page description here">
        <meta name="keywords" content="page keywords here">  
        <link rel="shortcut icon" href="<?php echo e(url('images/favicon.ico')); ?>" type="image/x-icon" />
        <link rel="stylesheet" href="<?php echo e(url('css/master.min.css')); ?>">
        <title>Password Reset</title>
       	<link rel="stylesheet" type="text/css" href="<?php echo e(url('vendors/fa/css/all.css')); ?>">
       	<link rel="stylesheet" type="text/css" href="<?php echo e(url('vendors/sa/sweetalert.css')); ?>">
       	<link rel="stylesheet" type="text/css" href="<?php echo e(url('vendors/ve/css/validationEngine.jquery.css')); ?>">
    </head>

    <body class="home">
		<div class="home-form" style="margin-top: 100px">
			<img class="home_logo img-fluid d-block mx-auto" src="<?php echo e(\App\HelperX::appLogo()); ?>" alt="Logo">
			<h2 class="text-center system_name"><?php echo e(\App\HelperX::appName()); ?></h2>
			<?php if(session()->has('error')): ?>
			<div class="alert alert-danger flush">
				<i class="fa fa-ban"></i> <?php echo e(session()->get('error')); ?>

			</div>
			<script src="<?php echo e(url('js/jquery.min.js')); ?>"></script>
			<script type="text/javascript">
				$('.flush').delay(5000).fadeOut();
			</script>
			<?php endif; ?>

			<?php if(session()->has('success')): ?>
			<div class="alert alert-success flush">
				<i class="fa fa-check"></i> <?php echo e(session()->get('success')); ?>

			</div>
			<script src="<?php echo e(url('js/jquery.min.js')); ?>"></script>
			<script type="text/javascript">
				$('.flush').delay(5000).fadeOut();
			</script>
			<?php endif; ?>
			<div class="tab-content" id="nav-tabContent">
				<div class="tab-pane fade show active jsHeightLogin" id="nav-login" role="tabpanel" aria-labelledby="nav-login-tab">

					<div class="tab-content" id="nav-tabContent" >
						<div class="tab-pane fade show active" id="nav-password" role="tabpanel" aria-labelledby="nav-password-tab" >

							

							<form id="passwordResetForm" action="<?php echo e(route('app.reset.password.do')); ?>" method="POST" onsubmit="return processPasswordReset()">

								<?php echo e(csrf_field()); ?>	

								<input type="hidden" value="<?php echo e($input); ?>" name="email" />
 
								<div class="form-group">
									<label for="password">Password</label>
									<input type="password" class="validate[required] form-control" id="password" name="password"
									data-errormessage-value-missing="Password is required!" 
									 />
								</div>
								<div class="form-group">
									<label for="password_confirm">Confirm Password</label>
									<input type="password" class="validate[required,equals[password]] form-control" id="password_confirm" name="password_confirm" 
									data-errormessage-value-missing="Confirm password is required!" 
									data-errormessage="Password mismatches!" />
								</div>


								<div class="form-group mt-4">
									<button type="submit" route="" id="resetNow" class="btn btn-primary btn-block px-5 py-2">Reset Now!</button>
								</div>


							</form>


						</div>
					</div>
				</div>
			</div>
		</div>
        <script src="<?php echo e(url('js/jquery.min.js')); ?>"></script>
        <script src="<?php echo e(url('js/bootstrap.bundle.min.js')); ?>"></script>
        <script src="<?php echo e(url('js/matchHeight.min.js')); ?>"></script>
        <script src="<?php echo e(url('js/nprogress.min.js')); ?>"></script>
        <script src="<?php echo e(url('js/custom.min.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo e(url('vendors/sa/sweetalert.min.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo e(url('vendors/ve/js/languages/jquery.validationEngine-en.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo e(url('vendors/ve/js/jquery.validationEngine.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo e(url('vendors/bjs/biggo.js')); ?>"></script>
        <script type="text/javascript">

        	function processPasswordReset() {
        		return $("#passwordResetForm").validationEngine('validate');
        	}

        </script>

    </body>

</html>

