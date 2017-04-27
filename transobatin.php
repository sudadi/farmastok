<!DOCTYPE html>
<html lang="en">

<?php include('head.php');
$kdtrans = $kdsupp = $nmsupp = $kdobat = $nmobat = $edit = '' ;
$today = date("Ymd");
$nobaru = 1;
$qty = 0;
$err = TRUE;
if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['new'])) {
        $sql = "SELECT max(id) AS last, kdtrans FROM tobatin";
        $result=$db->query($sql);
        if ($result){
            $row=$result->fetch_array(MYSQLI_ASSOC);
            $lastkd=$row['kdtrans'];
            $lastno=substr($lastkd, 8, 3);
            $nobaru=$lastno+1;
        }
        $kdtrans = $today.sprintf('%03s', $nobaru);
    } else {
        if(isset($_POST['edit'])) $edit = $_POST['edit'];
        if(isset($_POST['kdtrans'])) $kdtrans=$_POST['kdtrans'];
        if(isset($_POST['kdsupp'])) $kdsupp = cek_input ($_POST['kdsupp']);
        if(isset($_POST['nmsupp'])) $nmsupp = cek_input ($_POST['nmsupp']);
        if(isset($_POST['kdobat'])) $kdobat = cek_input($_POST['kdobat']);
        if (isset($_POST['nmobat'])) $nmobat = cek_input($_POST['nmobat']);
        if (isset($_POST['qty'])) $qty = cek_input ($_POST['qty']);
        
        if($kdtrans='' || $kdsupp='' || $nmsupp='' || $kdobat='' || $nmobat='' || $qty<1){
            $err = TRUE;
        }
    }
    unset($_POST);
    if(!$err) {
        if($edit != '') {
            $sql = "update tobatin set kdsupp=$kdsupp, kdobat=$kdobat, qty=$qty"
                    . "where id=$edit";
            $db->query($sql);
        } else {
            $sql = "insert into tobatin (kdtrans, kdsupp, tgltrans, kdobat, qty) "
                    . "values ($kdtrans, $kdsupp, CURRDATE(), $kdobat, $qty)";
            $db->query($sql);
        }
        if ($db->affected_rows < 0) {
            $errmsg = "Error: Update data obat gagal!";
            $msg->error($errmsg, 'dataobat.php');
        } else {
            $msg->success('Update data berhasil!', 'dataobat.php');
        }
    } 
} else {
    if(isset($_GET['edit']) && $_GET['edit'] != ''){
        $edit = cek_input($_GET['edit']);
        $sql="select * from tobatin where id=$edit";
        $result=$db->query($sql);
        if($db->affected_row == 1){
            $row = mysql_fetch_array($result, MYSQLI_ASSOC);
            $kdtrans=$row['kdtrans'];
            $kdsupp=$row['kdsupp'];
            $kdobat=$row['kdobat'];
            $nmobat=$row['nmobat'];
            $qty=$row['qty'];
        }
    } elseif (isset ($_GET['hapus']) && $_GET['hapus'] !='') {
        $hapus= cek_input($_GET['hapus']);
        $sql="delete tobatin where id = $hapus";
        $db->query($sql);
        if ($db->affected_row > 0){
            $msg->success('Update data berhasil!', 'dataobat.php');
        } else {
            $errmsg = "Error: Update data obat gagal!";
            $msg->error($errmsg, 'dataobat.php');
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
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Input Data Penerimaan Obat</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <form data-parsley-validate class="form-horizontal form-label-left" action="transobatin.php" method="post">
                      <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kdttrans">No.Trans</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                          <input type="text" id="kdtrans" name="kdtrans" required="required" class="form-control col-md-12" 
                                 value="<?=$kdtrans;?>" readonly/>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kdsupp">Kode Supplier</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <input type="text" id="kdsupp" name="kdsupp" class="form-control col-md-12" required="required" 
                                   placeholder="Kode Supplier" value="<?=$kdsupp;?>" />
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nmsupp">Nama Supplier</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="nmsupp" name="nmsupp" class="form-control col-md-12" required="required"
                                   placeholder="Nama supplier" value="<?=$nmsupp;?>" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kdobat">Kode Obat</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <input type="text" id="kdobat" name="kdobat" class="form-control col-md-12" required="required" 
                                   placeholder="Kode Obat" value="<?=$kdobat;?>" />
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nmobat">Nama Obat</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="nmobat" name="nmobat" class="form-control col-md-12" required="required"
                                   placeholder="Nama Obat" value="<?=$nmobat;?>" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="qty">Jumlah</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <input type="number" id="qty" name="qty" class="form-control col-md-12" required="required" 
                                   value="<?=$qty;?>" />
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
                          <input type="hidden" name="edit" value="<?=$edit;?>"/>
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
                        <div class="x_content">
                             <div class="table-responsive">
                                <table class="table table-striped jambo_table bulk_action">
                                <thead>
                                <tr class="headings">
                                  <th class="column-title">Kode Trans</th>
                                  <th class="column-title">Unit Penerima</th>
                                  <th class="column-title">Tgl Trans.</th>
                                  <th class="column-title">Tot. Item</th>
                                  <th class="column-title">Opsi</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql="select * from tobatin where kdtrans=$kdtrans";
                                    $result=$db->query($sql);
                                    if ($result){
                                        while ($row = $result->fetch_array(MYSQLI_ASSOC)){ 
                                            $id=$row['id']; ?>
                                    <tr>
                                        <td><?=$row['kdtrans'];?></td>
                                        <td><?=$row['tgltrans'];?></td>
                                        <td><?=$row['nmsupp'];?></td>
                                        <td><?=$row['nmobat'];?></td>
                                        <td><?=$row['qty'];?></td>
                                        <td><a href="transobatin.php?edit=<?=$id;?>" class="btn btn-primary">
                                                <i class="fa fa-edit"></i></a>&nbsp;
                                                <a href="transobatin.php?hapus=<?=$id;?>" class="btn btn-danger">
                                                    <i class="fa fa-trash"></i>
                                        </td>
                                    </tr>
                                        <?php }
                                    }
                                    ?>
                                </tbody>
                              </table>
                            </div>


                        </div>
                    </div>
                </div>          
            </div>
        </div>  
    </div>
            <?php include('footer.php'); ?>
    </div>
</div>
<?php include('footerjs.php'); ?>
</body>
</html>