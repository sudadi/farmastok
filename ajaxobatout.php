<?php
include('dbconfig.php');
include('session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['notrans']) && $_POST['notrans'] != '') { 
		$kdtrans=$_POST['notrans']; ?>
		<table class="table table-striped jambo_table bulk_action">
		  <thead>
			<tr class="headings">
			  <th class="column-title">No</th>
			  <th class="column-title">Kode Obat</th>
			  <th class="column-title">Nama Obat</th>
			  <th class="column-title">Qty</th>
			</tr>
			</thead>
			<tbody>
				<?php
				$sql="SELECT tobatin.*, tobat.nmobat FROM tobatin INNER JOIN tobat on tobatin.kdobat=tobat.kdobat WHERE kdtrans='$kdtrans'";
				$result=$db->query($sql);
				if ($result){
					while ($row = $result->fetch_array(MYSQLI_ASSOC)){ 
						$id=$row['id']; ?>
				<tr>
					<td><?=$row['kdtrans'];?></td>
					<td><?=$row['tgltrans'];?></td>
					<td><?=$row['nmobat'];?></td>
					<td><?=$row['qty'];?></td>
				</tr>
					<?php }
				}
				?>
			</tbody>
		</table>
	<?php
	} else {
		echo "<b>Data tidak ditemukan..!</b>";
	}	
}
?>