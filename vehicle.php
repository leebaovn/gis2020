<?php 
  session_start();

  $vehicle_number ='';
  $color = '';

  if(isset($_POST['id'])){
    $vehicle_number=$_POST['id'];
  }else{
    return false;
  }
  if(isset($_POST['color'])){
    $color=$_POST['color'];
    echo "<script type='text/javascript'>window.localStorage.setItem('color',$color)</script>";
  }

  $db = new mysqli("localhost","root","","deviation_plus");
  // $db->query("SET NAMES utf8");
  if ($db -> connect_errno) {
    return 'DATABASE CONNECT ERROR';
  }
  $db -> set_charset("utf8");
  $sql = "INSERT INTO vehicle (registration_plate, color) VALUES('$vehicle_number','$color')";
  header('Content-type: application/json');
  $result = $db -> query($sql);
  $_SESSION['vehicle']['number_id'] = $vehicle_number;
  $_SESSION['vehicle']['color'] = $color;
  $_SESSION['vehicle']['id'] = $db->insert_id;  
  echo header("refresh: 0; url = index.php");
  return json_encode($result);
