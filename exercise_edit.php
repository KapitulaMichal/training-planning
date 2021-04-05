<?php

	session_start();
	if (!isset($_SESSION['signedin']))
	{
		header('Location: index.php');
		exit();
	}

$id_user = $_SESSION['id'];
$id = $_GET['id'];

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
		$result = $connection->query("SELECT * FROM Exercises WHERE ID = '$id'");

		$row = $result -> fetch_assoc();
		$name = $row['Name'];
		$type = $row['ID_Exercise_type'];
		$calories_burning_rate = $row['Calories_burning_rate'];
		$equipment = $row['Equipment'];


		$result = $connection->query("SELECT Name FROM Body_parts INNER JOIN Exercises_Body_parts ON Exercises_Body_parts.ID_Body_parts = Body_parts.ID WHERE ID_Exercises ='$id'");
		
		while($row = $result -> fetch_assoc())
		{
			if($row['Name'] == 'Arms')
			{
				$arms = true;
			}
			else if($row['Name'] == 'Back')
			{
				$back = true;
			}
			else if($row['Name'] == 'Chest')
			{
				$chest = true;
			}
			else if($row['Name'] == 'Core')
			{
				$core = true;
			}
			else if($row['Name'] == 'Legs')
			{
				$legs = true;
			}
			else if($row['Name'] == 'Shoulders')
			{
				$shoulders = true;
			}
			else if($row['Name'] == 'ABS')
			{
				$abs = true;
			}
		}



	}
}
catch(Exception $e)
{
	echo '<span style="color:red;">Server error</span>';
	echo '<br />Information for developers: '.$e;
}


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
			      <h1 class="title">Edit exercise</h1>
			      <hr style=" width: 15%;" />
			   </div>
			</div>
 <div class="container">
  	<!-- Modal content -->
  	<div class="">
	    <div class = "center">
	      <form class="form-horizontal" method="post" action=<?php echo "\"exercise_edit_script.php?id=".$id."\"" ?>>

	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Name</label>
	          <div class="cols-sm-10">
	              <input type="text" class="form-control" name="name" placeholder="Enter name of the exercise"
	              <?php
	              if(isset($name))
	              {
	              	echo "value=\"".$name."\"";
	              }
	              ?>
	              >
	          </div>
	        </div>

	        <div class="form-group">
        		<label class="cols-sm-2 control-label">Exercise type</label>
        		<div class="cols-sm-10">
        			<select name="type" class="form-control" >
        			  <option <?php 
					  if(!isset($type))
					  {
					  	echo "selected=\"selected\"";
					  }

    			  	  ?> value= "0"> Select type</option>
					  <option <?php 
					  if(isset($type) && $type == 1)
					  {
					  	echo "selected=\"selected\"";
					  }

					   ?> value= "1" >Endurance</option>
					  <option <?php 
					  if(isset($type) && $type == 2)
					  {
					  	echo "selected=\"selected\"";
					  }

					   ?> value= "2" >Strength</option>
					  <option <?php 
					  if(isset($type) && $type == 3)
					  {
					  	echo "selected=\"selected\"";
					  }

					   ?> value= "3" >Balance</option>
					  <option <?php 
					  if(isset($type) && $type == 4)
					  {
					  	echo "selected=\"selected\"";
					  }

					   ?> value= "4" >Flexibility</option>
					</select>
        		</div>
	        </div>

	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Calories burning rate [cal/h]</label>
	          <div class="cols-sm-10">
	              <input type="number" class="form-control" name="calories_burning_rate" placeholder="Calories per hour" min="1" max="5000" <?php
	              if(isset($calories_burning_rate))
	              {
	              	echo "value=\"".$calories_burning_rate."\"";
	              }
	              ?>>
	          </div>
	        </div>

	       
	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Equipment</label>
	          <div class="cols-sm-10">
	              <input type="text" class="form-control" name="equipment" placeholder="Equipment" 
	              <?php
	              if(isset($equipment))
	              {
	              	echo "value=\"".$equipment."\"";
	              }
	              ?>/>
	          </div>
	        </div>

	        <div class="form-group">
	        	<label class="cols-sm-2 control-label">Body parts</label>

	        	<div class = "cols-sm-10">
	        		<input name = "arms" type = "checkbox" value ="1" 
	        		<?php
	        			if(isset($arms)&&$arms==true)
	        			{
	        				echo "checked";
	        			}
					?>
	        		>Arms</input>
    			</div>
	        		
	        	<div class = "cols-sm-10">
	        		<input name = "back" type = "checkbox" value ="2"
	        		<?php
	        			if(isset($back)&&$back==true)
	        			{
	        				echo "checked";
	        			}
					?>
	        		 >Back</input>
	        	</div>

	        	<div class = "cols-sm-10">
	        		<input name = "chest" type = "checkbox" value ="3" 
	        		<?php
	        			if(isset($chest)&&$chest==true)
	        			{
	        				echo "checked";
	        			}
					?>
	        		>Chest</input>
        		</div>
	        	
	        	<div class = "cols-sm-10">
	        		<input name = "core" type = "checkbox" value ="4"
	        		<?php
	        			if(isset($core)&&$core==true)
	        			{
	        				echo "checked";
	        			}
					?>
	        		>Core</input>
	        	</div>

	        	<div class = "cols-sm-10">
	        		<input name = "legs" type = "checkbox" value ="5"
	        		<?php
	        			if(isset($legs)&&$legs==true)
	        			{
	        				echo "checked";
	        			}
					?>
	        		 >Legs</input>
	        	</div>

	        	<div class = "cols-sm-10">
	        		<input name = "shoulders" type = "checkbox" value ="6" 
	        		<?php
	        			if(isset($shoulders)&&$shoulders==true)
	        			{
	        				echo "checked";
	        			}
					?>
	        		>Shoulders</input>
	        	</div>

	        	<div class = "cols-sm-10">
	        		<input name = "abs" type = "checkbox" value ="7" 
	        		<?php
	        			if(isset($abs)&&$abs==true)
	        			{
	        				echo "checked";
	        			}
					?>
	        		>ABS</input>
	        	</div>
	        </div>

	        <div class="form-group ">
	          <button type="submit button" name="submitbutton" class="btn btn-primary btn-lg btn-block login-button">Edit</button>
	        </div>
	        <div class="login-register">
          <a href="exercises.php">Back</a>
        </div>
	      </form>
	  </div>
  </div>

</div>

</body>
</html>