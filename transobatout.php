<!DOCTYPE html>
<html lang="en">

<?php include('head.php');
$kdtrans = $kdsat = $nmsat = $kdobat = $nmobat = $edit = $onlyread ='' ;
$qty = $hrg = 0;
function newtrans(){
    global $db;
    $today = date("Ymd");
    $sql = "SELECT max(kdtrans) AS last FROM tobatout where kdtrans like '$today%'";
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
    global $kdtrans, $kdsat, $nmsat;
    $kdtrans=$_SESSION['kdtransout'];
    $kdsat=$_SESSION['kdsat'];
    $nmsat=$_SESSION['nmsat'];
}

function setsession(){
    global $kdtrans, $kdsat, $nmsat;
    $_SESSION['kdtransout'] = $kdtrans;
    $_SESSION['kdsat'] = $kdsat;
    $_SESSION['nmsat'] = $nmsat;
   
}

function unsetsession() {
	$unsetvar = array('kdtransout', 'kdsat', 'nmsat');
    foreach($unsetvar as $var) {
        unset($_SESSION[$var]);
    }
}

function pesan(){
	global $db, $msg;
	if ($db->affected_rows < 0) {
        $errmsg = "Error: " . $sql . "<br>" . mysqli_error($db);
        $msg->error($errmsg, 'transobatout.php');
        return(TRUE);
    } else {
        $msg->success('Data berhasil proses!', 'transobatout.php');
    	return(FALSE);
    }
}

if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['new'])) {
    	if (isset($_SESSION['kdtransout'])){
            getsession();
        }else {
            $kdtrans=newtrans();
        }
    } else {
        if(isset($_POST['edit'])) $edit = $_POST['edit'];
		if(isset($_POST['hrg'])) $hrg = $_POST['hrg'];
        if(isset($_POST['kdtrans'])) $kdtrans=$_POST['kdtrans'];
        if(isset($_POST['kdsat'])) $kdsat = cek_input ($_POST['kdsat']);
        if(isset($_POST['nmsat'])) $nmsat = cek_input ($_POST['nmsat']);
        if(isset($_POST['kdobat'])) $kdobat = cek_input($_POST['kdobat']);
        if (isset($_POST['nmobat'])) $nmobat = cek_input($_POST['nmobat']);
        if (isset($_POST['qty'])) $qty = cek_input ($_POST['qty']);

        if($kdtrans!='' && $kdsat!='' && $nmsat!='' && $kdobat!='' && $nmobat!='' && $qty>0 && $hrg>0){
            if ($edit !=''){ //jika edit data
                $sql = "update tobatout set kdsat='$kdsat', kdobat='$kdobat', qty='$qty', hjual='$hrg' where id='$edit'";
                $db->query($sql);
            }else { //insert data 
                $sql="INSERT INTO tobatout (kdtrans, kdsat, kdobat, tgltrans, qty, hjual, opt) values ('$kdtrans', '$kdsat', '$kdobat', curdate(), '$qty', '$hrg', '') ON DUPLICATE KEY UPDATE qty = qty+$qty";
                $db->query($sql);
            }
            setsession();
            pesan();
        }
    }
}else {
    if(isset($_GET['kdtrans']) && $_GET['kdtrans'] !=''){
        $kdtrans=cek_input($_GET['kdtrans']);
        $sql="SELECT tsatelit.* FROM tsatelit INNER JOIN tobatout ON tsatelit.kdsat = tobatout.kdsat  WHERE kdtrans ='$kdtrans' GROUP BY kdtrans"; 
        $result=$db->query($sql);
        if ($result->num_rows == 1){
            $row=$result->fetch_array(MYSQLI_ASSOC);
            $kdsat=$row['kdsat'];
            $nmsat=$row['nmsat'];
            setsession();	
        }
    } else if(isset($_GET['edit']) && $_GET['edit'] != ''){
        $edit = cek_input($_GET['edit']);
        $sql="select tobat.kdobat, tobat.nmobat, qty, tobatout.hjual from tobatout inner join tobat on tobatout.kdobat=tobat.kdobat where tobatout.id='$edit'";
        $result=$db->query($sql);
        if($result->num_rows == 1){
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $kdobat=$row['kdobat'];
            $nmobat=$row['nmobat'];
            $qty=$row['qty'];
            $hrg=$row['hjual'];
            
	}
    } elseif (isset ($_GET['hapus']) && $_GET['hapus'] !='') {
        $hapus= cek_input($_GET['hapus']);
        $sql="delete from tobatout where id = '$hapus'";
        $db->query($sql);
        pesan();
    } else if (isset($_GET['selesai']) && $_GET['selesai'] = true){
        unsetsession();
        header('location:obatout.php');
    }
    if (isset($_SESSION['kdtransout'])) {
    	getsession();	
    }else{
        header("location:obatout.php");
    }   
}

