<?php

	session_start();
	if (!isset($_SESSION['signedin']))
	{
		header('Location: index.php');
		exit();
	}

$_SESSION['active_tab'] = "users";

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

if(isset($_POST['name']))
{
	$name = $_POST['name'];
	$surname = $_POST['surname'];
	$login = $_POST['login'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$password_confirm = $_POST['password_confirm'];

	$OK=true;

	if(!isset($_POST['user_type']))
	{
		$OK=false;
		$_SESSION['e_user_type']="Choose user type!";
	}
	else
	{
		$user_type = $_POST['user_type'];
	}

	if ((strlen($name)<2) || (strlen($name)>20))
	{
		$OK=false;
		$_SESSION['e_name']="Name has to be between 2 and 20 characters!";
	}

	if ((strlen($surname)<2) || (strlen($surname)>40))
	{
		$OK=false;
		$_SESSION['e_surname']="Surname has to be between 2 and 20 characters!";
	}

	if ((strlen($login)<2) || (strlen($login)>20))
	{
		$OK=false;
		$_SESSION['e_login']="Login has to be between 2 and 20 characters!";
	}

	if (ctype_alnum($login)==false)
	{
		$OK=false;
		$_SESSION['e_login']="Login can contain only letters and numbers";
	}

	$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
	if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
	{
		$OK=false;
		$_SESSION['e_email']="Provide a valid email address!";
	}

	if ((strlen($password)<4) || (strlen($password)>20))
	{
		$OK=false;
		$_SESSION['e_password']="Password has to be between 4 and 20 characters!";
	}
	
	if ($password!=$password_confirm)
	{
		$OK=false;
		$_SESSION['e_password']="The entered passwords are not identical!";
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
			//Czy email już istnieje?
			$result = $connection->query("SELECT id FROM Users WHERE Email='$email'");
			
			if (!$result) throw new Exception($connection->error);
			
			$nr_of_emails = $result->num_rows;
			if($nr_of_emails>0)
			{
				$OK=false;
				$_SESSION['e_email']="There is already an account assigned to this email address!";
			}		

			//Czy nick jest już zarezerwowany?
			$result = $connection->query("SELECT id FROM Users WHERE login='$login'");
			
			if (!$result) throw new Exception($connection->error);
			
			$nr_of_logins = $result->num_rows;
			if($nr_of_logins>0)
			{
				$OK=false;
				$_SESSION['e_login']="Login is already taken!";
			}
			
			if ($OK==true)
			{ 
				//Hurra, wszystkie testy zaliczone, dodajemy gracza do bazy
				
				if ($connection->query("INSERT INTO Users VALUES (NULL, '$name', '$surname', '$login', '$password', '$user_type', 0, '','$email', 0, 0)"))
				{
					$_SESSION['user_added']=true;
				}
				else
				{
					throw new Exception($connection->error);
				}
				
			}
			
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
<html lang="pl">
<head>
  	<title>Users</title>
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

<div class="row">
	<div class="col-xs-6">
		<button type="button" id="myBtn" class="btn btn-success">Add+</button><br/><br/>
	</div>
	<div class="col-xs-6 text-right">
<form class="navbar-form" style="margin: 0; padding-right: 0;" method="post" action="search_users.php">
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
		$result = $connection->query("SELECT COUNT(ID) FROM Users WHERE ID != '$id_user'");
		$row = $result -> fetch_assoc();
		$nr_of_records = $row['COUNT(ID)'];

		$nr_of_pages = round($nr_of_records/$elements_on_page);
		if($nr_of_records - $nr_of_pages*$elements_on_page>0)
		{
			$nr_of_pages = $nr_of_pages + 1;
		}

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

			if($i>($page-1)*$elements_on_page && $i<=($page-1)*$elements_on_page+$elements_on_page)
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
	    			echo "<li class=\"page-item\"><a class=\"page-link\" href=\"users.php?page=".$prev_page."\">Previous</a></li>";
	    		}

		    	for ($i=1; $i <= $nr_of_pages; $i++) 
		    	{ 
		    		echo "<li class=\"page-item ";
		    		if($i == $page)
		    		{
		    			echo "active";
		    		}
		    		echo"\"><a class=\"page-link\" href=\"users.php?page=".$i."\">".$i."</a></li>";
		    	}

		    	$next_page = $page + 1;
		    	if ($page != $nr_of_pages) 
	    		{
	    			echo "<li class=\"page-item\"><a class=\"page-link\" href=\"users.php?page=".$next_page."\">Next</a></li>";
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
			      <h1 class="title">Add user</h1>
			      <hr style="width:80%;" />
			   </div>
			</div>
	      <form class="form-horizontal" method="post" action="">

	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Name</label>
	          <div class="cols-sm-10">
	              <input type="text" class="form-control" name="name" placeholder="Enter your name">
	          </div>
	              <?php
					if (isset($_SESSION['e_name']))
					{
						echo '<div class="cols-sm-2 error"> '.$_SESSION['e_name'].'</div>';
						unset($_SESSION['e_name']);
					}
				?>
	        </div>

	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Surame</label>
	          <div class="cols-sm-10">
	              <input type="text" class="form-control" name="surname" placeholder="Enter your surname">
	          </div>
	          <?php
					if (isset($_SESSION['e_surname']))
					{
						echo '<div class="cols-sm-2 error"> '.$_SESSION['e_surname'].'</div>';
						unset($_SESSION['e_surname']);
					}
				?>
	        </div>

	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Login</label>
	          <div class="cols-sm-10">
	              <input type="text" class="form-control" name="login" placeholder="Enter your login">
	          </div>
	          <?php
					if (isset($_SESSION['e_login']))
					{
						echo '<div class="cols-sm-2 error"> '.$_SESSION['e_login'].'</div>';
						unset($_SESSION['e_login']);
					}
				?>
	        </div>

	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Email</label>
	          <div class="cols-sm-10">
	              <input type="text" class="form-control" name="email" placeholder="Enter your email">
	          </div>
	          <?php
					if (isset($_SESSION['e_email']))
					{
						echo '<div class="cols-sm-2 error"> '.$_SESSION['e_email'].'</div>';
						unset($_SESSION['e_email']);
					}
				?>
	        </div>

	        <div class="form-group">
        		<label class="cols-sm-2 control-label">User type</label>
        		<div class="cols-sm-10">
        			<select  name="user_type" class="form-control" >
						<option disabled selected value>Choose user type</option>
						<option value = "1">Standard</option>
						<option value = "2">Trainer</option>
						<option value = "3">Administrator</option>
					</select>
        		</div>
        		<?php
					if (isset($_SESSION['e_user_type']))
					{
						echo '<div class="cols-sm-2 error"> '.$_SESSION['e_user_type'].'</div>';
						unset($_SESSION['e_user_type']);
					}
				?>
	        </div>
	        
	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Password</label>
	          <div class="cols-sm-10">
	              <input type="password" class="form-control" name="password" placeholder="Enter your password">
	          </div>
	          <?php
					if (isset($_SESSION['e_password']))
					{
						echo '<div class="cols-sm-2 error"> '.$_SESSION['e_password'].'</div>';
						unset($_SESSION['e_password']);
					}
				?>
	        </div>

	        <div class="form-group">
	          <label class="cols-sm-2 control-label">Confirm password</label>
	          <div class="cols-sm-10">
	              <input type="password" class="form-control" name="password_confirm" placeholder="Confirm your password">
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