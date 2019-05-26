<?php
  require_once 'base.php';

  //create connection. First need to instantiate new mysqli class
  $mysqli =  new Mysqli('localhost', 'root', '', 'todoapp2');

  //check if connection is successful
  if ($mysqli->connect_error){
      die("Connection failed:". $mysqli->connect_error);
  }
  //echo "Connected successfully!";
?>