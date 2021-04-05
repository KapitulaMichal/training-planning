<?php

	session_start();
		
	if ((isset($_SESSION['signedin'])) && ($_SESSION['signedin']==true))
	{
		if($_SESSION['user_type'] == 3)
		{
			header('Location: users.php');
		}
		else
		{
			header('Location: training.php');
		}
	}
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
		               		<h1 class="title">Login</h1>
		               		<hr />
		               	</div>
		            </div>
				<div class="main-login main-center">
					<form class="form-horizontal" method="post" action="login.php">
							<div class="form-group">
								<label class="cols-sm-2 control-label">Login</label>
								<div class="cols-sm-10">
									<div class="input-group">
										<input type="text" class="form-control" name="login" placeholder="Enter your login"/>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="cols-sm-2 control-label">Password</label>
								<div class="cols-sm-10">
									<div class="input-group">
										<input type="password" class="form-control" name="password" placeholder="Enter your password"/>
									</div>
								</div>
							</div>

							<div class="form-group ">
								<button type="submit button" class="btn btn-primary btn-lg btn-block login-button">Login</button>
							</div>
					</form>
						<div class="login-register">
							<a href="register.php">Register</a>
						</div>

						<?php
							if(isset($_SESSION['error']))
							echo "<br>".$_SESSION['error']."</label></div>"							
						?>
				</div>
			</div>
		</div>

		<!--<script type="text/javascript" src="bootstrap.js"></script>-->
	</body>
</html>