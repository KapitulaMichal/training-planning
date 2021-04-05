<?php
session_start();
	
if ((!isset($_POST['login'])) || (!isset($_POST['password'])))
{
	header('Location: index.php');
	exit();
}

require_once "connect.php";

$connection = @new mysqli($host, $db_user, $db_password, $db_name);

if ($connection->connect_errno!=0)
	{
		echo "Error: ".$connection->connect_errno;
	}
	else
	{
		$login = $_POST['login'];
		$password = $_POST['password'];

		if ($result = @$connection->query(
		sprintf("SELECT * FROM Users WHERE Login='%s' AND password='%s'",
		mysqli_real_escape_string($connection,$login),
		mysqli_real_escape_string($connection,$password))))
		{
			$how_many_users = $result->num_rows;

			if($how_many_users > 0)
			{
				$_SESSION['signedin'] = true;

				$row = $result->fetch_assoc();
				$_SESSION['id'] = $row['ID'];
				$_SESSION['name'] = $row['Name'];
				$_SESSION['surname'] = $row['Surname'];
				$_SESSION['login'] = $row['Login'];
				$_SESSION['password'] = $row['Password'];
				$_SESSION['user_type'] = $row['User_type'];
				$_SESSION['gender'] = $row['Gender'];
				$_SESSION['phone_number'] = $row['Phone_number'];
				$_SESSION['email'] = $row['Email'];
				$_SESSION['height'] = $row['Height'];
				$_SESSION['weight'] = $row['Weight'];

				unset($_SESSION['blad']);
				$result->free_result();
				if($_SESSION['user_type'] == 3)
				{
					header('Location: users.php');
				}
				else
				{
					header('Location: training.php');
				}
				
			}
			else
			{
				$_SESSION['error'] = '<span style="color:red">Incorrect login or password!</span>';
				header('Location: index.php');
			}
		}
	$connection->close();
	}
	
	
?>