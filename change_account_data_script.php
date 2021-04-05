<?php
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
      if($_POST['gender'] == 'male')
      {
        $gender = 1;
      }
      if($_POST['gender'] == 'female')
      {
        $gender = 2;
      }
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
        $login = $_SESSION['login'];

        //Czy email już istnieje?
        $result = $connection->query("SELECT id FROM Users WHERE Email='$email'");
        
        if (!$result) throw new Exception($connection->error);
        
        $nr_of_emails = $result->num_rows;
        if($nr_of_emails>0)
        {
          $OK=false;
          $_SESSION['e_email']="There is already an account assigned to this email address!";
        }   
        
        if ($OK==true)
        {          
          //Hurra, wszystkie testy zaliczone, dodajemy gracza do bazy
          
          if ($connection->query("UPDATE Users SET Name = '$name', Surname = '$surname', Gender = '$gender', Phone_number = '$phone_number', Email = '$email', Height='$height', Weight = '$weight' WHERE Login ='$login' "))
          { 
            //Usuwanie błędów rejestracji
            if (isset($_SESSION['e_name'])) unset($_SESSION['e_name']);
            if (isset($_SESSION['e_surname'])) unset($_SESSION['e_surname']);
            if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
            if (isset($_SESSION['e_phone_number'])) unset($_SESSION['e_phone_number']);

            $change_made = true;
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
?>

<!DOCTYPE html>
<html lang="en">
    <head> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap.css">

    <!-- Website CSS style -->
    <link rel="stylesheet" type="text/css" href="main.css">

    <!-- Website Font style -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>

    <title>Login</title>
  </head>
  <body>
    <div class="container">
      <div class="row main">
          <div class="panel-heading">
                   <div class="panel-title text-center">
                      <h1 class="title">Welcome</h1>
                      <hr />
                    </div>
                </div>
        <div class="main-login main-center">
          <form class="form-horizontal" method="post" action="login.php">
              <div class="form-group">
                <label class="cols-sm-2 control-label">Data changed! </label>
                
              </div>
              <div class="form-group ">
                <a href = "index.php">
                  <button type="button" class="btn btn-primary btn-lg btn-block login-button">Login</button>
                </a>
              </div>
          </form>
        </div>
      </div>
    </div>

    <!--<script type="text/javascript" src="bootstrap.js"></script>-->
  </body>
</html>