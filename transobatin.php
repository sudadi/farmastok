<!DOCTYPE html>
<html lang="en">

<?php include('head.php');
$kdtrans = $kdsupp = $nmsupp = $almsupp = $kdobat = $nmobat = $edit = $onlyread = $hide ='' ;
$qty=$hrg=0;
function newtrans(){
	global $db;
	$today = date("Ymd");
    $sql = "SELECT max(kdtrans) AS last FROM tobatin where kdtrans like '$today%'";
    $result=$db->query($sql);
    if ($result){
        $row=$result->fetch_array(MYSQLI_ASSOC);
        $lastkd=$row['last'];
        $lastno=substr($lastkd, 8, 3);
        $nobaru=$lastno+1;
        $notrans = $today.sprintf('%03s', $nobaru);
        $result->close();
    }
    return $notrans;
}

function getsession(){
    global $kdtrans, $kdsupp, $nmsupp;
    $kdtrans=$_SESSION['kdtrans'];
    $kdsupp=$_SESSION['kdsupp'];
    $nmsupp=$_SESSION['nmsupp'];
    $almsupp=$_SESSION['almsupp'];
}

function setsession(){
    global $kdtrans, $kdsupp, $nmsupp, $almsupp;
    $_SESSION['kdtrans'] = $kdtrans;
    $_SESSION['kdsupp'] = $kdsupp;
    $_SESSION['nmsupp'] = $nmsupp;
    $_SESSION['almsupp'] = $almsupp;
}

function unsetsession() {
	$unsetvar = array('kdtrans', 'kdsupp', 'nmsupp', 'almsupp');
    foreach($unsetvar as $var) {
        unset($_SESSION[$var]);
    }
}

function pesan(){
	global $db, $msg;
	if ($db->affected_rows < 0) {
        $errmsg = "Error: " . $sql . "<br>" . mysqli_error($db);
        $msg->error($errmsg, 'transobatin.php');
        return(TRUE);
    } else {
        $msg->success('Data berhasil proses!', 'transobatin.php');
    	return(FALSE);
    }
}

if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['new'])) {
    	if (isset($_SESSION['kdtrans'])){
	       getsession();
        }else {
			$kdtrans=newtrans();
		}
	} else {
		if(isset($_POST['edit'])) $edit = $_POST['edit'];
        if(isset($_POST['kdtrans'])) $kdtrans=$_POST['kdtrans'];
        if(isset($_POST['kdsupp'])) $kdsupp = cek_input ($_POST['kdsupp']);
        if(isset($_POST['nmsupp'])) $nmsupp = cek_input ($_POST['nmsupp']);
		if(isset($_POST['almsupp'])) $almsupp = cek_input($_POST['almsupp']);
        if(isset($_POST['kdobat'])) $kdobat = cek_input($_POST['kdobat']);
        if (isset($_POST['nmobat'])) $nmobat = cek_input($_POST['nmobat']);
        if (isset($_POST['qty'])) $qty = cek_input ($_POST['qty']);
        if (isset($_POST['hrg'])) $hrg = cek_input ($_POST['hrg']);
        
        if($kdtrans!='' && $kdsupp!='' && $nmsupp!='' && $kdobat!='' && $nmobat!='' && $qty>0 && $hrg>0){
        	if ($edit !=''){ //jika edit data
				$sql = "update tobatin set kdsupp='$kdsupp', kdobat='$kdobat', qty='$qty', hbeli='$hrg' where id='$edit'";
                $db->query($sql);
			}else { //insert data 
				$sql="INSERT INTO `tobatin` (`kdtrans`, `kdsupp`, `tgltrans`, `kdobat`, `qty`, `hbeli`) values ('$kdtrans', '$kdsupp', CURRENT_DATE, '$kdobat', '$qty', '$hrg') ON DUPLICATE KEY UPDATE qty = qty+$qty, hbeli='$hrg'";
                $db->query($sql);
			}
			setsession();
            pesan();
        }
	}
}else {
	if(isset($_GET['kdtrans']) && $_GET['kdtrans'] !=''){
        $kdtrans=cek_input($_GET['kdtrans']);
		$sql="SELECT tsupp.* FROM tsupp INNER JOIN tobatin ON tsupp.kdsupp = tobatin.kdsupp  WHERE kdtrans ='$kdtrans' GROUP BY kdtrans"; 
		$result=$db->query($sql);
		if ($result->num_rows == 1){
			$row=$result->fetch_array(MYSQLI_ASSOC);
			$kdsupp=$row['kdsupp'];
			$nmsupp=$row['nmsupp'];
			$almsupp=$row['almsupp'];
			setsession();	
		}
    } else if(isset($_GET['edit']) && $_GET['edit'] != ''){
        $edit = cek_input($_GET['edit']);
        $sql="select tobat.kdobat, nmobat, tobat.hbeli, qty from tobatin inner join tobat on tobatin.kdobat=tobat.kdobat where tobatin.id='$edit'";
        $result=$db->query($sql);
        if($result->num_rows == 1){
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $kdobat=$row['kdobat'];
            $nmobat=$row['nmobat'];
            $qty=$row['qty'];
            $hrg=$row['hrg'];
		}
    } elseif (isset ($_GET['hapus']) && $_GET['hapus'] !='') {
        $hapus= cek_input($_GET['hapus']);
        $sql="delete from tobatin where id = '$hapus'";
        $db->query($sql);
        pesan();
    } else if (isset($_GET['selesai']) && $_GET['selesai'] = true){
        unsetsession();
        header('location:obatin.php');
    }
	if (isset($_SESSION['kdtrans'])) {
    	getsession();	
    }else{
        header("location:obatin.php");
    }   
}