if (isset($_SESSION['kdsat']) && $_SESSION['kdsat']!=''){
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
                    <h2>Input Data Pengeluaran Obat</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <form data-parsley-validate class="form-horizontal form-label-left" action="transobatout.php" method="post">
                      <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kdtrans">No.Trans</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                          <input type="text" id="kdtrans" name="kdtrans" required="required" class="form-control col-md-12" value="<?=$kdtrans;?>" readonly/>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kdsat">Kode Depo</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <input type="text" id="kdsat" name="kdsat" data-type="kd_sat" class="form-control col-md-12 autocomplete_txt" required="required" placeholder="Kode Depo" value="<?=$kdsat;?>" <?=$onlyread;?> autocomplete="off"/>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nmsat">Nama Depo</label>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <input type="text" id="nmsat" name="nmsat" data-type="nm_sat" class="form-control col-md-12 autocomplete_txt" required="required" placeholder="Nama supplier" value="<?=$nmsat;?>" <?=$onlyread;?> autocomplete="off"/>
                        </div>                        
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kdobat">Kode Obat</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <input type="text" id="kdobat" name="kdobat" data-type="kd_obatout" class="form-control col-md-12 autocomplete_txt" required="required" placeholder="Kode Obat" value="<?=$kdobat;?>" autocomplete="off"/>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nmobat">Nama Obat</label>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <input type="text" id="nmobat" name="nmobat" data-type="nm_obatout" class="form-control col-md-12 autocomplete_txt" required="required" placeholder="Nama Obat" value="<?=$nmobat;?>" autocomplete="off"/>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="qty">Jumlah</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <input type="number" id="qty" name="qty" class="form-control col-md-12" required="required" value="<?=$qty;?>"/>
                        </div>
						<label class="control-label col-md-2 col-sm-2 col-xs-12" for="hrg">Harga</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <input type="number" id="hrg" name="hrg" class="form-control col-md-12" required="required" value="<?=$hrg;?>"/>
                        </div>
						<label id="stok" class="control-label col-sm-2 text-success"></label>
						<label id="limit" class="control-label col-sm-2 text-danger blink"></label>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
                          <input type="hidden" name="edit" value="<?=$edit;?>"/>
			  <input type="hidden" id="stokval" value="0">
                          <button type="submit" id="simpan" class="btn btn-success"><?=$edit ? 'Update' : 'Simpan';?></button>
                          <a href="transobatout.php?selesai=true" class="btn btn-warning">Selesai</a>
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
                                      Depo : <strong><?=$nmsat;?></strong>
                                      <br>
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
                                    $sql="SELECT tobatout.id, tobat.kdobat, qty, (qty*tobatout.hjual) as jml, nmobat FROM tobatout INNER JOIN tobat on tobatout.kdobat=tobat.kdobat WHERE kdtrans='$kdtrans'";
                                    $result=$db->query($sql);
                                    if ($result){
                                        while ($row = $result->fetch_array(MYSQLI_ASSOC)){ 
                                            $id=$row['id']; ?>
                                    <tr>
                                        <td><?=$row['kdobat'];?></td>
                                        <td><?=$row['nmobat'];?></td>
                                        <td><?=$row['qty'];?></td>
					<td><?=$row['jml'];?></td>
                                        <td><!--<a href="transobatout.php?edit=<?//=$id;?>" data-toggle="tooltip" title="Edit"><i class="fa fa-edit fa-lg"></i></a>&nbsp;-->
                                            <a href="transobatout.php?hapus=<?=$id;?>" onClick="return confirm('Yakin menghapus data tersebut?')" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash fa-lg"></i></a>
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
    
    <!-- Modal Cari Depo-->
	<div id="modalcarisupp" class="modal fade modalcarisupp" role="dialog">
	  <div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Detail Transaksi Distribusi Obat</h4>
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
<script>
$(document).ready(function() {
	window.setInterval(function(){
	  $('.blink').toggle();
	}, 300);
	
	$(function () {
        $('#qty').keyup(function () {
			var y = parseInt($('#stokval').val());
			console.log(y);
            if ($(this).val() < 1 || y < $(this).val()) {
                $(':submit').prop('disabled', true);
            } else {
                $('#simpan').prop('disabled', false);
            }
        });
    });
});
</script>
</body>
</html>