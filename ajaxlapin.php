<?php
include('dbconfig.php');
include('session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['bln']) && $_POST['bln'] != '' && isset($_POST['tahun']) && $_POST['tahun'] != '') { 
		$bln=$_POST['bln']; 
		$tahun=$_POST['tahun'];
		?>
		<table class="table jambo_table bulk_action">
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
				$sql="select kdtrans, tgltrans, tsupp.kdsupp, tsupp.nmsupp from tobatin inner join tsupp on tobatin.kdsupp=tsupp.kdsupp where year(tgltrans)='$tahun' and month(tgltrans)='$bln' group by kdtrans";
				$hasil=$db->query($sql);
				if ($hasil) {
				  while($baris = $hasil->fetch_array(MYSQLI_ASSOC)) {
					$kdtrans=$baris['kdtrans'];
					$sql="SELECT tobatin.qty, tobat.kdobat, tobat.nmobat FROM tobatin INNER JOIN tobat on tobatin.kdobat=tobat.kdobat WHERE kdtrans='$kdtrans'";
					$result=$db->query($sql);
						$n=$result->num_rows+1;
					echo "<tr><td rowspan='".$n."'>".$kdtrans."</td>";
					echo "<td colspan='3'> Supplier : ".$baris['nmsupp']."  Tgl. : ".$baris['tgltrans']."</td></tr>";
					while($row=$result->fetch_array(MYSQLI_ASSOC)) {
					?>
					  <tr>
						<td><?=$row['kdobat'];?></td>
						<td><?=$row['nmobat'];?></td>
						<td><?=$row['qty'];?></td>
					  </tr>
					<?php
					}
				  }
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