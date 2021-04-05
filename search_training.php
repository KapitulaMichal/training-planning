<?php

	session_start();
	if (!isset($_SESSION['signedin']))
	{
		header('Location: index.php');
		exit();
	}

$_SESSION['active_tab'] = "training";
$elements_on_page = 10;


require_once "connect.php";
mysqli_report(MYSQLI_REPORT_STRICT);

$searched = $_POST['srch-term'];

	
?>
<!DOCTYPE html>
<html lang="en">
<head>
  	<title>Trainings</title>
  	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
 	<link rel="stylesheet" type="text/css" href="main.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>
function delete_training(id)
{ 
    if(confirm("Are you sure, that you want to delete this training session?")==true)
    window.location="delete_training.php?id="+id;
    return false;
}
</script>

</head>
<body>

 <?php include 'topbar.php';?>

<div class="panel-heading" style="padding-bottom: 0;">
   <div class="panel-title text-center">
      <h1 class="title">Trainings </h1>
      <hr/>
    </div>
</div>

<div class="container">

	<?php
	if(isset($_SESSION['training_added']) && $_SESSION['training_added'] == true)
	    {
	        echo '<h4>Training session added</h4>';
	        $_SESSION['training_added'] = false;
	    }

	if(isset($_SESSION['training_deleted']) && $_SESSION['training_deleted'] == true)
	    {
	        echo '<h4 style = "color:red;">Training session deleted</h4>';
		    $_SESSION['training_deleted'] = false;
	    }
	?>
<div style="margin-bottom: 5px;">
	<a href="training.php" id="back-button"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Back</a>
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
      <th style="vertical-align: middle;">Date</th>
      <th style="vertical-align: middle;">Training type</th>
      <th style="vertical-align: middle;">Trainer</th>
      <th style="vertical-align: middle;">Location</th>
      <th style="vertical-align: middle;">Description</th>
      <th class="buttons"></th>
    </tr>
  </thead>
  <tbody>

<?php 

$id_user = $_SESSION['id'];

try 
{
	$connection = new mysqli($host, $db_user, $db_password, $db_name);
	if ($connection->connect_errno!=0)
	{
		throw new Exception(mysqli_connect_errno());
	}
	else
	{
		$result = $connection->query("SELECT s.ID, s.Name, s.Training_date, t.Name AS Type, u.Name AS trainer_name, u.Surname AS trainer_surname, s.Location, s.Description FROM Training_session s LEFT OUTER JOIN Training_types t ON s.ID_Training_type = t.ID LEFT OUTER JOIN Users u on s.ID_Trainer = u.ID WHERE s.ID_User = '$id_user' ORDER BY Training_date");

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

			if($name == $searched)
			{
				
				echo "<tr style = \"vertical-align: middle;\">
				      <th style=\"vertical-align:middle;\" scope=\"row\">".$i."</th>
				      <td style=\"vertical-align:middle;\"><strong>".$name."</strong></td>
				      <td style=\"vertical-align:middle;\">".$day."-".$month."-".$year."</td>
				      <td style=\"vertical-align:middle;\">".$type."</td>
				      <td style=\"vertical-align:middle;\">".$trainer_name. " " .$trainer_surname."</td>
				      <td style=\"vertical-align:middle;\">".$location."</td>
				      <td style=\"vertical-align:middle;\">".$description."</td>
				      <td style=\"vertical-align:middle;\">

				      <a href=\"start_training.php?id=".$id."\" class=\"btn btn-success button-training\">START</a>
				      <a href=\"copy_training.php?id_session=".$id."\" class=\"btn btn-warning button-training\" >Copy</a><br/>
				      <a href=\"edit_training.php?id_session=".$id."&nr=".$i."\" style=\"margin-top:5px;\" class=\"btn btn-info button-training\">Edit</a> 
				      <a class=\"btn btn-danger button-training\" style=\"margin-top:5px;\" onclick = \"delete_training(".$id.")\" >Delete</a></td></tr>"; 
				
			

			}
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


</div>




</body>
</html>