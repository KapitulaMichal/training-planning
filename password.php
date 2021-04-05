<?php

  session_start();
  if (!isset($_SESSION['signedin']))
  {
    header('Location: index.php');
    exit();
  }

  if (isset($_POST['new_password']))
  { 

    $OK=true;
    
    $new_password = $_POST['new_password'];
    $old_password = $_POST['old_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ((strlen($new_password)<5) || (strlen($new_password)>20))
    {
      $OK=false;
      $_SESSION['e_new']="Password has to be between 2 and 20 characters!";
    }
    
    if ($new_password != $confirm_password)
    {
      $OK=false;
      $_SESSION['e_new']="The entered passwords are not identical!";
    }
    
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
        $login = $_SESSION['login'];

        //Czy email już istnieje?
        $result = $connection->query("SELECT ID FROM Users WHERE Login='$login' AND password='$old_password'");
        
        if (!$result) throw new Exception($connection->error);
        
        $how_many_users = $result->num_rows;

        if($how_many_users <= 0)
        {
          $OK=false;
          $_SESSION['e_old'] ="Old password incorrect";
        }
        
        if ($OK==true)
        {          
          
          //Hurra, wszystkie testy zaliczone, dodajemy gracza do bazy
          
          if ($connection->query("UPDATE Users SET Password = '$new_password' WHERE Login ='$login' "))
          { 
            $change_made = true;
          }
          else
          {
            throw new Exception($connection->error);
          }
          $change_made =true;
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
<html lang="en">
<head>
  <title>Change</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css\bootstrap.css">
  <link rel="stylesheet" type="text/css" href="main.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
  <link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

<?php include 'topbar.php';?>

<div class="panel-heading">
   <div class="panel-title text-center">
      <h1 class="title">Change your password</h1>
      <hr />
    </div>
</div>

<div class="container">
  <div class = "center" >
    <?php if(isset($change_made) && $change_made == true)
      {
        echo '<h4>Changes were saved</h4>';
      } 
      ?>
      <form class="form-horizontal" method="post">

        <div class="form-group">
          <label class="cols-sm-2 control-label">Old Password</label>
          <div class="cols-sm-10">
              <input type="password" class="form-control" name="old_password" placeholder="Enter your old password"/>
                  <?php
                    if (isset($_SESSION['e_old']))
                    {
                      echo '<div class = "error"> '.$_SESSION['e_old'].'</div>';
                      unset($_SESSION['e_old']);
                    }
                  ?>
          </div>
        </div>

        <div class="form-group">
          <label class="cols-sm-2 control-label">New Password</label>
          <div class="cols-sm-10">
              <input type="password" class="form-control" name="new_password" placeholder="Enter your new password"/>
                    <?php
                    if (isset($_SESSION['e_new']))
                    {
                      echo '<div class = "error"> '.$_SESSION['e_new'].'</div>';
                      unset($_SESSION['e_new']);
                    }
                  ?>
          </div>
        </div>

        <div class="form-group">
          <label class="cols-sm-2 control-label">Confirm password</label>
          <div class="cols-sm-10">
              <input type="password" class="form-control" name="confirm_password" placeholder="Confirm your password"/>
          </div>
        </div>

        <div class="form-group ">
          <button type="submit button" name="submitbutton" class="btn btn-primary btn-lg btn-block login-button">Accept</button>
        </div>
            
        <div class="login-register">
          <a href="change_account_data.php">Back</a>
        </div>
      </form>
  </div>
</body>
</html>