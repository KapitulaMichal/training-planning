<?php

	session_start();
	if (!isset($_SESSION['signedin']))
	{
		header('Location: index.php');
		exit();
	}

$id_user = $_GET['id_user'];
$id_trainer = $_SESSION['id'];

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
		$result = $connection->query("SELECT * FROM Users WHERE ID = '$id_user'");

		$row = $result->fetch_assoc();
		$name = $row['Name'];
		$surname = $row['Surname'];


		$connection->close();
	}
}
catch(Exception $e)
{
	echo '<span style="color:red;">Server error</span>';
	echo '<br />Information for developers: '.$e;
}

$nr = $_GET['nr'];
$id_session = $_GET['id_session'];
$_SESSION['id_session'] = $id_session;
$_SESSION['nr'] = $nr;

if(isset($_POST['repetition']))
{
	$OK=true;

	if(isset($_POST['repetition_ch']) && $_POST['repetition_ch'] = "repetition")
	{
		$repetition = $_POST['repetition'];
	}
	else
	{
		$repetition = "NULL";
	}

	if(isset($_POST['time_ch']) && $_POST['time_ch'] = "time")
	{
		$time = $_POST['time'];
	}
	else
	{
		$time = "NULL";
	}

	if(isset($_POST['load_ch']) && $_POST['load_ch'] = "load")
	{
		$load = $_POST['load'];
	}
	else
	{
		$load = "NULL";
	}

	if(isset($_POST['exercise']))
	{
		$exercise = $_POST['exercise'];
	}
	else
	{
		$OK = false;
		$exercise_er = true;
	}

	if($OK==true)
	{	
		$query = "INSERT INTO Series VALUES (NULL,". $exercise .",". $repetition. ",".$time.",".$load.")";
		
		try
		{
			$connection = new mysqli($host, $db_user, $db_password, $db_name);
			if ($connection->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				$connection->query($query);
	
				$result = $connection->query("SELECT ID FROM Series ORDER BY ID");
	
				while ($row = $result -> fetch_assoc())
				{
					$id = $row['ID'];
				}

	
				$connection->query("INSERT INTO Training_session_Series VALUES (NULL, '$id_session','$id')");
				
				
			}				
				$connection -> close();
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Server error</span>';
			echo '<br />Information for developers: '.$e;
		}
	}
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  	<title>Edit training</title>
  	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
 	<link rel="stylesheet" type="text/css" href="main.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

 <?php include 'topbar.php';?>

<div class="panel-heading">
    <div class="panel-title text-center">
    	<h1 class="title">Edit training</h1>
    	<hr/>
    	<?php
      	echo "<h4 class=\"title\" style = \"color:black;\"><strong>User: </strong>".$name." ".$surname. "</h4>";
      ?>
    </div>
</div>
		

<div class="container border">
	<a <?php echo "href=\"user_trainings.php?id_user=".$id_user."\"" ?> id="back-button"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Back</a>

<table class="table table-striped table-bordered table-responsive" style="margin-bottom: 0; margin-top: 2px;">
  <thead>
    <tr>
      <th style="vertical-align: middle;">#</th>
      <th style="vertical-align: middle;">Name</th>
      <th style="vertical-align: middle;">Date</th>
      <th style="vertical-align: middle;">Training type</th>
      <th style="vertical-align: middle;">Trainer</th>
      <th style="vertical-align: middle;">Location</th>
      <th style="vertical-align: middle;">Description</th>
      <th style="vertical-align: middle; width: 120px;"></th>
    </tr>
  </thead>
  <tbody>

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
		$result = $connection->query("SELECT s.ID, s.Name, s.Training_date, t.Name AS Type, u.Name AS trainer_name, u.Surname AS trainer_surname, s.Location, s.Description FROM Training_session s LEFT OUTER JOIN Training_types t ON s.ID_Training_type = t.ID LEFT OUTER JOIN Users u on s.ID_Trainer = u.ID WHERE s.ID = '$id_session'");

		$i=1;
		while ($row = $result -> fetch_assoc()) 
		{
			$id = $row['ID'];
			$name = $row['Name']; 
			$date = $row['Training_date'];
			$type = $row['Type'];
			$trainer_name = $row['trainer_name'];
			$trainer_surname = $row['trainer_surname'];
			$location = $row['Location'];
			$description = $row['Description'];

			$day = substr($date, 8, 2);
			if(substr($day, 0,1) == 0)
			{
				$day = substr($day, 1,1);
			}

			$month = substr($date, 5, 2);
			$year = substr($date, 0, 4);

			echo "<tr style = \"vertical-align: middle;\">
			      <th style=\"vertical-align:middle;\" scope=\"row\">".$nr."</th>
			      <td style=\"vertical-align:middle;\"><strong>".$name."</strong></td>
			      <td style=\"vertical-align:middle;\">".$day."-".$month."-".$year."</td>
			      <td style=\"vertical-align:middle;\">".$type."</td>
			      <td style=\"vertical-align:middle;\">".$trainer_name. " " .$trainer_surname."</td>
			      <td style=\"vertical-align:middle;\">".$location."</td>
			      <td style=\"vertical-align:middle;\">".$description."</td>
			      <td style=\"vertical-align:middle;\"> <a href=\"edit_training_data.php?nr=".$nr."&id_session=".$id_session."&id_user=".$id_user."\" class = \"btn btn-primary\">Edit training</a> </td>";
			    
			$i++;
		}

		$result->free();

		$connection->close();
	}
}
catch(Exception $e)
{
	echo '<span style="color:red;">Server error</span>';
	echo '<br />Information for developers: '.$e;
}
?>
</tbody>
</table>


