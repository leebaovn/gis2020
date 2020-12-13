<?php 
  function node(){
    $db = new mysqli("localhost","root","","deviation");
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
  $db = new mysqli("localhost","root","","deviation");
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
  $db = new mysqli("localhost", "root", "", "deviation");
  if ($db -> connect_errno) {
    return "DATABASE CONNECT ERROR";
  }
  $db -> set_charset("utf8");
  $last = array_pop($list);

  $result = $db -> query("SELECT * FROM arc WHERE node_begin_id=$list[0] AND node_end_id=$last");
  $new_arc = mysqli_fetch_assoc($result);
  $arc_id = $new_arc['id'];

  $sql = '';
  foreach($list as $key=>$value) {
    if ($key > 0 && $key < count($list) - 1) {
      $sql .= "INSERT INTO arc_point (Arc_id, Point_id, Sequence) VALUES ($arc_id, $value, $key);";
    }
  }
  $arc_point_result = $db -> multi_query($sql);
  header('Content-type: application/json');
  return json_encode($arc_point_result);
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