<?php

	session_start();
	if (!isset($_SESSION['signedin']))
	{
		header('Location: index.php');
		exit();
	}

$_SESSION['active_tab'] = "users";



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
function delete_user(id)
{ 
    if(confirm("Are you sure, that you want to delete this user?")==true)
    window.location="delete_user.php?id_user="+id;
    return false;
}
</script>

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
	<?php
	if(isset($_SESSION['user_added']) && $_SESSION['user_added'] == true)
	    {
	        echo '<h4>User added</h4>';
	        $_SESSION['user_added'] = false;
	    }

	if(isset($_SESSION['user_deleted']) && $_SESSION['user_deleted'] == true)
	    {
	        echo '<h4 style = "color:red;">User deleted</h4>';
		    $_SESSION['user_deleted'] = false;
	    }
	?>
<div style="margin-bottom: 5px;">
	<a href="users.php" id="back-button"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Back</a>
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
      <th style="vertical-align: middle;">User type</th>
      <th style="vertical-align: middle;">Phone Number</th>
      <th style="vertical-align: middle;">Email</th>
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
		$result = $connection->query("SELECT ID, Name, Surname, User_type, Phone_number, Email FROM Users WHERE ID != '$id_user'");

		$i=1;
		while ($row = $result -> fetch_assoc()) 
		{
			$id = $row['ID'];
			$name = $row['Name']; 
			$surname = $row['Surname'];
			$user_type = $row['User_type'];
			$phone_number = $row['Phone_number'];
			$email = $row['Email'];

			if($user_type == 1)
			{
				$user_type_word = 'Standard';
			}
			else if ($user_type == 2)
			{
				$user_type_word = 'Trainer';
			}
			else if ($user_type == 3)
			{
				$user_type_word = 'Administrator';
			}

			if($name == $searched || $surname == $searched || $phone_number == $searched || $email == $searched)
			{
				echo "<tr style = \"vertical-align: middle;\">
				      <th style=\"vertical-align:middle;\" scope=\"row\">".$i."</th>
				      <td style=\"vertical-align:middle;\">".$name."</td>
				      <td style=\"vertical-align:middle;\">".$surname."</td>
				      <td style=\"vertical-align:middle;\">".$user_type_word."</td>
				      <td style=\"vertical-align:middle;\">".$phone_number."</td>
				      <td style=\"vertical-align:middle;\">".$email."</td>

				      <td style=\"vertical-align:middle;\">
				      <a href=\"edit_user.php?id_user=".$id."\" class=\"btn btn-info button-training\">Edit</a> 
				      <a class=\"btn btn-danger button-training\" onclick = \"delete_user(".$id.")\" >Delete</a></td></tr>"; 
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