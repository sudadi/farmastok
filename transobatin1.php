<!DOCTYPE html>
<html lang="en">

<?php include('head.php');
$kdtrans = $kdsupp = $nmsupp = $kdobat = $nmobat = $edit = '' ;
$today = date("Ymd");
$nobaru = 1;
$qty = 0;
$result=nul;

if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['new'])) {
        if (isset($_SESSION['transobatin'])){
	       $kdtrans=$_SESSION['transobatin'];
        }else {
            $sql = "SELECT max(kdtrans) AS last FROM tobatin where kdtrans like '$today%'";
            $result=$db->query($sql);
            if ($result){
                $row=$result->fetch_array(MYSQLI_ASSOC);
                $lastkd=$row['last'];
                $lastno=substr($lastkd, 8, 3);
                $nobaru=$lastno+1;
                $result->close();
            }
            $kdtrans = $today.sprintf('%03s', $nobaru);
            $_SESSION['transobatin'] = $kdtrans;
        }
    } else {
        if($_POST['edit'] != NULL) $edit = $_POST['edit'];
        if($_POST['kdtrans'] != NULL) $kdtrans=$_POST['kdtrans'];
        if($_POST['kdsupp'] != NULL) $kdsupp = cek_input ($_POST['kdsupp']);
        if($_POST['nmsupp'] != NULL) $nmsupp = cek_input ($_POST['nmsupp']);
        if($_POST['kdobat'] != NULL) $kdobat = cek_input($_POST['kdobat']);
        if ($_POST['nmobat'] != NULL) $nmobat = cek_input($_POST['nmobat']);
        if ($_POST['qty'] != NULL) $qty = cek_input ($_POST['qty']);
        
        if($kdtrans!='' || $kdsupp!='' || $nmsupp!='' || $kdobat!='' || $nmobat!='' || $qty<1){
            $_SESSION['transobatin'] = $kdtrans;
            $_SESSION['kdsupp'] = $kdsupp;
            $_SESSION['nmsupp'] = $nmsupp;
            if($edit != '') {
                $sql = "update tobatin set kdsupp='$kdsupp', kdobat='$kdobat', qty='$qty' where id='$edit'";
                $db->query($sql);
            } else {
                $sql="select id from tobatin where kdtrans='$kdtrans' and kdobat='$kdobat' ";
                $result=$db->query($sql);
                if ($result && $result->num_rows >0){
                    $id=$result->fetch_array(MYSQLI_ASSOC)['id'];
                    $sql = "update tobatin set qty=qty+'$qty' where id='$id'"; 
                    $db->query($sql);
                } else {
                    $sql="INSERT INTO `tobatin` (`kdtrans`, `kdsupp`, `tgltrans`, `kdobat`, `qty`) values ('$kdtrans', '$kdsupp', CURRENT_DATE, '$kdobat', '$qty')";
                    $db->query($sql);
                }                
            }
            if ($db->affected_rows < 0) {
                echo 'Error';
                $errmsg = "Error: " . $sql . "<br>" . mysqli_error($db);
                $msg->error($errmsg, 'transobatin.php');
            } else {
                $msg->success('Update data berhasil!', 'transobatin.php');
            }
        }
    }
} else if($_SERVER['REQUEST_METHOD'] === "GET"){
    if(isset($_GET['edit']) && $_GET['edit'] != ''){
        $edit = cek_input($_GET['edit']);
        if(isset($_GET['kdtrans']) && $_GET['kdtrans'] !=''){
            $kdtrans=cek_input($_GET['kdtrans']);
            
        } else {
            $sql="select * from tobatin where id=$edit";
            $result=$db->query($sql);
            if($db->affected_rows == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $kdtrans=$row['kdtrans'];
                $kdsupp=$row['kdsupp'];
                $kdobat=$row['kdobat'];
                $qty=$row['qty'];
            }
        }
    } elseif (isset ($_GET['hapus']) && $_GET['hapus'] !='') {
        $hapus= cek_input($_GET['hapus']);
        $sql="delete from tobatin where id = '$hapus'";
        $db->query($sql);
        if ($db->affected_row < 0){
            $errmsg = "Error: " . $sql . "<br>" . mysqli_error($db);
            $msg->error($errmsg, 'transobatin.php');
        } else {
            $msg->success('Update data berhasil!', 'transobatin.php');
        }
    } else if (isset($_GET['selesai']) && $_GET['selesai'] = true){
        $unsetvar = array('transobatin', 'kdsupp', 'nmsupp');
        foreach($unsetvar as $var) {
            unset($_SESSION[$var]);
        }
        header('location:obatin.php');
    } else {
        if (isset($_SESSION['transobatin'])){
	       $kdtrans = $_SESSION['transobatin'];
            $kdsupp = $_SESSION['kdsupp'];
            $nmsupp = $_SESSION['nmsupp'];
        } else {
            header("location:obatin.php");
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
                    <?php $msg->display();?>
                    <h2>Input Data Penerimaan Obat</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <form data-parsley-validate class="form-horizontal form-label-left" action="transobatin.php" method="post">
                      <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kdtrans">No.Trans</label>
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
                          <a href="transobatin.php?selesai=true" class="btn btn-warning">Selesai</a>
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
                          <section class="content invoice">
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                  <address>
                                      Supplier : <strong>Iron Admin, Inc.</strong>
                                      <br>795 Freedom Ave, Suite 600
                                      <br>New York, CA 94107
                                  </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                 
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                  <b>Invoice #007612</b>
                                  <b>Order ID:</b> 4F3S8J
                                  <b>Payment Due:</b> 2/22/2014
                                </div>
                                <!-- /.col -->
                            </div>  
                             <div class="table-responsive">
                                <table class="table table-striped jambo_table bulk_action">
                                <thead>
                                <tr class="headings">
                                    <th class="column-title">No</th>
                                  <th class="column-title">Kode Obat</th>
                                  <th class="column-title">Nama Obat</th>
                                  <th class="column-title">Qty</th>
                                  <th class="column-title">Opsi</th>
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
                                        <td><a href="transobatin.php?edit=<?=$id;?>"><i class="fa fa-edit"></i></a>&nbsp;
                                            <a href="transobatin.php?hapus=<?=$id;?>"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                        <?php }
                                    }
                                    ?>
                                </tbody>
                              </table>
                            </div>
                          </section>

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