<div class="row justify-content-md-center" id="edit-field">
<div class="col-sm-1"></div>
	<div class="col-sm-4 " align="center">

		<div class="panel-heading">
			<div class="panel-title text-center">
			    <h3 style="margin-top: 0">Add exercise</h3>
			</div>
		</div>

		<form class="form-horizontal justify-content-md-center" method="post" action="">
			<select name="exercise" class="form-control" id="select-exercise" >
				<option disabled selected value> Select exercise</option>
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
							$result = $connection->query("SELECT ID, Name, Permanent, ID_User FROM Exercises ORDER BY Name");
						}

						$i=1;
						while ($row = $result -> fetch_assoc())
						{
							$id_exercise = $row['ID'];
							$name = $row['Name'];
							$permanent = $row['Permanent'];
							$id_user_exercise = $row['ID_User'];

							if($permanent == 1 || $id_user_exercise == $id_user || $id_user_exercise == $id_trainer)
								echo "<option value=\"".$id_exercise."\">". $name ."</option>";

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
			</select>
			<?php
			if(isset($exercise_er) && $exercise_er = true)
			{
				echo '<div class="error"> Choose the exercise!</div>';
				unset($exercise_er);
			}
			?>
			<br/>
			<div class="form-group">
				<div class= "column_center">
					<input type="checkbox" name="repetition_ch" value="repetition" id="repetition">
					<label for="repetition">Repetition</label><br>

					<input type="checkbox" name="time_ch" value="time" id="time"> 
					<label for="time">Time[min]</label><br>
					<input type="checkbox" name="load_ch" value="load" id="load">
					<label for="load">Weight[kg]</label>
				</div>

				<div class="column_center">
					<input size = "100" class="exercise-details" id="1" type="number" name="repetition" min = "1" max="500" /><br>
					<input type="number" class="exercise-details" step="any" min="0" max = "360"  name="time"/><br>
					<input type="number" class="exercise-details" name="load" step="any" value = "load" min="0" max = "300" /><br>
				</div>

				<div style="text-align: center;">
					<button style="margin-top: 5px; text-align: center;margin-left: auto;margin-right: auto;" type="submit button" name="submitbutton" class="btn btn-primary btn-lg ">Add</button>
				</div>
			</div>
		</form>

	</div>

	<div class="col-sm-6" align="center">

		<ul class="list-group">

		<?php

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
					$result = $connection->query("SELECT s.ID, e.Name, s.Repetition, s.Duration, s.Series_load FROM Series s INNER JOIN Exercises e ON s.ID_Exercise = e.ID INNER JOIN Training_session_Series tss ON s.ID = tss.ID_Series INNER JOIN Training_session ts ON ts.ID = tss.ID_Training_session WHERE ts.ID = '$id_session'");
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
					echo "<li class=\"list-group-item li-training justify-content-between\" style = \"text-align:left;\"> <a style=\"margin-left:4px;\" href = \"delete_exercise_from_training.php?id_session=".$id_session."&id_series=".$id_series."&nr=".$nr."\" class=\"close\"> &times;</a>".$name;

	   						if($load != NULL)
	   						{
	   						 	echo "<span class=\"badge badge-default badge-pill badge-edit\">".$load."kg</span>";
	   						}
	   						
	   						if($duration != NULL)
	   						{
								echo "<span class=\"badge badge-default badge-pill badge-edit\">".$duration."min</span>";
							}

							if($repetition != NULL)
							{
								echo "<span class=\"badge badge-default badge-pill badge-edit\">x".$repetition."</span>";
							}

							echo "</li>";
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
	<div class="col-sm-1"></div>
	<!-- <div class="col-sm-1"></div> -->

</div>
</div>


</body>
</html>