<?php
include ('dbconfig.php');
include ('session.php');
//CREDENTIALS FOR DB

//LET'S INITIATE CONNECT TO DB

//CREATE QUERY TO DB AND PUT RECEIVED DATA INTO ASSOCIATIVE ARRAY
if(!empty($_POST['field']) && !empty($_POST['name_startsWith'])){
	if ($_POST['field'] == 'kd'){
		$field = 'kdsat';
	} elseif ($_POST['field'] == 'nm'){
		$field = 'nmsat';
	} 
	$name = $_POST['name_startsWith'];
	$query = "SELECT * FROM tsat WHERE $field LIKE '%$name%' order by kdsat";
	$result = $db->query($query);
	$data = array();
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$name = $row['kdsat'].'|'.$row['nmsat'];
		array_push($data, $name);
	}	
	echo json_encode($data);exit;
}

?>
