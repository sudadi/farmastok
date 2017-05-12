<?php
include('dbconfig.php');
include('session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['filter']) && $_POST['filter'] != '') { 
	$filter=$_POST['filter'];
    } ?>
<table class="table table-striped jambo_table bulk_action">
    <thead>
      <tr class="headings">
            <th class="column-title">No. </th>
            <th class="column-title">Nama Obat</th>
            <th class="column-title">Persediaan</th>
            <th class="column-title">Satuan</th>
            <th class="column-title">Limit</th>
      </tr>
    </thead>
    <tbody>
        <?php 
        if($filter == 1){
            $sql="SELECT * FROM (select tobat.kdobat, tobat.nmobat, tobat.limitstok, tobat.satuan, sum(tobatin.sisa) as jml from tobatin inner join tobat on tobatin.kdobat=tobat.kdobat group by tobat.kdobat) as stok WHERE stok.jml < stok.limitstok";
        }else {
            $sql="select tobat.kdobat, tobat.nmobat, tobat.limitstok, tobat.satuan, sum(sisa) as jml from tobatin inner join tobat on tobatin.kdobat=tobat.kdobat group by tobat.kdobat";
        }
        $result=$db->query($sql);
        if ($result) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
              ?>
              <tr>
              <td><?=$row['kdobat'];?></td>
              <td><?=$row['nmobat'];?></td>
              <td><?=$row['jml'];?></td>
              <td><?=$row['satuan'];?></td>
              <td><?=$row['limitstok'];?></td>
              </tr>
            <?php
            }
        } 
        }?>
    </tbody>
</table>