<?php
session_start();
	
	if (isset($_POST['email']))
	{
		$OK=true;
		
		$name = $_POST['name'];
		
		if ((strlen($name)<2) || (strlen($name)>20))
		{
			$OK=false;
			$_SESSION['e_name']="Name has to be between 2 and 20 characters!";
		}

		$surname = $_POST['surname'];

		if ((strlen($surname)<2) || (strlen($surname)>40))
		{
			$OK=false;
			$_SESSION['e_surname']="Surname has to be between 2 and 20 characters!";
		}

		$login = $_POST['login'];

		if ((strlen($login)<2) || (strlen($login)>20))
		{
			$OK=false;
			$_SESSION['e_login']="Login has to be between 2 and 20 characters!";
		}
		
		if (ctype_alnum($login)==false)
		{
			$OK=false;
			$_SESSION['e_login']="Login can contain only letters and numbers";
		}
		
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			$OK=false;
			$_SESSION['e_email']="Provide a valid email address!";
		}
		
		//Sprawdź poprawność hasła
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		
		if ((strlen($password1)<5) || (strlen($password1)>20))
		{
			$OK=false;
			$_SESSION['e_password']="Password has to be between 2 and 20 characters!";
		}
		
		if ($password1!=$password2)
		{
			$OK=false;
			$_SESSION['e_password']="The entered passwords are not identical!";
		}					
		
		//Zapamiętaj wprowadzone dane
		$_SESSION['rf_name'] = $name;
		$_SESSION['rf_surname'] = $surname;
		$_SESSION['rf_login'] = $login;
		$_SESSION['rf_email'] = $email;
		$_SESSION['rf_password1'] = $password1;
		$_SESSION['rf_password2'] = $password2;
		
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try 
		{
			$connection = new mysqli($host, $db_user, $db_password, $db_name);
			if ($connection->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				//Czy email już istnieje?
				$result = $connection->query("SELECT id FROM Users WHERE Email='$email'");
				
				if (!$result) throw new Exception($connection->error);
				
				$nr_of_emails = $result->num_rows;
				if($nr_of_emails>0)
				{
					$OK=false;
					$_SESSION['e_email']="There is already an account assigned to this email address!";
				}		

				//Czy nick jest już zarezerwowany?
				$result = $connection->query("SELECT id FROM Users WHERE login='$login'");
				
				if (!$result) throw new Exception($connection->error);
				
				$nr_of_logins = $result->num_rows;
				if($nr_of_logins>0)
				{
					$OK=false;
					$_SESSION['e_login']="Login is already taken!";
				}
				
				if ($OK==true)
				{
					//Hurra, wszystkie testy zaliczone, dodajemy gracza do bazy
					
					if ($connection->query("INSERT INTO Users VALUES (NULL, '$name', '$surname', '$login', '$password1', 1, 0, '','$email', 0, 0)"))
					{
						$_SESSION['successful_registration']=true;
						header('Location: welcome.php');
					}
					else
					{
						throw new Exception($connection->error);
					}
					
				}
				
				$connection->close();
			}
			
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Server error</span>';
			echo '<br />Information for developers: '.$e;
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

		<title>Register</title>
	</head>
	<body>
		<div class="container">
			<div class="row main">
					<div class="panel-heading">
		               <div class="panel-title text-center">
		               		<h1 class="title">Register</h1>
		               		<hr />
		               	</div>
		            </div>
				<div class="main-login main-center">
					<form class="form-horizontal" method="post">

							<div class="form-group">
								<label class="cols-sm-2 control-label">Name</label>
								<div class="cols-sm-10">
									<div class="input-group">
										<input type="text" class="form-control" name="name" placeholder="Enter your name" value=""
										<?php
											/*if (isset($_SESSION['rf_name']))
											{
												echo $_SESSION['rf_name'];
												unset($_SESSION['rf_name']);
											}*/
										?> />
									</div>
									<?php
										if (isset($_SESSION['e_name']))
										{
											echo '<div class="cols-sm-2 control-label error"> '.$_SESSION['e_name'].'</div>';
											unset($_SESSION['e_name']);
										}
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="cols-sm-2 control-label">Surname</label>
								<div class="cols-sm-10">
									<div class="input-group">
										<input type="text" class="form-control" name="surname" placeholder="Enter your surname"/>
									</div>
									<?php
										if (isset($_SESSION['e_surname']))
										{
											echo '<div class="cols-sm-2 control-label error"> '.$_SESSION['e_surname'].'</div>';
											unset($_SESSION['e_surname']);
										}
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="cols-sm-2 control-label">Login</label>
								<div class="cols-sm-10">
									<div class="input-group">
										<input type="text" class="form-control" name="login" placeholder="Enter your login"/>
									</div>
									<?php
										if (isset($_SESSION['e_login']))
										{
											echo '<div class="cols-sm-2 control-label error"> '.$_SESSION['e_login'].'</div>';
											unset($_SESSION['e_login']);
										}
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="cols-sm-2 control-label">E-mail</label>
								<div class="cols-sm-10">
									<div class="input-group">
										<input type="text" class="form-control" name="email" placeholder="Enter your E-mail"/>
									</div>
									<?php
										if (isset($_SESSION['e_email']))
										{
											echo '<div class="cols-sm-2 control-label error"> '.$_SESSION['e_email'].'</div>';
											unset($_SESSION['e_email']);
										}
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="cols-sm-2 control-label">Password</label>
								<div class="cols-sm-10">
									<div class="input-group">
										<input type="password" class="form-control" name="password1" placeholder="Enter your password"/>
									</div>
									<?php
										if (isset($_SESSION['e_password']))
										{
											echo '<div class="cols-sm-2 control-label error"> '.$_SESSION['e_password'].'</div>';
											unset($_SESSION['e_password']);
										}
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="cols-sm-2 control-label">Confirm Password</label>
								<div class="cols-sm-10">
									<div class="input-group">
										<input type="password" class="form-control" name="password2" placeholder="Repeat your password"/>
									</div>
								</div>
							</div>

							<div class="form-group ">
								<button type="submit button" class="btn btn-primary btn-lg btn-block login-button">Register</button>
							</div>
					</form>
						<div class="login-register">
							<a href="index.php">Login</a>
						</div>
				</div>
			</div>
		</div>

		<!--<script type="text/javascript" src="bootstrap.js"></script>-->
	</body>
</html>