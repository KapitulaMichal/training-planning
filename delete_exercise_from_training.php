<?php

	session_start();
	if (!isset($_SESSION['signedin']))
	{
		header('Location: index.php');
		exit();
	}

$id_session = $_GET['id_session'];
$id_series = $_GET['id_series'];
$nr = $_GET['nr'];

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
		$connection->query("DELETE FROM Training_session_Series WHERE ID_Series = '$id_series'");
	
		$connection->query("DELETE FROM Series WHERE ID = '$id_series'");
				
		$connection->close();

		header('Location: edit_training.php?id_session='.$id_session.'&nr='.$nr);
	}
		
}
catch(Exception $e)
{
	echo '<span style="color:red;">Server error</span>';
	echo '<br />Information for developers: '.$e;
}

	
?>