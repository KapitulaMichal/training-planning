<?php

	session_start();
	if (!isset($_SESSION['signedin']))
	{
		header('Location: index.php');
		exit();
	}
	
require_once "connect.php";
mysqli_report(MYSQLI_REPORT_STRICT);

$id_session = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  	<title>Active training</title>
  	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
 	<link rel="stylesheet" type="text/css" href="main.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<style>
		.pending
		{
			display: block;
		}

		.done
		{
			display: none;
		}
	</style>

</head>

<body>

 <?php include 'topbar.php';?>
<div class="panel-heading" style="padding-bottom: 0px;">
    <div class="panel-title text-center">
    	<h1 class="title">Active training </h1>
    	<hr/>
    </div>
</div>
<div class="container" align="center">
	<div class="start-training-field" style="text-align: left;">

<a href="training.php" id="back-button"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Back</a>

<?php
try 
{
	$connection = new mysqli($host, $db_user, $db_password, $db_name);
	if ($connection->connect_errno!=0)
	{
		throw new Exception(mysqli_connect_errno());
	}
	else
	{
		$result = $connection->query("SELECT Name FROM Training_session WHERE ID = '$id_session'");
		$row = $result -> fetch_assoc();
		$name = $row['Name'];

		echo "<div style=\"font-size: 25px;\" align=\"center\"><strong>".$name."</strong></div>";

		$result -> free();
		$connection -> close();

	}
	
}
catch(Exception $e)
{
	echo '<span style="color:red;">Server error</span>';
	echo '<br />Information for developers: '.$e;
}
?>



 	<ul class="list-group">

		<?php



			
			$are_exercises_present = false;

			try 
			{
				$connection = new mysqli($host, $db_user, $db_password, $db_name);
				if ($connection->connect_errno!=0)
				{
					throw new Exception(mysqli_connect_errno());
				}
				else
				{
					$result = $connection->query("SELECT s.ID, e.Name, s.Repetition, s.Duration, s.Series_load FROM Series s INNER JOIN Exercises e ON s.ID_Exercise = e.ID INNER JOIN Training_session_Series tss ON s.ID = tss.ID_Series INNER JOIN Training_session ts ON ts.ID = tss.ID_Training_session WHERE ts.ID = '$id_session'");
				}

				$i=1;
				while ($row = $result -> fetch_assoc())
				{
					$are_exercises_present = true;
					$id_series = $row['ID'];
					$name = $row['Name'];
					$repetition = $row['Repetition'];
					$duration = $row['Duration'];
					$load = $row['Series_load'];

					//echo "<div class = \"jumbotron\">".$name." x".$repetition."</div>";
					echo "<li class=\"list-group-item align-middle text-left li-training pending justify-content-between\" vertical-align = \"middle\">".
					"<button class=\"chk_pending btn btn-primary btn-md\"  align = \"\" onclick = \"showHide(".$i.",1)\"><i class=\"glyphicon glyphicon-ok\"></i></button>  ".$name;

	   						if($load != NULL)
	   						{
	   						 	echo "<span class=\"badge badge-default badge-pill badge-start\">".$load."kg</span>";
	   						}
	   						
	   						if($duration != NULL)
	   						{
								echo "<span class=\"badge badge-default badge-pill badge-start\">".$duration."min</span>";
							}

							if($repetition != NULL)
							{
								echo "<span class=\"badge badge-default badge-pill badge-start\">x".$repetition."</span>";
							}

							echo "</li>";
							$i++;
				}
					$result -> free();
					$connection -> close();
			}
			catch(Exception $e)
			{
				echo '<span style="color:red;">Server error</span>';
				echo '<br />Information for developers: '.$e;
			}
		?>
	</ul>
	<hr/>
	<ul class="list-group">

		<?php

			require_once "connect.php";
			mysqli_report(MYSQLI_REPORT_STRICT);

			$id_session = $_GET['id'];

			try 
			{
				$connection = new mysqli($host, $db_user, $db_password, $db_name);
				if ($connection->connect_errno!=0)
				{
					throw new Exception(mysqli_connect_errno());
				}
				else
				{
					$result = $connection->query("SELECT s.ID, e.Name, s.Repetition, s.Duration, s.Series_load FROM Series s INNER JOIN Exercises e ON s.ID_Exercise = e.ID INNER JOIN Training_session_Series tss ON s.ID = tss.ID_Series INNER JOIN Training_session ts ON ts.ID = tss.ID_Training_session WHERE ts.ID = '$id_session'");
				}

				if($are_exercises_present = false)
				{
					echo "<h4>Training is empty</h4>";
				}

				$i=1;
				while ($row = $result -> fetch_assoc())
				{
					$id_series = $row['ID'];
					$name = $row['Name'];
					$repetition = $row['Repetition'];
					$duration = $row['Duration'];
					$load = $row['Series_load'];

					//echo "<div class = \"jumbotron\">".$name." x".$repetition."</div>";
					echo "<li class=\"list-group-item text-left li-training done list-group-item-success justify-content-between\" >"."<button class=\"chk_pending btn btn-default btn-md\" align = \"\" onclick = \"showHide(".$i.",2)\"><i class=\"glyphicon glyphicon-ok\"></i></button>  ".$name;

	   						if($load != NULL)
	   						{
	   						 	echo "<span class=\"badge badge-default badge-pill badge-start\">".$load."kg</span>";
	   						}
	   						
	   						if($duration != NULL)
	   						{
								echo "<span class=\"badge badge-default badge-pill badge-start\">".$duration."min</span>";
							}

							if($repetition != NULL)
							{
								echo "<span class=\"badge badge-default badge-pill badge-start\">x".$repetition."</span>";
							}

							echo "</li>";

							$i++;
				}
					$result -> free();
					$connection -> close();
			}
			catch(Exception $e)
			{
				echo '<span style="color:red;">Server error</span>';
				echo '<br />Information for developers: '.$e;
			}
		?>
	</ul>
</div>
</div>

<script>
	function showHide(i, type)
	{
		var pending_exercses = document.getElementsByClassName("pending");
		var done_exercises = document.getElementsByClassName("done");

		var chk_pending = document.getElementsByClassName("chk_pending");
		var chk_done = document.getElementsByClassName("chk_done");

		if(type == 1)
		{
			pending_exercses[i-1].style.display = "none";
			done_exercises[i-1].style.display = "block";			
		}
		else if(type == 2)
		{
			pending_exercses[i-1].style.display = "block";
			done_exercises[i-1].style.display = "none";
		}

	}
</script>

</body>
</html>