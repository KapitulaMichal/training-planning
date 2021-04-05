<?php

	session_start();
	if (!isset($_SESSION['signedin']))
	{
		header('Location: index.php');
		exit();
	}

$_SESSION['active_tab'] = "create_training";

$searched = $_POST['srch-term'];

require_once "connect.php";
mysqli_report(MYSQLI_REPORT_STRICT);
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
  	<title>System</title>
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
      <h1 class="title">Users</h1>
      <hr/>
    </div>
</div>

<div class="container">

<div style="margin-bottom: 5px;">
	<a href="create_training.php" id="back-button"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Back</a>
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
	      <th style="vertical-align: middle;">Surname</th>
	      <th style="vertical-align: middle;">Phone number</th>
	      <th style="vertical-align: middle;">Email</th>
 	      <th></th>
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
		$result = $connection->query("SELECT ID, Name, Surname, Phone_number, Email, User_type FROM Users ORDER BY ID");

		$i=1;
		while ($row = $result -> fetch_assoc()) 
		{
			$id = $row['ID'];
			$user_type = $row['User_type'];

			if($id != $_SESSION['id'] && $user_type !=3)
			{
				$name = $row['Name']; 
				$surname = $row['Surname'];
				$phone_number = $row['Phone_number'];
				$email = $row['Email'];

				if($name == $searched || $surname == $searched || $phone_number == $searched || $email == $searched)
				{
					echo "<tr style = \"vertical-align: middle;\">
					      <th style=\"vertical-align:middle;\" scope=\"row\">".$i."</th>
					      <td style=\"vertical-align:middle;\"><strong>".$name."</strong></td>
					      <td style=\"vertical-align:middle;\">".$surname."</td>
					      <td style=\"vertical-align:middle;\">".$phone_number."</td>
					      <td style=\"vertical-align:middle;\">".$email."</td>
					      <td style=\"vertical-align:middle;\"><a href=\"user_trainings.php?id_user=".$id."\" class=\"btn btn-primary\">Trainings</a></td>";
				}
				$i++;
			}
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