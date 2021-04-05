<?php

session_start();
if (!isset($_SESSION['signedin']))
{
	header('Location: index.php');
	exit();
}


$name = $_POST['name'];

if(isset($_POST['type']))
{
	$type = $_POST['type'];
}
else
{
	$type = "NULL";
}

$calories_burning_rate = $_POST['calories_burning_rate'];

if (empty($calories_burning_rate)) 
{
	$calories_burning_rate = "NULL";
}

$equipment = $_POST['equipment'];


$query = "INSERT INTO Exercises VALUES (NULL,'" . $name . "'," . $type . "," . $calories_burning_rate . ",'" .$equipment. "',1, NULL)";

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
		if ($connection->query($query))
		{
			$_SESSION['exercise_added']=true;
		}
		else
		{
			throw new Exception($connection->error);
		}

		$result = $connection->query("SELECT ID FROM Exercises ORDER BY ID");

		while($row = $result->fetch_assoc())
		{
			$id = $row['ID'];
		}
		
		echo $id."<br/>";

		if(isset($_POST['arms']) && $_POST['arms'] == 1)
		{
			$arms = $_POST['arms'];

			if ($connection->query("INSERT INTO Exercises_Body_parts VALUES (NULL, '$id', '$arms')"))
			{

			}
			else
			{
				throw new Exception($connection->error);
			}
		}

		if(isset($_POST['back']) && $_POST['back'] == 2)
		{
			$back = $_POST['back'];
			
			if ($connection->query("INSERT INTO Exercises_Body_parts VALUES (NULL, '$id', '$back')"))
			{

			}
			else
			{
				throw new Exception($connection->error);
			}
		}

		if(isset($_POST['chest']) && $_POST['chest'] == 3)
		{
			$chest = $_POST['chest'];
			
			if ($connection->query("INSERT INTO Exercises_Body_parts VALUES (NULL, '$id', '$chest')"))
			{

			}
			else
			{
				throw new Exception($connection->error);
			}
		}

		if(isset($_POST['core']) && $_POST['core'] == 4)
		{
			$core = $_POST['core'];
			
			if ($connection->query("INSERT INTO Exercises_Body_parts VALUES (NULL, '$id', '$core')"))
			{

			}
			else
			{
				throw new Exception($connection->error);
			}
		}

		if(isset($_POST['legs']) && $_POST['legs'] == 5)
		{
			$legs = $_POST['legs'];
			
			if ($connection->query("INSERT INTO Exercises_Body_parts VALUES (NULL, '$id', '$legs')"))
			{

			}
			else
			{
				throw new Exception($connection->error);
			}
		}

		if(isset($_POST['shoulders']) && $_POST['shoulders'] == 6)
		{
			$shoulders = $_POST['shoulders'];
			
			if ($connection->query("INSERT INTO Exercises_Body_parts VALUES (NULL, '$id', '$shoulders')"))
			{

			}
			else
			{
				throw new Exception($connection->error);
			}
		}

		if(isset($_POST['abs']) && $_POST['abs'] == 7)
		{
			$abs = $_POST['abs'];
			
			if ($connection->query("INSERT INTO Exercises_Body_parts VALUES (NULL, '$id', '$abs')"))
			{

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

header('Location: exercises_admin.php');

?>