if (isset($_SESSION['kdsupp']) && $_SESSION['kdsupp']!=''){
	$onlyread='readonly';
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
                          <input type="text" id="kdtrans" name="kdtrans" required="required" class="form-control col-md-12" value="<?=$kdtrans;?>" readonly/>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kdsupp">Kode Supplier</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <input type="text" id="kdsupp" name="kdsupp" data-type="kd_supp" class="form-control col-md-12 autocomplete_txt" required="required" placeholder="Kode Supplier" value="<?=$kdsupp;?>" <?=$onlyread;?> autocomplete="off"/>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nmsupp">Nama Supplier</label>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <input type="text" id="nmsupp" name="nmsupp" data-type="nm_supp" class="form-control col-md-12 autocomplete_txt" required="required" placeholder="Nama supplier" value="<?=$nmsupp;?>" <?=$onlyread;?> autocomplete="off"/>
                        </div>                        
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kdobat">Kode Obat</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <input type="text" id="kdobat" name="kdobat" data-type="kd_obat" class="form-control col-md-12 autocomplete_txt" required="required" placeholder="Kode Obat" value="<?=$kdobat;?>" autocomplete="off"/>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nmobat">Nama Obat</label>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <input type="text" id="nmobat" name="nmobat" data-type="nm_obat" class="form-control col-md-12 autocomplete_txt" required="required" placeholder="Nama Obat" value="<?=$nmobat;?>" autocomplete="off"/>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="qty">Jumlah</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <input type="number" id="qty" name="qty" class="form-control col-md-12" required="required" value="<?=$qty;?>" />
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="hrg">Jumlah</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <input type="number" id="hrg" name="hrg" class="form-control col-md-12" required="required" value="<?=$hrg;?>" />
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
                          <input type="hidden" name="edit" value="<?=$edit;?>"/>
						  <input type="hidden" id="almsupp" name="almsupp" value="<?=$almsupp;?>"> 
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
                                      Supplier : <strong><?=$nmsupp;?></strong>
                                      <br><?=$almsupp?>
                                  </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                 
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                  <b>No. Trans: #<?=$kdtrans;?></b><br />
                                  <b>Tgl Trans: </b><?=date_format(date_create(substr($kdtrans, 0, 8)),"Y/m/d");?> <br /><br />
                                </div>
                                <!-- /.col -->
                            </div>  
                             <div class="table-responsive">
                                <table class="table table-striped jambo_table bulk_action">
                                <thead>
                                <tr class="headings">
                                  <th class="column-title">Kode Obat</th>
                                  <th class="column-title">Nama Obat</th>
                                  <th class="column-title">Qty</th>
                                  <th class="column-title">Jumlah</th>
                                  <th class="column-title">Opsi</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql="SELECT tobatin.id, tobatin.kdobat, qty, (qty*tobatin.hbeli) as jml, tobat.nmobat FROM tobatin INNER JOIN tobat on tobatin.kdobat=tobat.kdobat WHERE kdtrans='$kdtrans'";
                                    $result=$db->query($sql);
                                    if ($result){
                                        while ($row = $result->fetch_array(MYSQLI_ASSOC)){ 
                                            $id=$row['id']; ?>
                                    <tr>
                                        <td><?=$row['kdobat'];?></td>
                                        <td><?=$row['nmobat'];?></td>
                                        <td><?=$row['qty'];?></td>
                                        <td><?=$row['jml'];?></td>
                                        <td><a href="transobatin.php?edit=<?=$id;?>" data-toggle="tooltip" title="Edit"><i class="fa fa-edit fa-lg"></i></a>&nbsp;
                                            <a href="transobatin.php?hapus=<?=$id;?>" data-toggle="tooltip" title="Hapus" onClick="return confirm('Yakin menghapus data tersebut?')"><i class="fa fa-trash fa-lg"></i></a>
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
    
    <!-- Modal Cari Supplier-->
	<div id="modalcarisupp" class="modal fade modalcarisupp" role="dialog">
	  <div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Detail Disposisi</h4>
			</div>
			<div class="modal-body">
				<div class="table-responsive" id="detail">
				
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	  </div>
	</div>
	<!-- //Modal Cari -->
	
            <?php include('footer.php'); ?>
    </div>
</div>
<?php include('footerjs.php'); ?>
</body>
</html>