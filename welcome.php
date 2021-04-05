<?php

	session_start();
	
	if (!isset($_SESSION['successful_registration']))
	{
		header('Location: index.php');
		exit();
	}
	else
	{
		unset($_SESSION['successful_registration']);
	}
	
	//Usuwanie zmiennych pamiętających wartości wpisane do formularza
	if (isset($_SESSION['rf_name'])) unset($_SESSION['rf_name']);
	if (isset($_SESSION['rf_surname'])) unset($_SESSION['rf_surname']);
	if (isset($_SESSION['rf_login'])) unset($_SESSION['rf_login']);
	if (isset($_SESSION['rf_email'])) unset($_SESSION['rf_email']);
	if (isset($_SESSION['rf_password1'])) unset($_SESSION['rf_password1']);
	if (isset($_SESSION['rf_password2'])) unset($_SESSION['rf_password2']);

	//Usuwanie błędów rejestracji
	if (isset($_SESSION['e_name'])) unset($_SESSION['e_name']);
	if (isset($_SESSION['e_surname'])) unset($_SESSION['e_surname']);
	if (isset($_SESSION['e_login'])) unset($_SESSION['e_login']);
	if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
	if (isset($_SESSION['e_password'])) unset($_SESSION['e_password']);
	
?>

<!DOCTYPE html>
<html lang="en">
    <head> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="bootstrap.css">

		<!-- Website CSS style -->
		<link rel="stylesheet" type="text/css" href="register.css">

		<!-- Website Font style -->
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
		
		<!-- Google Fonts -->
		<link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>

		<title>Login</title>
	</head>
	<body>
		<div class="container">
			<div class="row main">
					<div class="panel-heading">
		               <div class="panel-title text-center">
		               		<h1 class="title">Welcome</h1>
		               		<hr />
		               	</div>
		            </div>
				<div class="main-login main-center">
					<form class="form-horizontal" method="post" action="login.php">
							<div class="form-group">
								<label class="cols-sm-2 control-label">You can now log in to your account! </label>
								
							</div>
							<div class="form-group ">
								<a href = "index.php">
									<button type="button" class="btn btn-primary btn-lg btn-block login-button">Login</button>
								</a>
							</div>
					</form>
				</div>
			</div>
		</div>

		<!--<script type="text/javascript" src="bootstrap.js"></script>-->
	</body>
</html>