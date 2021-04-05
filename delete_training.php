<?php

	session_start();
	if (!isset($_SESSION['signedin']))
	{
		header('Location: index.php');
		exit();
	}
	
	$id_session = $_GET['id'];

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

			$result = $connection->query("SELECT tss.ID, tss.ID_Series FROM Training_session_Series tss INNER JOIN Training_session ts ON tss.ID_Training_session = ts.ID WHERE ts.ID = '$id_session'");

			while($row = $result->fetch_assoc())
			{
				$id_session_series = $row['ID'];
				$id_series = $row['ID_Series'];

				$connection->query("DELETE FROM Training_session_Series WHERE ID = '$id_session_series'");

				$connection->query("DELETE FROM Series WHERE ID = '$id_series'");

			}

			if($connection->query("DELETE FROM Training_session WHERE ID = '$id_session'"))
			{
				$_SESSION['training_deleted'] = true;
			}
			else
			{
				throw new Exception($connection->error);
			}

			$connection->close();
		}
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Server error</span>';
		echo '<br />Information for developers: '.$e;
	}

	header('Location: training.php');

?>