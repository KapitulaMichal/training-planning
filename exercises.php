<?php

	session_start();
	if (!isset($_SESSION['signedin']))
	{
		header('Location: index.php');
		exit();
	}

$_SESSION['active_tab'] = "exercises";

$elements_on_page = 10;

$id_user = $_SESSION['id'];

if(isset($_GET['page']))
{
	$page = $_GET['page'];
}
else
{
	$page = 1;
}
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
<div class="row">
	<div class="col-xs-6">
		<button type="button" id="myBtn" class="btn btn-success">Add+</button><br/><br/>
	</div>
	<div class="col-xs-6 text-right">
<form class="navbar-form" style="margin: 0; padding-right: 0;" method="post" action="search_exercises.php">
	<div class="input-group add-on" style="text-align: right;">
		<input class="form-control" placeholder="Search" name="srch-term" id="srch-term" type="text" >
		<div class="input-group-btn">
			<button class="btn btn-default" type="submit" style="position: static;"><i class="glyphicon glyphicon-search"></i></button>
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
		$result = $connection->query("SELECT COUNT(ID) FROM exercises WHERE ID_User = '$id_user'");
		$row = $result -> fetch_assoc();
		$user_exercises = $row['COUNT(ID)'];

		$result = $connection->query("SELECT COUNT(ID) FROM exercises WHERE Permanent = 1");
		$row = $result -> fetch_assoc();
		$permanent_exercises = $row['COUNT(ID)'];

		$nr_of_records = $user_exercises + $permanent_exercises;

		$nr_of_pages = round($nr_of_records/$elements_on_page);
		if($nr_of_records - $nr_of_pages*$elements_on_page>0)
		{
			$nr_of_pages = $nr_of_pages + 1;
		}


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
				if($i>($page-1)*$elements_on_page && $i<=($page-1)*$elements_on_page+$elements_on_page)
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

		$result = $connection->query("SELECT Exercises.ID, Exercises.Name, Exercise_types.Name as Type, Exercises.Calories_burning_rate, Exercises.Equipment, Exercises.Permanent, Exercises.ID_User FROM Exercises LEFT OUTER JOIN Exercise_types ON Exercises.ID_Exercise_type = Exercise_types.ID ORDER BY Exercises.Name");

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
				if($i>($page-1)*$elements_on_page && $i<=($page-1)*$elements_on_page+$elements_on_page)
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
		$result2->free();

		$connection->close();
	}
	
}
catch(Exception $e)
{
	echo '<span style="color:red;">Server error</span>';
	echo '<br />Information for developers: '.$e;
}

?>


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
	    			echo "<li class=\"page-item\"><a class=\"page-link\" href=\"exercises.php?page=".$prev_page."\">Previous</a></li>";
	    		}

		    	for ($i=1; $i <= $nr_of_pages; $i++) 
		    	{ 
		    		echo "<li class=\"page-item ";
		    		if($i == $page)
		    		{
		    			echo "active";
		    		}
		    		echo"\"><a class=\"page-link\" href=\"exercises.php?page=".$i."\">".$i."</a></li>";
		    	}

		    	$next_page = $page + 1;
		    	if ($page != $nr_of_pages) 
	    		{
	    			echo "<li class=\"page-item\"><a class=\"page-link\" href=\"exercises.php?page=".$next_page."\">Next</a></li>";
	    		}
		    
		echo" </ul>
		</nav>
	</div>";
}
?>


</div><!--container-->

<!-- The Modal -->
<div id="myModal" class="modal">

  	<!-- Modal content -->
  	<div class="modal-content">
	    <span class="close">&times;</span>
	    <div class = "center" >

	    	<div class="panel-heading">
			   <div class="panel-title text-center">
			      <h1 class="title">Add exercise</h1>
			      <hr style="width:80%;" />
			   </div>
			</div>
	      <form class="form-horizontal" method="post" action="add_exercise.php">

	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Name</label>
	          <div class="cols-sm-10">
	              <input type="text" class="form-control" name="name" placeholder="Enter name of the exercise">
	          </div>
	        </div>

	        <div class="form-group">
        		<label class="cols-sm-2 control-label">Exercise type</label>
        		<div class="cols-sm-10">
        			<select name="type" class="form-control" >
		    			<option disabled selected value> Select type</option>
						<option value= "1" >Endurance</option>
						<option value= "2" >Strength</option>
						<option value= "3" >Balance</option>
						<option value= "4" >Flexibility</option>
					</select>
        		</div>
	        </div>

	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Calories burning rate [cal/h]</label>
	          <div class="cols-sm-10">
	              <input type="number" class="form-control" name="calories_burning_rate" placeholder="Calories per hour" min="1" max="5000">
	          </div>
	        </div>

	       
	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Equipment</label>
	          <div class="cols-sm-10">
	              <input type="text" class="form-control" name="equipment" placeholder="Equipment" />
	          </div>
	        </div>

	        <div class="form-group">
	        	<label class="cols-sm-2 control-label">Body parts</label>

	        	<div class = "cols-sm-10">
	        		<input name = "arms" type = "checkbox" value ="1" >Arms</input>
    			</div>
	        		
	        	<div class = "cols-sm-10">
	        		<input name = "back" type = "checkbox" value ="2" >Back</input>
	        	</div>

	        	<div class = "cols-sm-10">
	        		<input name = "chest" type = "checkbox" value ="3" >Chest</input>
        		</div>
	        	
	        	<div class = "cols-sm-10">
	        		<input name = "core" type = "checkbox" value ="4" >Core</input>
	        	</div>

	        	<div class = "cols-sm-10">
	        		<input name = "legs" type = "checkbox" value ="5" >Legs</input>
	        	</div>

	        	<div class = "cols-sm-10">
	        		<input name = "shoulders" type = "checkbox" value ="6" >Shoulders</input>
	        	</div>

	        	<div class = "cols-sm-10">
	        		<input name = "abs" type = "checkbox" value ="7" >ABS</input>
	        	</div>
	        </div>

	        <div class="form-group ">
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