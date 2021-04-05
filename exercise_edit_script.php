<?php

	session_start();
	if (!isset($_SESSION['signedin']))
	{
		header('Location: index.php');
		exit();
	}

$id_exercise = $_GET['id'];

if(isset($_POST['equipment']))
{
	
	$name = $_POST['name'];
	$type = $_POST['type'];
	if($type == 0)
	{
		$type = "NULL";
	}
	$calories_burning_rate = $_POST['calories_burning_rate'];
	if (empty($calories_burning_rate)) 
	{
		$calories_burning_rate = "NULL";
	}
	$equipment = $_POST['equipment'];

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
			$query = "UPDATE Exercises SET Name = '".$name."', ID_Exercise_type = ".$type.", Calories_burning_rate = ".$calories_burning_rate.", Equipment = '".$equipment."' WHERE ID =".$id_exercise;

			if ($connection->query($query))
			{
				$_SESSION['exercise_changed']=true;
			}
			else
			{
				throw new Exception($connection->error);
			}

			$connection->query("DELETE FROM Exercises_Body_parts WHERE ID_Exercises = '$id_exercise'");

			if(isset($_POST['arms']) && $_POST['arms'] == 1)
			{
				$arms = $_POST['arms'];

				if ($connection->query("INSERT INTO Exercises_Body_parts VALUES (NULL, '$id_exercise', '$arms')"))
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
				
				if ($connection->query("INSERT INTO Exercises_Body_parts VALUES (NULL, '$id_exercise', '$back')"))
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
				
				if ($connection->query("INSERT INTO Exercises_Body_parts VALUES (NULL, '$id_exercise', '$chest')"))
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
				
				if ($connection->query("INSERT INTO Exercises_Body_parts VALUES (NULL, '$id_exercise', '$core')"))
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
				
				if ($connection->query("INSERT INTO Exercises_Body_parts VALUES (NULL, '$id_exercise', '$legs')"))
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
				
				if ($connection->query("INSERT INTO Exercises_Body_parts VALUES (NULL, '$id_exercise', '$shoulders')"))
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
				
				if ($connection->query("INSERT INTO Exercises_Body_parts VALUES (NULL, '$id_exercise', '$abs')"))
				{

				}
				else
				{
					throw new Exception($connection->error);
				}
			}

				$arms = false;
				$back = false;
				$chest = false;
				$core = false;
				$legs = false;
				$shoulders =false;
				$abs = false;

				
				$connection->close();
			}
				
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Server error</span>';
			echo '<br />Information for developers: '.$e;
		}

		header('Location: exercises.php');

}


?>