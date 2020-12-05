<?php 
  function node(){
    $db = new mysqli("localhost","root","","deviation");
    $db->query("set names utf8");
    $arc = $db->query('select * from arc');
    return $arc;
}

function add_new_vehicle($reg_plate, $color){
  $db = new mysqli("localhost","root","","deviation");
  $db->query("set names utf8");
  $new_vehicle = $db->query('insert into vehicle values('.$reg_plate.','.$color.')')
  return true;
}
?>