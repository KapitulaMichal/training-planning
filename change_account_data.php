<?php

  session_start();
  if (!isset($_SESSION['signedin']))
  {
    header('Location: index.php');
    exit();
  }

  $_SESSION['active_tab'] = "account";

  if (isset($_POST['email']))
  { 

    $OK=true;
    
    $name = $_POST['name'];
    
    if ((strlen($name)<2) || (strlen($name)>20))
    {
      $OK=false;
      $_SESSION['e_name']="Name has to be between 2 and 20 characters!";
    }

    $surname = $_POST['surname'];

    if ((strlen($surname)<2) || (strlen($surname)>40))
    {
      $OK=false;
      $_SESSION['e_surname']="Surname has to be between 2 and 20 characters!";
    }

   if(isset($_POST['gender']))
   {
      $gender = $_POST['gender'];
    }
    else
    {
      $gender = 0;
    }
  
    
    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
    {
      $OK=false;
      $_SESSION['e_email']="Provide a valid email address!";
    }

    $phone_number = $_POST['phone_number'];
    if (ctype_digit($phone_number)==false)
    {
      $OK=false;
      $_SESSION['e_phone_number'] = "Phone number can contain only numbers";
    }

    if (strlen($phone_number) < 7 || strlen($phone_number) > 12 )
    {
      $OK=false;
      $_SESSION['e_phone_number'] = "Phone number can contain from 7 to 12 digits";
    }
    
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    
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
        //Czy email już istnieje?
        $result = $connection->query("SELECT id FROM Users WHERE Email='$email'");
        
        if (!$result) throw new Exception($connection->error);
        
        if($email != $_SESSION['email'])
        {
          $nr_of_emails = $result->num_rows;
          if($nr_of_emails>0)
          {
            $OK=false;
            $_SESSION['e_email']="There is already an account assigned to this email address!";
          } 
        }
        
        if ($OK==true)
        {          
          $login = $_SESSION['login'];
          //Hurra, wszystkie testy zaliczone, dodajemy gracza do bazy
          
          if ($connection->query("UPDATE Users SET Name = '$name', Surname = '$surname', Gender = '$gender', Phone_number = '$phone_number', Email = '$email', Height='$height', Weight = '$weight' WHERE Login ='$login' "))
          { 
            //Usuwanie błędów rejestracji
            if (isset($_SESSION['e_name'])) unset($_SESSION['e_name']);
            if (isset($_SESSION['e_surname'])) unset($_SESSION['e_surname']);
            if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
            if (isset($_SESSION['e_phone_number'])) unset($_SESSION['e_phone_number']);

            $_SESSION['name'] = $name;
            $_SESSION['surname'] = $surname;
            $_SESSION['gender'] = $gender;
            $_SESSION['phone_number'] = $phone_number;
            $_SESSION['email'] = $email;
            $_SESSION['height'] = $height;
            $_SESSION['weight'] = $weight;

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
  <title>Account</title>
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
      <h1 class="title">Change your account information</h1>
      <hr />
    </div>
</div>


<div class="container">
  <div class = "center" >
    <?php if(isset($change_made) && $change_made == true)
      {
        echo '<h4>Your data was changed</h4>';
      } 
      ?>
      <form class="form-horizontal" method="post" action="">

        <div class="form-group">
          <label class="cols-sm-2 control-label">Name</label>
          <div class="cols-sm-10">
              <input type="text" class="form-control" name="name" placeholder="Enter your name" 
                    <?php
                      if (isset($_SESSION['name']))
                      {
                        echo 'value="' .$_SESSION['name'].'"';
                      }
                    ?>/>
                  <?php
                    if (isset($_SESSION['e_name']))
                    {
                      echo '<div class = "error"> '.$_SESSION['e_name'].'</div>';
                      unset($_SESSION['e_name']);
                    }
                  ?>
          </div>
        </div>

        <div class="form-group">
          <label class="cols-sm-2 control-label">Surname</label>
          <div class="cols-sm-10">
              <input type="text" class="form-control" name="surname" placeholder="Enter your surname" 
                    <?php
                      if (isset($_SESSION['surname']))
                      {
                        echo 'value="'. $_SESSION['surname'].'"';
                      }
                    ?>/>
                    <?php
                    if (isset($_SESSION['e_surname']))
                    {
                      echo '<div class = "error"> '.$_SESSION['e_surname'].'</div>';
                      unset($_SESSION['e_surname']);
                    }
                  ?>
          </div>
        </div>

        <div class="form-group" id="radio">
          <label class="cols-sm-2 control-label">Gender</label>
          
              <div>
                <div class="radio-left">
                  <label><input type="radio" name="gender" 
                    <?php if (isset($_SESSION['gender']) && $_SESSION['gender'] == 1)
                      {
                        echo "checked";
                      } 
                    ?> value= 1>Male</label>
                </div>

                <div class="radio-right">
                  <label><input type="radio" name="gender" 
                    <?php if (isset($_SESSION['gender']) && $_SESSION['gender'] == 2)
                      {
                        echo "checked";
                      } 
                    ?> value = 2 >Female</label>
                </div>

              </div>
          
        </div>

        <div class="form-group">
          <label class="cols-sm-2 control-label">Email</label>
          <div class="cols-sm-10">
              <input type="text" class="form-control" name="email" placeholder="Phone number" 
              <?php
                if (isset($_SESSION['email']))
                {
                  echo 'value="'. $_SESSION['email'].'"';
                } 
              ?> />
              <?php
                    if (isset($_SESSION['e_email']))
                    {
                      echo '<div class = "error"> '.$_SESSION['e_email'].'</div>';
                      unset($_SESSION['e_email']);
                    }
                  ?>
          </div>
        </div>

        <div class="form-group">
          <label class="cols-sm-2 control-label">Phone number</label>
          <div class="cols-sm-10">
              <input type="text" class="form-control" name="phone_number" placeholder="Phone number"
              <?php
                if (isset($_SESSION['phone_number']))
                {
                  echo 'value="'. $_SESSION['phone_number'].'"';
                } 
              ?> />
              <?php
                    if (isset($_SESSION['e_phone_number']))
                    {
                      echo '<div  class = "error"> '.$_SESSION['e_phone_number'].'</div>';
                      unset($_SESSION['e_phone_number']);
                    }
                  ?>
          </div>
        </div>

        <div class="form-group">
          <label class="cols-sm-2 control-label">Height [cm]</label>
          <div class="cols-sm-10">
              <input type="number" class="form-control" name="height" placeholder="Enter your height" min="100" max="240" 
              <?php
                if (isset($_SESSION['height']))
                {
                  echo 'value="'.$_SESSION['height'].'"';
                } 
              ?> />
          </div>
        </div>

        <div class="form-group">
          <label class="cols-sm-2 control-label">Weight [kg]</label>
          <div class="cols-sm-10">
              <input type="number" class="form-control" name="weight" placeholder="Enter your weight" min="30" max="200" 
              <?php
                if (isset($_SESSION['weight']))
                {
                  echo 'value="'. $_SESSION['weight'].'"';
                } 
              ?> />
          </div>
        </div>

        <div class="form-group ">
          <button type="submit button" name="submitbutton" class="btn btn-primary btn-lg btn-block login-button">Accept</button>
        </div>
            
        <div class="login-register">
          <a href="password.php">Change password</a>
        </div>
      </form>
  </div>
</body>
</html>