<?php

	session_start();
	if (!isset($_SESSION['signedin']))
	{
		header('Location: index.php');
		exit();
	}

$id_user = $_GET['id_user'];

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

if(isset($_POST['location']))
{
	$name = $_POST['name'];
	$date = $_POST['date'];
	$location = $_POST['location'];
	$description = $_POST['description'];
	

	if(isset($_POST['type']))
	{
		$type = $_POST['type'];
	}
	else
	{
		$type = "NULL";
	}

	if(isset($_POST['trainer']))
	{
		$trainer = $_POST['trainer'];
	}
	else
	{
		$trainer = "NULL";
	}

	$query = "INSERT INTO Training_session VALUES (NULL,\"".$name."\",".$id_user.",\"".$date."\",".$type.",".$trainer.",\"".$location."\",\"".$description."\")";

	try 
	{
		$connection = new mysqli($host, $db_user, $db_password, $db_name);
		if ($connection->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{
			if($connection->query($query))
			{
				$_SESSION['training_added'] = true;
			}
			else
			{
				throw new Exception($connection->error);
			}

			$result = $connection->query("SELECT * FROM Exercises WHERE Name = '$name'");

			$row = $result->fetch_assoc();
			$id = $row['ID'];
			$connection->close();
		}
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Server error</span>';
		echo '<br />Information for developers: '.$e;
	}

}
	
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

<div class="panel-heading">
   <div class="panel-title text-center">
      <h1 class="title">Trainings </h1>
      <hr/>
      <?php
      	echo "<h4 class=\"title\" style = \"color:black;\"><strong>User: </strong>".$name." ".$surname. "</h4>";
      ?>
    </div>
</div>

<div class="container">
	<a href="create_training.php" id="back-button"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Back</a><br/>

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


<div class="row align-middle" style="vertical-align: middle; height: 70px;">

<div class="col-xs-1 align-middle" style="vertical-align:middle; padding-top: 18px;">
	<button type="button" id="myBtn" class="btn btn-success">Add+</button><br/><br/>
</div>
<div class="col-xs-1">
	
</div>

<form method="post" <?php echo "action=\"filter_training_date_user.php?id_user=".$id_user."\"" ?>>
<div class="col-xs-3">
<div "><strong>From:</strong></div>
	<div class="form-group"">
            <input name="date_from" type='date' class="form-control" placeholder="Start date" />
    </div>
</div>

<div class="col-xs-3">
<div "><strong>To:</strong></div>
	<div class="form-group"">
            <input name="date_to" type='date' class="form-control" placeholder="End date" />
    </div>
</div>

<div class="col-xs-1" style="padding-top: 18px;">
	<button type="submit" id="myBtn" class="btn btn-primary">Filter</button><br/><br/>
</div>
</form>

<div class="col-xs-3 text-right" style="padding-top: 18px;">
<form class="navbar-form" style="margin: 0; padding-right: 0;" method="post" <?php echo "action=\"search_user_trainings.php?id_user=".$id_user."\"" ?>>
	<div class="input-group add-on" style="text-align: right;">
		<input class="form-control" placeholder="Search" name="srch-term" id="srch-term" type="text" >
		<div class="input-group-btn">
			<button class="submit button btn btn-default" style="position: static;"><i class="glyphicon glyphicon-search"></i></button>
		</div>
	</div>
</form>
</div>

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



try 
{
	$connection = new mysqli($host, $db_user, $db_password, $db_name);
	if ($connection->connect_errno!=0)
	{
		throw new Exception(mysqli_connect_errno());
	}
	else
	{
		$result = $connection->query("SELECT COUNT(ID) FROM training_session WHERE ID_User = '$id_user'");
		$row = $result -> fetch_assoc();
		$nr_of_records = $row['COUNT(ID)'];

		$nr_of_pages = round($nr_of_records/$elements_on_page);
		if($nr_of_records - $nr_of_pages*$elements_on_page>0)
		{
			$nr_of_pages = $nr_of_pages + 1;
		}

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

			if($i>($page-1)*$elements_on_page && $i<=($page-1)*$elements_on_page+$elements_on_page)
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

				      <a href=\"edit_training_user.php?id_session=".$id."&nr=".$i."&id_user=".$id_user."\"  class=\"btn btn-info button-training\">Edit</a> 
				      <a class=\"btn btn-danger button-training\"  onclick = \"delete_training(".$id.")\" >Delete</a></td>"; 
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
	    			echo "<li class=\"page-item\"><a class=\"page-link\" href=\"user_trainings.php?id_user=".$id_user."&page=".$prev_page."\">Previous</a></li>";
	    		}

		    	for ($i=1; $i <= $nr_of_pages; $i++) 
		    	{ 
		    		echo "<li class=\"page-item ";
		    		if($i == $page)
		    		{
		    			echo "active";
		    		}
		    		echo"\"><a class=\"page-link\" href=\"user_trainings.php?id_user=".$id_user."&page=".$i."\">".$i."</a></li>";
		    	}

		    	$next_page = $page + 1;
		    	if ($page != $nr_of_pages) 
	    		{
	    			echo "<li class=\"page-item\"><a class=\"page-link\" href=\"user_trainings.php?id_user=".$id_user."&page=".$next_page."\">Next</a></li>";
	    		}
		    
		echo" </ul>
		</nav>
	</div>";
}
?>

</div>

<!-- The Modal -->
<div id="myModal" class="modal">

  	<!-- Modal content -->
  	<div class="modal-content">
	    <span class="close">&times;</span>
	    <div class = "center" >

	    	<div class="panel-heading">
			   <div class="panel-title text-center">
			      <h1 class="title">Add training</h1>
			      <hr style="width:80%;" />
			   </div>
			</div>
	      <form class="form-horizontal" method="post" action="">

	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Name</label>
	          <div class="cols-sm-10">
	              <input type="text" class="form-control" name="name" placeholder="Enter name of the training">
	          </div>
	        </div>

	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Date</label>
	          <div class="cols-sm-10">
	              <input type="date" class="form-control" name="date" placeholder="Enter name of the training">
	          </div>
	        </div>

	        <div class="form-group">
        		<label class="cols-sm-2 control-label">Training type</label>
        		<div class="cols-sm-10">
        			<select name="type" class="form-control" >
						<option disabled selected value> Select type</option>
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
									$result = $connection->query("SELECT ID, Name FROM Training_types");

									while ($row = $result->fetch_assoc())
									{
										$id = $row['ID'];
										$name = $row['Name'];

										echo "<option value= \"".$id."\" >".$name."</option>\n";
									}
									$result->free();
									$connection ->close();

								}
							}
							catch(Exception $e)
							{
								echo '<span style="color:red;">Server error</span>';
								echo '<br />Information for developers: '.$e;
							}

						?>
					</select>
        		</div>
	        </div>

	        <div class="form-group">
        		<label class="cols-sm-2 control-label">Trainer</label>
        		<div class="cols-sm-10">
        			<select name="trainer" class="form-control" >
						<option disabled selected value> Select trainer</option>
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
									$result = $connection->query("SELECT ID ,Name, Surname FROM Users WHERE User_type = 2 ORDER BY ID");

									while ($row = $result->fetch_assoc())
									{
										$id = $row['ID'];
										$name = $row['Name'];
										$surname = $row['Surname'];

										echo "<option value= \"".$id."\" >".$name." ". $surname ."</option>\n";
									}
									$result->free();
									$connection ->close();

								}
							}
							catch(Exception $e)
							{
								echo '<span style="color:red;">Server error</span>';
								echo '<br />Information for developers: '.$e;
							}

						?>
					</select>
        		</div>
	        </div>

	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Location</label>
	          <div class="cols-sm-10">
	              <input type="text" class="form-control" name="location" placeholder="Insert location">
	          </div>
	        </div>

	       
	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Description</label>
	          <div class="cols-sm-10">
	              <input type="text" class="form-control" name="description" placeholder="Description">
	          </div>
	        </div>

	        <div class="form-group">
	          <button type="submit button" name="submitbutton" class="btn btn-primary btn-lg btn-block login-button">Add</button>
	        </div>
	      </form>
	  </div>
  </div>

</div>

<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

</script>

</body>
</html>