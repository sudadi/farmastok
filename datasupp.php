<!DOCTYPE html>
<html lang="en">

<?php include('head.php');
$edit = $kdsupp = $nmsupp = NULL;

if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
	if (isset($_POST['kdsupp'])) $kdsupp=cek_input($_POST['kdsupp']);
	if (isset($_POST['nmsupp'])) $nmsupp=cek_input($_POST['nmsupp']);
	if (isset($_POST['edit'])) $edit=cek_input($_POST['edit']);
	
	unset($_POST);
	if ($edit){
		$sql="update tsupp set kdsupp=$kdsupp, nmsupp=$nmsupp where id='$edit'; ";
		$db->query($sql);
	} else {
		$sql="insert into tsupp (kdsupp, nmsupp) values ('$kdsupp', '$nmsupp'); ";
		$db->query($sql);
	}
	if ($db->affected_rows > 0){
		$errmsg = "Error: Update data obat gagal!";
		$msg->error($errmsg, 'datasupp.php');
	} else {
		$msg->success('Update data berhasil!', 'datasupp.php');
	}
} else {
	if (isset($_GET['edit'])) {
		$edit=$_GET['edit'];
		$sql = "select * from tsupp where id =$edit ";
		$result= $db->query($sql);
		if ($db->affected_rows > 0){
			while($row = $result->fetch_array(MYSQL_ASSOC)){
				$kdsupp = $row['kdsupp'];
				$nmsupp = $row['nmsupp'];
			}			
		}
	} else if (isset($_GET['hapus'])){
		$hapus = $_GET['hapus'];
		$sql = "delete from tsupp where id=$hapus ";
		$db->query($sql);
		if ($db->affected_rows > 0){
			//$msg->success('Data Sudah di Hapus!', 'datasupp.php');
		} else {
			$errmsg="Error: " . $sql . "<br>" . mysqli_error($db);
			$msg->error($errmsg);
		}
	}
	
		//unset($_GET);
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
                    <h2>Update Data Supplier</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form data-parsley-validate class="form-horizontal form-label-left" action="datasupp.php" method="post">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kdsuppt">Kode Supplier<span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text" id="kdsupp" name="kdsupp" required="required" class="form-control col-md-12 col-xs-12" placeholder="Kode Supplier" <?=$edit ? 'value="'.$kdsupp.'"':'';?> />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nmsuppt">Nama Supplier<span class="required">*</span>
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                          <input type="text" id="nmsupp" name="nmsupp" required="required" class="form-control col-md-12 col-xs-12" placeholder="Nama Supplier" value="<?=$nmsupp;?>" />
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
                          <input type="hidden" name="edit" value="<?=$edit;?>"/>
						  <a href="datasupp.php" class="btn btn-primary">Reset</a>
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
                    <h2>Data Supplier</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                	<div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">Kode </th>
                            <th class="column-title">Nama Supplier</th>
                            <th class="column-title">Opsi</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $n = 1;
                        $sql ="select * from tsupp";
                        $result=mysqli_query($db, $sql);
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                			$id=$row['id'];
                        ?>
                          <tr class="<?=($n%2)==0 ? 'even ':'odd ';?> pointer">
                            <td class=" "><?php echo $row['kdsupp'];?></td>
                            <td class=" "><?php echo $row['nmsupp'];?></td>
                            <td class=" ">
                            <a href="<?php echo 'datasupp.php?edit='.$id;?>" data-toggle="tooltip" title="Edit"><span class="fa fa-edit"></span></a> &nbsp
                            <a href="<?php echo 'datasupp.php?hapus='.$id;?>" data-toggle="tooltip" title="Hapus"><span class="fa fa-trash" onClick="return confirm('Yakin menghapus data tersebut?')"></span></a>
                            </td>
                            </tr>
                        <?php $n++; } ?>
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