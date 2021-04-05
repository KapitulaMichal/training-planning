<?php

	session_start();
	if (!isset($_SESSION['signedin']))
	{
		header('Location: index.php');
		exit();
	}

$_SESSION['active_tab'] = "create_training";

$elements_on_page = 10;

if(isset($_GET['page']))
{
	$page = $_GET['page'];
}
else
{
	$page = 1;
}

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

<div  style="text-align: right; margin-bottom: 15px;">
	<form class="navbar-form" style="margin: 0; padding-right: 0;" method="post" action="search_create_training.php">
		<div class="input-group add-on" style="text-align: right;">
			<input class="form-control" placeholder="Search" name="srch-term" id="srch-term" type="text" >
			<div class="input-group-btn">
				<button class="submit button btn btn-default" style="position: static;"><i class="glyphicon glyphicon-search"></i></button>
			</div>
		</div>
	</form>
</div>
<table class="table table-striped table-bordered table-responsive" >
  	<thead>
	    <tr>
	      <th style="vertical-align: middle;">#</th>
	      <th style="vertical-align: middle;">Name</th>
	      <th style="vertical-align: middle;">Surname</th>
	      <th style="vertical-align: middle;">Phone number</th>
	      <th style="vertical-align: middle;">Email</th>
	      <th style="vertical-align: middle;">Gender</th>
	      <th style="vertical-align: middle;">Height</th>
	      <th style="vertical-align: middle;">Weight</th>
 	      <th style="width: 70px;"></th>
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
		$result = $connection->query("SELECT COUNT(ID) FROM Users WHERE ID != '$id_user' AND User_type != 3");
		$row = $result -> fetch_assoc();
		$nr_of_records = $row['COUNT(ID)'];

		$nr_of_pages = round($nr_of_records/$elements_on_page);
		if($nr_of_records - $nr_of_pages*$elements_on_page>0)
		{
			$nr_of_pages = $nr_of_pages + 1;
		}

		$result = $connection->query("SELECT ID, Name, Surname, Phone_number, Email, User_type, Gender, Height, Weight FROM Users ORDER BY ID");

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

				if($row['Gender'] == 1)
				{
					$gender = "M";
				}
				else if($row['Gender'] == 2)
				{
					$gender = "F";
				}
				else
				{
					$gender = NULL;
				}

				if($row['Height'] == 0)
				{
					$height = NULL;
				}
				else
				{
					$height = $row['Height'];
				}

				if($row['Weight'] == 0)
				{
					$weight = NULL;
				}
				else
				{
					$weight = $row['Weight'];
				}
				

				if($i>($page-1)*$elements_on_page && $i<=($page-1)*$elements_on_page+$elements_on_page)
				{
					echo "<tr style = \"vertical-align: middle;\">
					      <th style=\"vertical-align:middle;\" scope=\"row\">".$i."</th>
					      <td style=\"vertical-align:middle;\"><strong>".$name."</strong></td>
					      <td style=\"vertical-align:middle;\">".$surname."</td>
					      <td style=\"vertical-align:middle;\">".$phone_number."</td>
					      <td style=\"vertical-align:middle;\">".$email."</td>
					      <td style=\"vertical-align:middle;\">".$gender."</td>
					      <td style=\"vertical-align:middle;\">".$height."</td>
					      <td style=\"vertical-align:middle;\">".$weight."</td>
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

<?php

if($nr_of_pages>1)	
{
	echo "
	<div align=\"center\">
		<nav aria-label=\"Page navigation example\">
		  <ul class=\"pagination\">";
		    
		    	$prev_page = $page - 1;
		    	if ($page != 1) 
	    		{
	    			echo "<li class=\"page-item\"><a class=\"page-link\" href=\"create_training.php?page=".$prev_page."\">Previous</a></li>";
	    		}

		    	for ($i=1; $i <= $nr_of_pages; $i++) 
		    	{ 
		    		echo "<li class=\"page-item ";
		    		if($i == $page)
		    		{
		    			echo "active";
		    		}
		    		echo"\"><a class=\"page-link\" href=\"create_training.php?page=".$i."\">".$i."</a></li>";
		    	}

		    	$next_page = $page + 1;
		    	if ($page != $nr_of_pages) 
	    		{
	    			echo "<li class=\"page-item\"><a class=\"page-link\" href=\"create_training.php?page=".$next_page."\">Next</a></li>";
	    		}
		    
		echo" </ul>
		</nav>
	</div>";
}
?>
</div>

</body>
</html>