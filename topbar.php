<nav class="navbar navbar-default">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
      <li><!-- <a href="#"><i class="glyphicon glyphicon-home"></i></a> --></li>
    </ul>
      
    <ul class="nav navbar-nav navbar-right">

      <?php 
      if($_SESSION['user_type'] == 2)
      {
        echo '<li><a ';
        if($_SESSION['active_tab'] == "create_training")
          echo "class= \"active\""; 
        echo 'href="create_training.php">Create training</a></li>';
      }

      if($_SESSION['user_type'] == 3) 
      {
        echo '<li><a ';
        if($_SESSION['active_tab'] == "users")
          echo "class= \"active\"";
        echo'href="users.php">Users</a></li> ';
      }
      
      if($_SESSION['user_type'] == 1 || $_SESSION['user_type'] == 2) 
      {
        echo "<li><a ";
        if($_SESSION['active_tab'] == "training")
          echo "class = \"active\" ";
        echo "href=\"training.php\">Trainings</a></li>";
      }      
      
      if($_SESSION['user_type'] == 1 || $_SESSION['user_type'] == 2) 
      {
        echo "<li><a ";
            if($_SESSION['active_tab'] == "exercises")  echo "class= \"active\"";  
             echo " href=\"exercises.php\">Exercises</a></li>";
      }

      if($_SESSION['user_type'] == 3) 
      {
        echo "<li><a ";
            if($_SESSION['active_tab'] == "exercises")  echo "class= \"active\"";  
             echo " href=\"exercises_admin.php\">Permanent exercises</a></li>";
      }

      ?>
      <li>
        <a <?php if($_SESSION['active_tab'] == "account") echo "class= \"active\""; ?> href="change_account_data.php">My account</a>
      </li>

      <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $_SESSION["name"]." ".$_SESSION["surname"] ?><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a></li>
         </ul>
        </li>       
    </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>