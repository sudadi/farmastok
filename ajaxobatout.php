<?php
include('dbconfig.php');
include('session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['notrans']) && $_POST['notrans'] != '') { 
		$kdtrans=$_POST['notrans']; ?>
		<table class="table table-striped jambo_table bulk_action">
		  <thead>
			<tr class="headings">
			  <th class="column-title">Kode Obat</th>
			  <th class="column-title">Nama Obat</th>
			  <th class="column-title">Qty</th>
			  <th class="column-title">Jumlah</th>
			</tr>
			</thead>
			<tbody>
				<?php
				$sql="SELECT tobat.kdobat, qty, (qty*tobatout.hjual) as jml, nmobat FROM tobatout INNER JOIN tobat on tobatout.kdobat=tobat.kdobat WHERE kdtrans='$kdtrans'";
				$result=$db->query($sql);
				if ($result){
					while ($row = $result->fetch_array(MYSQLI_ASSOC)){ 
						?>
				<tr>
					<td><?=$row['kdobat'];?></td>
					<td><?=$row['nmobat'];?></td>
					<td><?=$row['qty'];?></td>
				    <td><?=$row['jml'];?></td>
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