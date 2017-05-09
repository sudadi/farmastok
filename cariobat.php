<?php
include ('dbconfig.php');
include ('session.php');
//CREDENTIALS FOR DB

//LET'S INITIATE CONNECT TO DB

//CREATE QUERY TO DB AND PUT RECEIVED DATA INTO ASSOCIATIVE ARRAY
if(!empty($_POST['field']) && !empty($_POST['name_startsWith'])){
	if ($_POST['field'] == 'kd'){
		$field = 'kdobat';
	} elseif ($_POST['field'] == 'nm'){
		$field = 'nmobat';
	} 
	$name = $_POST['name_startsWith'];
	$query = "SELECT kdobat, nmobat, hbeli FROM tobat WHERE $field LIKE '$name%' order by kdobat";
	$result = $db->query($query);
	$data = array();
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$name = $row['kdobat'].'|'.$row['nmobat'].'|'.$row['hbeli'];
		array_push($data, $name);
	}	
	echo json_encode($data);exit;
}

?>