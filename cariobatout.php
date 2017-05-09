<?php
include ('dbconfig.php');
include ('session.php');
//CREDENTIALS FOR DB

//LET'S INITIATE CONNECT TO DB

//CREATE QUERY TO DB AND PUT RECEIVED DATA INTO ASSOCIATIVE ARRAY
if(!empty($_POST['field']) && !empty($_POST['name_startsWith'])){
	$name = $_POST['name_startsWith'];
	if ($_POST['field'] == 'kd'){
		$query = "SELECT tobat.kdobat, tobat.nmobat, tobat.limitstok, tobat.hjual, sum(tobatin.sisa) as jml FROM tobat INNER JOIN tobatin on tobat.kdobat=tobatin.kdobat WHERE tobat.kdobat LIKE '%$name%' group by tobat.kdobat order by tobat.kdobat";
	} elseif ($_POST['field'] == 'nm'){
		$query = "SELECT tobat.kdobat, tobat.nmobat, tobat.limitstok, tobat.hjual, sum(tobatin.sisa) as jml FROM tobat INNER JOIN tobatin on tobat.kdobat=tobatin.kdobat WHERE tobat.nmobat LIKE '%$name%' group by tobat.kdobat order by tobat.kdobat";
	} 	
	$result = $db->query($query);
	$data = array();
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$limit = '';
		$stok = "Persediaan :".$row['jml'];
		if ($row['jml'] < $row['limitstok']) {
			$limit = "Limit : ".$row['limitstok'];
		}
		$name = $row['kdobat'].'|'.$row['nmobat'].'|'.$stok.'|'.$limit.'|'.$row['hjual'];
		array_push($data, $name);
	}	
	echo json_encode($data);exit;
}

?>