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
              <th class="column-title">Satuan</th>
            </tr>
            </thead>
            <tbody>
                <?php
                $sql="select kdtrans, tgltrans, tsatelit.kdsat, tsatelit.nmsat from tobatout inner join tsatelit on tobatout.kdsat=tsatelit.kdsat where year(tgltrans)='$tahun' and month(tgltrans)='$bln' group by kdtrans";
                $hasil=$db->query($sql);
                if ($hasil) {
                  while($baris = $hasil->fetch_array(MYSQLI_ASSOC)) {
                    $kdtrans=$baris['kdtrans'];
                    $sql="SELECT tobatout.qty, tobat.kdobat, tobat.nmobat, tobat.satuan FROM tobatout INNER JOIN tobat on tobatout.kdobat=tobat.kdobat WHERE kdtrans='$kdtrans'";
                    $result=$db->query($sql);
                    $n=$result->num_rows+1;
                    echo "<tr><td rowspan='".$n."'>".$kdtrans."</td>";
                    echo "<td colspan='4' class='well well-sm'> Depo/Satelit : ".$baris['nmsat']."&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;  Tgl. : ".$baris['tgltrans']."</td></tr>";
                    while($row=$result->fetch_array(MYSQLI_ASSOC)) {
                    ?>
                      <tr>
                        <td><?=$row['kdobat'];?></td>
                        <td><?=$row['nmobat'];?></td>
                        <td><?=$row['qty'];?></td>
                        <td><?=$row['satuan'];?></td>
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