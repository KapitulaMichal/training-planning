<?php

	session_start();
	if (!isset($_SESSION['signedin']))
	{
		header('Location: index.php');
		exit();
	}

$_SESSION['active_tab'] = "exercises";

$id_user = $_SESSION['id'];


$searched = $_POST['srch-term'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  	<title>Exercises</title>
  	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
 	<link rel="stylesheet" type="text/css" href="main.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script >
function delete_ex(name)
{ 
    if(confirm("Are you sure, that you want to delete this exercise?")==true)
    window.location="delete_exercise.php?name="+name;
    return false;
}
</script>

</head>
<body>

 <?php include 'topbar.php';?>


<div class="panel-heading">
   <div class="panel-title text-center">
      <h1 class="title">Exercises</h1>
      <hr />
    </div>
</div>

<div class = "container"> 
	<?php 
		if(isset($_SESSION['exercise_added']) && $_SESSION['exercise_added'] == true)
	    {
	        echo '<h4>Exercise added</h4>';
	        $_SESSION['exercise_added'] = false;
	    } 

	    if(isset($_SESSION['exercise_deleted']) && $_SESSION['exercise_deleted'] == true)
	    {
	        echo '<h4 style = "color:red;" >Exercise deleted</h4>';
		    $_SESSION['exercise_deleted'] = false;
	    } 

	    if(isset($_SESSION['exercise_changed']) && $_SESSION['exercise_changed'] == true)
	    {
	        echo '<h4>Exercise chagned</h4>';
		    $_SESSION['exercise_changed'] = false;
	    }
    ?>
<div style="margin-bottom: 5px;">
	<a href="exercises.php" id="back-button"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Back</a>
</div>

<div style="margin-bottom: 2px;">
	<span class="glyphicon glyphicon-search" aria-hidden="true"></span><strong> Searched term: </strong> 
	<?php echo $searched ?>
</div>


<table class="table table-striped table-bordered table-responsive" >
  <thead>
    <tr>
      <th style="vertical-align: middle;">#</th>
      <th style="vertical-align: middle;">Name</th>
      <th style="vertical-align: middle;">Type</th>
      <th style="vertical-align: middle;">Calories burning rate [cal/h]</th>
      <th style="vertical-align: middle;">Equipment</th>
      <th style="vertical-align: middle;">Trained body parts</th>
      <th class="buttons"></th>
    </tr>
  </thead>
  <tbody>

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
		$result = $connection->query("SELECT Exercises.ID, Exercises.Name, Exercise_types.Name as Type, Exercises.Calories_burning_rate, Exercises.Equipment, Exercises.Permanent, Exercises.ID_User FROM Exercises LEFT OUTER JOIN Exercise_types ON Exercises.ID_Exercise_type = Exercise_types.ID ORDER BY Exercises.Name");

		$i=1;
		while ($row = $result->fetch_assoc())
		{
			$id = $row['ID'];
			$name = $row['Name'];
			$type = $row['Type'];
			$calories_burning_rate = $row ['Calories_burning_rate'];
			$equipment = $row['Equipment'];
			$permanet = $row['Permanent'];
			$user = $row['ID_User'];

			if($permanet == 0 && $user == $_SESSION['id'])
			{
				if($name == $searched)
				{
					echo "<tr style = \"vertical-align: middle;\">
					      <th style=\"vertical-align:middle;\" scope=\"row\">".$i."</th>
					      <td style=\"vertical-align:middle;\"><strong>".$name."</strong></td>
					      <td style=\"vertical-align:middle;\">".$type."</td>
					      <td style=\"vertical-align:middle;\">".$calories_burning_rate."</td>
					      <td style=\"vertical-align:middle;\">".$equipment."</td>
					      <td style=\"vertical-align:middle;\">";
					      


					$result2 = $connection->query("SELECT Body_parts.Name FROM Body_parts INNER JOIN Exercises_Body_parts ON Body_parts.ID = Exercises_Body_parts.ID_Body_parts INNER JOIN Exercises ON Exercises_Body_parts.ID_Exercises = Exercises.ID WHERE Exercises.ID = '$id'");
					$comma=0;
					while ($row = $result2->fetch_assoc()) 
					{	
						$body_part = $row['Name'];
						
						if($comma == 0)
						{
							$comma = 1;
						}
						else
						{
							echo ", ";
						}
						
						echo $body_part;
					}


					echo "</td>
					<td  style=\"vertical-align:middle;\">";
					if($permanet != 1)
					{
						echo '<a href="exercise_edit.php?id='. $id .'" class="btn btn-info">Edit</a>   <a  class="btn btn-danger " onclick= " delete_ex(\''. $name .'\') " >Delete</a>';
					}

					echo "</td></tr>";
								
				}

				$i++; 
			}
		}
		$result = $connection->query("SELECT Exercises.ID, Exercises.Name, Exercise_types.Name as Type, Exercises.Calories_burning_rate, Exercises.Equipment, Exercises.Permanent, Exercises.ID_User FROM Exercises INNER JOIN Exercise_types ON Exercises.ID_Exercise_type = Exercise_types.ID ORDER BY Exercises.Name");

		while ($row = $result->fetch_assoc())
		{
			$id = $row['ID'];
			$name = $row['Name'];
			$type = $row['Type'];
			$calories_burning_rate = $row ['Calories_burning_rate'];
			$equipment = $row['Equipment'];
			$permanet = $row['Permanent'];
			$user = $row['ID_User'];

			if($permanet == 1 )
			{
				if($name == $searched)
				{
					echo "<tr style = \"vertical-align: middle;\">
					      <th style=\"vertical-align:middle;\" scope=\"row\">".$i."</th>
					      <td style=\"vertical-align:middle;\"><strong>".$name."</strong></td>
					      <td style=\"vertical-align:middle;\">".$type."</td>
					      <td style=\"vertical-align:middle;\">".$calories_burning_rate."</td>
					      <td style=\"vertical-align:middle;\">".$equipment."</td>
					      <td style=\"vertical-align:middle;\">";
					      


					$result2 = $connection->query("SELECT Body_parts.Name FROM Body_parts INNER JOIN Exercises_Body_parts ON Body_parts.ID = Exercises_Body_parts.ID_Body_parts INNER JOIN Exercises ON Exercises_Body_parts.ID_Exercises = Exercises.ID WHERE Exercises.ID = '$id'");
					$comma=0;
					while ($row = $result2->fetch_assoc()) 
					{	
						$body_part = $row['Name'];
						
						if($comma == 0)
						{
							$comma = 1;
						}
						else
						{
							echo ", ";
						}
						
						echo $body_part;
					}


					echo "</td>
					<td>";
					if($permanet != 1)
					{
						echo '<a href="exercise_edit.php?name='. $name .'" class="btn btn-info">Edit</a>   <a  class="btn btn-danger" onclick= " delete_ex(\''. $name .'\') " >Delete</a>';
					}

					echo "</td></tr>";
				}

				$i++; 
			}
			

		}

		echo "</tbody>
			</table>";
		
		$result->free();
		
		if(isset($result2))
		{
			$result2->free();
		}

		$connection->close();
	}
	
}
catch(Exception $e)
{
	echo '<span style="color:red;">Server error</span>';
	echo '<br />Information for developers: '.$e;
}

?>

</div><!--container-->


</body>
</html>