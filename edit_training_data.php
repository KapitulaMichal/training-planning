<?php

	session_start();
	if (!isset($_SESSION['signedin']))
	{
		header('Location: index.php');
		exit();
	}

	$nr = $_GET['nr'];
	$id_session = $_GET['id_session'];

	if(isset($_GET['id_user']))
	{
		$id_user = $_GET['id_user'];
	}

	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);

	if(isset($_POST['description']))
	{

		$name = $_POST['name'];
		$date = $_POST['date'];
		$type = $_POST['type'];

		if($type == 0)
		{
			$type = "NULL";
		}

		if($_POST['trainer'] == 'null')
		{
			$trainer = "NULL";
		}
		else
		{
			$trainer = $_POST['trainer'];
		}
		
		$location = $_POST['location'];
		$description = $_POST['description'];

		$query = "UPDATE Training_session SET Name = '".$name."', Training_date = '".$date."', ID_Training_type = ".$type.", ID_Trainer = ".$trainer.", Location = '".$location."', Description = '".$description."' WHERE ID = ".$id_session;

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

				$connection -> close();
			}				
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Server error</span>';
			echo '<br />Information for developers: '.$e;
		}

		if(isset($id_user))
		{
			$link = "edit_training_user.php?id_session=".$id_session."&nr=".$nr."&id_user=".$id_user;
		}
		else
		{
			$link = "edit_training.php?id_session=".$id_session."&nr=".$nr;
		}
		header('Location:'.$link);
		exit();
	}


	try
	{
		$connection = new mysqli($host, $db_user, $db_password, $db_name);
		if ($connection->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{
			$result = $connection->query("SELECT * FROM Training_session WHERE ID = '$id_session'");

			$row = $result->fetch_assoc();
			$name = $row['Name'];
			$date = $row['Training_date'];
			$type = $row['ID_Training_type'];
			$trainer = $row['ID_Trainer'];
			$location = $row['Location'];
			$description = $row['Description'];
			
			$result->free();
			$connection -> close();
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
      <h1 class="title">Edit training data</h1>
      <hr style=" width: 15%;"/>
    </div>
</div>

<div class="">
	    <div class = "center" >

	    	<form class="form-horizontal" method="post" action="">

	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Name</label>
	          <div class="cols-sm-10">
	              <input type="text" class="form-control" name="name" placeholder="Enter name of the training"
	              <?php echo "value =\"".$name."\""; ?>>
	          </div>
	        </div>

	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Date</label>
	          <div class="cols-sm-10">
	              <input type="date" class="form-control" name="date" placeholder="Enter name of the exercise"
	               <?php echo "value =\"".$date."\""; ?>>
	          </div>
	        </div>

	        <div class="form-group">
        		<label class="cols-sm-2 control-label">Training type</label>
        		<div class="cols-sm-10">
        			<select name="type" class="form-control" >
						<option value ="0"> Select type</option>
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

										echo "<option value= \"".$id."\"" ;

										if($type == $id)
										{
											echo "selected=\"selected\"";
										}

										echo ">".$name."</option>\n";
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
						<option selected value = "null"> Select trainer</option>
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

										echo "<option value= \"".$id."\"";

										if($trainer == $id)
										{
											echo "selected=\"selected\"";
										} 

										echo">".$name." ". $surname ."</option>\n";
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
	              <input type="text" class="form-control" name="location" placeholder="Insert location"
	               <?php echo "value =\"".$location."\""; ?>>
	          </div>
	        </div>

	       
	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Description</label>
	          <div class="cols-sm-10">
	              <input type="text" class="form-control" name="description" placeholder="Description"
	               <?php echo "value =\"".$description."\""; ?>>
	          </div>
	        </div>

	        <div class="form-group">
	          <button type="submit button" name="submitbutton" class="btn btn-primary btn-lg btn-block login-button">Change</button>
	        </div>

	        <div class="login-register">
          		<?php 
          		if(isset($id_user))
          		{
          			echo"<a href=\"edit_training_user.php?id_session=".$id_session."&nr=".$nr."&id_user=".$id_user."\">Back</a>";
          		}
          		else
          		{
          			echo"<a href=\"edit_training.php?id_session=".$id_session."&nr=".$nr."\">Back</a>";
				}
          		?>
        	</div>
	      </form>
	  </div>
  </div>

</body>
</html>