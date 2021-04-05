<?php

$id_session = $_SESSION['id_session'];
$type = $_POST['exercise'];


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
		$result = $connection->query("INSERT INTO Series VALUES (NULL, '$type','$repetition' )");
	}



	$result -> free();
	$connection -> close();
}
catch(Exception $e)
{
	echo '<span style="color:red;">Server error</span>';
	echo '<br />Information for developers: '.$e;
}
?>