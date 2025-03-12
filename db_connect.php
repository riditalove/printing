<?php
  $hostname = "localhost";
  $username = "root";
  $password = "";
  $database = "printing";
  
  $con = mysqli_connect($hostname,$username,$password,$database);
  if($con)
  {
    // echo 'sucess';
    
  }
  else
  {
    die(mysqli_error(($con)));
  }
?>