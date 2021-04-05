<?php

session_start();
	if (!isset($_SESSION['signedin']))
	{
		header('Location: index.php');
		exit();
	}

$id_session = $_GET['id_session'];

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
		$result = $connection->query("SELECT * FROM Training_session WHERE ID = '$id_session'");

		$row = $result->fetch_assoc();
		$name = $row['Name'];
		$date = $row['Training_date'];
		$type = $row['ID_Training_type'];

		if($row['ID_Trainer'] == NULL)
		{
			$trainer = "NULL";
		}
		else
		{
			$trainer = $row['ID_Trainer'];
		}

		if($row['Location'] == '')
		{
			$location = "NULL";			
		}
		else
		{
			$location = $row['Location'];	
		}
		
		if($row['Description'] == '')
		{
			$description = "NULL";
		}
		else
		{
			$description = $row['Description'];
		}

		if(isset($_GET['id_user']))
		{
			$id_user = $_GET['id_user'];
		}
		else
		{
			$id_user = $_SESSION['id'];
		}
		
		$query = "INSERT INTO Training_session VALUES (NULL, '".$name."',".$id_user.",'".$date."',".$type.",".$trainer.",'".$location."', '".$description."' )";

		$connection -> query($query);

		$result = $connection->query("SELECT ID FROM Training_session ORDER BY ID");

		while ($row = $result -> fetch_assoc())
		{
			$id_session_new = $row['ID'];
		}

		
		$result = $connection->query("SELECT ID_Series FROM Training_session_Series WHERE ID_Training_session = '$id_session' ");

		while($row = $result -> fetch_assoc())
		{
			$id_series = $row['ID_Series'];

			$result2 = $connection->query("SELECT ID, ID_Exercise, Repetition, Duration, Series_load FROM Series WHERE ID = '$id_series'");
			$row2 = $result2 -> fetch_assoc();

			$exercise = $row2['ID_Exercise'];
			
			if($row2['Repetition'] == NULL)
			{
				$repetition = "NULL";
			}
			else
			{
				$repetition = $row2['Repetition'];
			}

			if($row2['Duration'] == NULL)
			{
				$duration = "NULL";
			}
			else
			{
				$duration = $row2['Duration'];
			}
			
			if($row2['Series_load'] == NULL)
			{
				$load = "NULL";
			}
			else
			{
				$load = $row2['Series_load'];
			}
			
			$query = "INSERT INTO Series VALUES (NULL,".$exercise.",".$repetition.",".$duration.",".$load.")";			
			$result2->free();

			$connection -> query($query);

			$result3 = $connection->query("SELECT ID FROM Series ORDER BY ID");

			while ($row3 = $result3 -> fetch_assoc())
			{
				$id_series_new = $row3['ID'];
			}

			$result3->free();

			$connection -> query("INSERT INTO Training_session_Series VALUES (NULL, '$id_session_new', '$id_series_new')");
		}

		$result->free();
		
		$connection -> close();
	}				
		
}
catch(Exception $e)
{
	echo '<span style="color:red;">Server error</span>';
	echo '<br />Information for developers: '.$e;
}

if(isset($_GET['id_user']))
{
	header('Location: user_trainings.php?id_user='.$id_user);
}
else
{
	header('Location: training.php');
}

?>