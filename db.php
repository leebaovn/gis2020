<?php
session_start();
function getArc()
{
  $db = new mysqli("localhost", "root", "", "deviation_plus");
  // $db->query("SET NAMES utf8");
  if ($db->connect_errno) {
    return 'DATABASE CONNECT ERROR';
  }
  $db->set_charset("utf8");
  $result = $db->query("SELECT * FROM arc");
  while ($row = mysqli_fetch_assoc($result))
    $arc[] = $row;
  header('Content-type: application/json');
  return json_encode($arc);
}

function add_new_vehicle($reg_plate, $color)
{
  $db = new mysqli("localhost", "root", "", "deviation_plus");
  // $db->query("set names utf8");
  if ($db->connect_errno) {
    return 'DATABASE CONNECT ERROR';
  }
  $db->set_charset("utf8");
  $new_vehicle = $db->query("INSERT INTO vehicle (registration_plate, color) VALUES('$reg_plate','$color')");
  header('Content-type: application/json');
  return json_encode($new_vehicle);
  // return $db -> error;
}

function add_new_route($list, $vehicle_id)
{
  $db = new mysqli("localhost", "root", "", "deviation_plus");
  if ($db->connect_errno) {
    return "DATABASE CONNECT ERROR";
  }
  $db->set_charset("utf8");

  $points = [];
  $add_points_query = 'INSERT INTO point (longitude, latitude) VALUES';
  foreach ($list as $key => $value) {
    $point = explode(',', $value);
    $db->query($add_points_query . "($point[0], $point[1])");
    array_push($points, $db->insert_id);
  }

  $last = array_pop($points);

  $db->query("INSERT INTO arc (point_begin_id, point_end_id, vehicle_id) VALUES($points[0], $last, '$vehicle_id')");
  $arc_id = $db->insert_id;

  $add_arc_point_query = 'INSERT INTO arc_point (arc_id, point_id, sequence) VALUES ';
  foreach ($points as $key => $value) {
    if ($key > 0) {
      $add_arc_point_query .= "($arc_id, $value, $key),";
    }
  }

  $add_arc_point_query = substr($add_arc_point_query, 0, strlen($add_arc_point_query) - 1);
  $add_arc_point_result = $db->query($add_arc_point_query);

  $_SESSION['arc_id'] = $arc_id;
  echo header("refresh: 0; url = index.php");

  header('Content-type: application/json');
  return $arc_id;
  // return $db -> error;
  // return $sql;
}

function add_gps_point($point, $vehicle_id, $arc_id)
{
  $db = new mysqli("localhost", "root", "", "deviation_plus");
  if ($db->connect_errno) {
    return "DATABASE CONNECT ERROR";
  }
  $db->set_charset("utf8");
  $db->query("INSERT INTO point (longitude, latitude) VALUES($point[0], $point[1])");
  $point_id = $db->insert_id;

  $deviation = calculate_deviation($point_id, $arc_id);

  $db->query("INSERT INTO gps_point (deviation, point_id, vehicle_id) VALUES ($deviation, $point_id, $vehicle_id)");
  header('Content-type: application/json');
  return $deviation;
}

function calculate_deviation($point_id, $arc_id)
{
  $db = new mysqli("localhost", "root", "", "deviation_plus");
  if ($db->connect_errno) {
    return "DATABASE CONNECT ERROR";
  }
  $db->set_charset("utf8");

  $point_result = $db->query("SELECT * FROM point WHERE id=$point_id");
  while ($row = mysqli_fetch_assoc($point_result))
    $point[] = $row;

  $arc_result = $db->query("SELECT * FROM arc WHERE id=$arc_id");
  while ($row = mysqli_fetch_assoc($arc_result))
    $arc[] = $row;

  $arc_points_result = $db->query("SELECT * FROM arc_point WHERE arc_id=$arc_id");
  while ($row = mysqli_fetch_assoc($arc_points_result))
    $arc_points[] = $row;

  // array_push($arc_points, (object) ['point_id' => $arc[0]['point_end_id']]);
  // array_unshift($arc_points, (object) ['point_id' => $arc[0]['point_begin_id']]);

  $point_begin_id = $arc[0]['point_begin_id'];
  $point_end_id = $arc[0]['point_end_id'];
  $point_begin_result = $db->query("SELECT * FROM point WHERE id=$point_begin_id");
  while ($row = mysqli_fetch_assoc($point_begin_result))
    $point_begin[] = $row;

  $point_end_result = $db->query("SELECT * FROM point WHERE id=$point_end_id");
  while ($row = mysqli_fetch_assoc($point_end_result))
    $point_end[] = $row;


  $get_points_query = "SELECT * FROM point WHERE";
  foreach ($arc_points as $key => $value) {
    $id = $value['point_id'];
    $get_points_query .= " id=$id OR";
  }
  $get_points_query = substr($get_points_query, 0, count($get_points_query) - 3);


  $points_result = $db->query($get_points_query);
  while ($row = mysqli_fetch_assoc($points_result))
    $points[] = $row;

  array_push($points, $point_begin[0]);
  array_unshift($points, $point_end[0]);
  $heights = [];

  foreach ($points as $key => $value) {
    if ($key < count($points) - 1) {
      $height = calculate_height($value, $points[$key + 1], $point[0]);
      array_push($heights, $height);
      // if ($height < $min_height) {
      //   $min_height = $height;
      // }
    }
  }
  $min_height = min($heights);
  return $min_height;
}

function calculate_height($p1, $p2, $p3)
{
  $l1 = calculate_length($p1, $p2);
  $l2 = calculate_length($p3, $p1);
  $l3 = calculate_length($p3, $p2);
  if (round($l2 + $l3, 5) === round($l1, 5)) {
    return 0;
  }
  if ($l1 > 0) {
    return $l2 * $l3 / $l1;
  } else return 0;
}

function calculate_length($p1, $p2)
{
  $result = sqrt(pow($p1['longitude'] - $p2['longitude'], 2) + pow($p1['latitude'] - $p2['latitude'], 2));
  return $result;
}
if (isset($_POST['function'])) {
  switch ($_POST['function']) {
    case 'add_new_vehicle':
      echo add_new_vehicle($_POST['reg_plate'], $_POST['color']);
      break;
    case 'add_new_route':
      echo add_new_route($_POST['list'], $_POST['vehicle_id']);
      break;

    case 'add_gps_point':
      echo add_gps_point($_POST['point'], $_POST['vehicle_id'], $_POST['arc_id']);
      break;

    case 'calculate_deviation':
      echo calculate_deviation($_POST['point_id'], $_POST['arc_id']);
      break;

    default:
      echo false;
      break;
  }
}
