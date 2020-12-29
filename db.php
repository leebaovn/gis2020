<?php 
  function getArc(){
    $db = new mysqli("localhost","root","","deviation_plus");
    // $db->query("SET NAMES utf8");
    if ($db -> connect_errno) {
      return 'DATABASE CONNECT ERROR';
    }
    $db -> set_charset("utf8");
    $result = $db -> query("SELECT * FROM arc");
    while($row = mysqli_fetch_assoc($result))
      $arc[] = $row;  
    header('Content-type: application/json');
    return json_encode($arc);
}

function add_new_vehicle($reg_plate, $color){
  $db = new mysqli("localhost","root","","deviation_plus");
  // $db->query("set names utf8");
  if ($db -> connect_errno) {
    return 'DATABASE CONNECT ERROR';
  }
  $db -> set_charset("utf8");
  $new_vehicle = $db -> query("INSERT INTO vehicle (registration_plate, color) VALUES('$reg_plate','$color')");
  header('Content-type: application/json');
  return json_encode($new_vehicle);
  // return $db -> error;
}

function add_new_arc($list) {
  $db = new mysqli("localhost", "root", "", "deviation_plus");
  if ($db -> connect_errno) {
    return "DATABASE CONNECT ERROR";
  }
  $db -> set_charset("utf8");

  $points = [];
  $add_points_query = 'INSERT INTO point (longitude, latitude) VALUES';
  foreach($list as $key=>$value) {
    $point = explode(',', $value);
    $db -> query($add_points_query."($point[0], $point[1])");
    array_push($points, $db -> insert_id);
  }

  $last = array_pop($points);

  $db -> query("INSERT INTO arc (point_begin_id, point_end_id) VALUES($points[0], $last)");
  $arc_id = $db -> insert_id;

  $add_arc_point_query = 'INSERT INTO arc_point (arc_id, point_id, sequence) VALUES ';
  foreach($points as $key=>$value) {
    if ($key > 0) {
      $add_arc_point_query .= "($arc_id, $value, $key),";
    }
  }

  $add_arc_point_query=substr($add_arc_point_query, 0, strlen($add_arc_point_query) - 1);
  $add_arc_point_result = $db -> query($add_arc_point_query);
  header('Content-type: application/json');
  return json_encode($add_arc_point_result);
  // return $db -> error;
  // return $sql;
}

  if (isset($_POST['function'])) {
    switch ($_POST['function']) {
      case 'node':
        echo node();
        break;
      case 'add_new_vehicle':
        echo add_new_vehicle($_POST['reg_plate'], $_POST['color']);
        break;
      case 'add_new_arc':
        echo add_new_arc($_POST['list']);
        break;
      default:
        echo false;
        break;
    }
  }
?>