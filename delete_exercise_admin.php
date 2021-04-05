<?php

session_start();
if (!isset($_SESSION['signedin']))
{
	header('Location: index.php');
	exit();
}

$id_exercise = $_GET['id'];

/*$query = "SELECT * FROM Exercises WHERE Name = '".$name."' AND ID = ".$id_user;
echo $query;
die();*/

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
		$connection->query("DELETE FROM Exercises_Body_parts WHERE ID_Exercises = '$id_exercise'");
		

		$result = $connection->query("SELECT ID FROM Series WHERE ID_Exercise = '$id_exercise' ");

		while($row = $result->fetch_assoc())
		{
			$id = $row['ID'];

			$connection->query("DELETE FROM Training_Session_series WHERE ID_Series = '$id'");
		}

		$connection->query("DELETE FROM Series WHERE ID_Exercise = '$id_exercise'");

		if ($connection->query("DELETE FROM Exercises WHERE ID = '$id_exercise' "))
		{
			$_SESSION['exercise_deleted'] = true;
		}
		
		$connection->close();
	}
		
}
catch(Exception $e)
{
	echo '<span style="color:red;">Server error</span>';
	echo '<br />Information for developers: '.$e;
}

header('Location: exercises_admin.php');

?>