<!DOCTYPE html>
<html lang="en">

<?php include('head.php');
    $editrow=null;
    $edit = $kdobat = $nmobat = $satuan = $limitstok = $hjual = $hbeli =null;
	
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['kdobat'])) $kdobat=cek_input($_POST['kdobat']);
        if (isset($_POST['nmobat'])) $nmobat=cek_input($_POST['nmobat']);
        if (isset($_POST['satuan'])) $satuan=cek_input($_POST['satuan']);
        if (isset($_POST['limit'])) $limitstok=cek_input($_POST['limit']);
        if (isset($_POST['hbeli'])) $hbeli=cek_input($_POST['hbeli']);
        if (isset($_POST['hjual'])) $hjual=cek_input($_POST['hjual']);
        if (isset($_POST['edit'])) $edit=cek_input($_POST['edit']);
        unset($_POST);
        if($kdobat !='' || $nmobat !='' || $satuan !='' || $limitstok >= 0){
            if ($edit != ''){	
                $sql = "update tobat set nmobat='$nmobat', satuan='$satuan', limitstok='$limitstok', hbeli='$hbeli', hjual='$hjual' where kdobat='$kdobat' ";
                $db->query($sql);

            } else {
                $sql="insert into tobat (kdobat, nmobat, satuan, limitstok, hbeli, hjual) values ('$kdobat','$nmobat','$satuan', '$limitstok','$hbeli','$hjual') ";
                $db->query($sql);			
            }
            if ($db->affected_rows < 0) {
                $errmsg = "Error: Update data obat gagal!";
                $msg->error($errmsg, 'dataobat.php');
            } else {
                $msg->success('Update data berhasil!', 'dataobat.php');
            }
        } 
    }else {
        if (isset($_GET["edit"])){
            $edit = $_GET["edit"];
            $sql = "select * from tobat where id=$edit";
            $result = $db->query($sql);
            if ($db->affected_rows > 0) {
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {	
                    $kdobat=$row['kdobat'];
                    $nmobat=$row['nmobat'];
                    $satuan=$row['satuan'];
                    $limitstok=$row['limitstok'];
                    $hjual=$row['hjual'];
                    $hbeli=$row['hbeli'];
                }		
            }
        }else if (isset($_GET["hapus"])) {
            $hapus = $_GET["hapus"];
            $sql = "delete from tobat where id=$hapus";
            if (mysqli_query($db, $sql)){
                $msg->success('Data Sudah di Hapus!', 'dataobat.php');	
            } else {
                $errmsg="Error: Gagal menghapus data.";
                $msg->error($errmsg);
            }
        }
    }
	

?>

 <body class="nav-md">
    <div class="container body">
      <div class="main_container">
<?php 
	include('topnav.php'); 
	include('sidemenu.php');
?>
		<!-- page content -->
        <div class="right_col" role="main">
		  <div class="">
            <!--<div class="page-title">
              <div class="title_left">
                <h3>Data Obat</h3>
              </div>
            </div> -->
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <?php $msg->display();?>
                    <h2>Simpan Data Obat</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form data-parsley-validate class="form-horizontal form-label-left" action="dataobat.php" method="post">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kdobat">Kode Obat<span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text" id="kdobat" name="kdobat" required="required" class="form-control col-md-12 col-xs-12" placeholder="Kode Obat" <?=$edit ? 'value="'.$kdobat.'" readonly':'';?> />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nmobat">Nama Obat<span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" id="nmobat" name="nmobat" required="required" class="form-control col-md-12 col-xs-12" placeholder="Nama Obat" value="<?=$nmobat;?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Satuan<span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text" id="satuan" name="satuan" required="required" class="form-control col-md-12 col-xs-12" placeholder="Satuan" value="<?=$satuan;?>">
                        </div>
                      
                        <label for="limit" class="control-label col-md-3 col-sm-3 col-xs-12">Limit Stok<span class="required">*</span></label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                          <input id="limit" class="form-control col-md-12 col-xs-12" type="number" name="limit" placeholder="0" required="required" value="<?=$limitstok;?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hbeli">Harga Beli<span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="number" id="hbeli" name="hbeli" required="required" class="form-control col-md-12 col-xs-12" placeholder="0" value="<?=$hbeli;?>">
                        </div>
                      
                        <label for="hjual" class="control-label col-md-3 col-sm-3 col-xs-12">Harga Jual<span class="required">*</span></label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input id="limit" class="form-control col-md-12 col-xs-12" type="number" name="hjual" placeholder="0" required="required" value="<?=$hjual;?>">
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
                          <input type="hidden" name="edit" value="<?=$edit;?>"/>
						  <a href="dataobat.php" class="btn btn-primary">Reset</a>
                          <button type="submit" class="btn btn-success"><?=$edit ? 'Update' : 'Simpan';?></button>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Data Obat</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                	<div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title"># </th>
                            <th class="column-title">Nama Obat</th>
                            <th class="column-title">Satuan </th>
                            <th class="column-title">Limit </th>
                            <th class="column-title">Hrg Beli </th>
                            <th class="column-title">Hrg Jual </th>
                            <th class="column-title">Opsi</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $n = 1;
                        $sql ="select * from tobat";
                        $result=mysqli_query($db, $sql);
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                			$id=$row['id'];
                        ?>
                          <tr class="<?=($n%2)==0 ? 'even ':'odd ';?> pointer">
                            <td class=" "><?php echo $row['kdobat'];?></td>
                            <td class=" "><?php echo $row['nmobat'];?></td>
                            <td class=" "><?php echo $row['satuan'];?></td>
                            <td class=" "><?php echo $row['limitstok'];?></td>
                            <td class="a-right a-right ">Rp. <?php echo $row['hbeli'];?></td>
                            <td class="a-right a-right ">Rp. <?php echo $row['hjual'];?></td>
                            <td class=" ">
                            <a href="<?php echo 'dataobat.php?edit='.$id;?>" data-toggle="tooltip" title="Edit"><span class="fa fa-edit fa-lg"></span></a> &nbsp
                            <a href="<?php echo 'dataobat.php?hapus='.$id;?>" data-toggle="tooltip" title="Hapus"><span class="fa fa-trash fa-lg" onClick="return confirm('Yakin menghapus data tersebut?')"></span></a>
                            </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                      </table>                    
                    </div>
                  </div>
				</div>
              </div>
		  </div>
	    </div>
		<?php include('footer.php'); ?>
	</div>
<?php include('footerjs.php'); ?>
</body>
</html>