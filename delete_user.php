<?php

session_start();
if (!isset($_SESSION['signedin']))
{
	header('Location: index.php');
	exit();
}

$id_user = $_GET['id_user'];

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
		//trainer
		$result = $connection->query("SELECT User_type FROM Users WHERE ID = '$id_user'");
		
		$row = $result->fetch_assoc();

		$user_type = $row['User_type'];

		if($user_type == 2)
		{
			$result2 = $connection->query("SELECT ID FROM Training_session WHERE ID_Trainer = '$id_user'");

			while($row2 = $result2->fetch_assoc())
			{
				$id_session = $row2['ID'];
				$connection->query("UPDATE Training_session SET ID_Trainer = NULL WHERE ID = '$id_session'");
			}

		}
		
		//trainings
		$result = $connection->query("SELECT ID FROM Training_session WHERE ID_User = '$id_user'");

		while($row = $result->fetch_assoc())
		{
			$id_session = $row['ID'];

			$result2 = $connection->query("SELECT tss.ID, tss.ID_Series FROM Training_session_Series tss INNER JOIN Training_session ts ON tss.ID_Training_session = ts.ID WHERE ts.ID = '$id_session'");

			while($row2 = $result2->fetch_assoc())
			{
				$id_session_series = $row2['ID'];
				$id_series = $row2['ID_Series'];

				$connection->query("DELETE FROM Training_session_Series WHERE ID = '$id_session_series'");

				$connection->query("DELETE FROM Series WHERE ID = '$id_series'");
			}

			$connection->query("DELETE FROM Training_session WHERE ID = '$id_session'");
		}

		//exercises
		$result = $connection->query("SELECT ID FROM Exercises WHERE ID_User = '$id_user'");

		while($row = $result->fetch_assoc())
		{
			$id_exercise = $row['ID'];

			$connection->query("DELETE FROM Exercises_Body_parts WHERE ID_Exercises = '$id_exercise'");
		
			$connection->query("DELETE FROM Exercises WHERE ID = '$id_exercise' ");
		}
		
		//user
		if($connection->query("DELETE FROM Users WHERE ID = '$id_user'"))
		{
			$_SESSION['user_deleted'] = true;
		}


		$result->free();
		if(isset($result2))
			$result2->free();
		$connection ->close();

	}
}
catch(Exception $e)
{
	echo '<span style="color:red;">Server error</span>';
	echo '<br />Information for developers: '.$e;
}

header('Location: users.php');


?>