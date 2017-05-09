<!DOCTYPE html>
<html lang="en">

<?php include('head.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (isset($_POST['oldpass'])) $oldpass=cek_input($_POST['oldpass']);
	if (isset($_POST['newpass1'])) $newpass1=cek_input($_POST['newpass1']);
	if (isset($_POST['newpass2'])) $newpass2=cek_input($_POST['newpass2']);
	
	if ($oldpass && $newpass1 && $newpass1==$newpass2){
		$userid = $_SESSION['login_user'];
		$sql = "select * from tuser where userid='$userid' and passwd='$oldpass';";
		$db->query($sql);
		if ($db->affected_rows == 1) {
			$sql="update tuser set passwd='$newpass1' where userid='$userid'";
			$db->query($sql);
			if ($db->affected_rows == 1){
				$msg->success('Update data berhasil!', 'ubahsandi.php');
			}
		} else {
			$errmsg = "Error: Update password gagal!";
			$msg->error($errmsg, 'ubahsandi.php');
		}
	} else {
		$errmsg = "Error: Pastikan data di isi dengan benar!";
		$msg->error($errmsg, 'ubahsandi.php');
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
                    <h2>Update Password</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form data-parsley-validate class="form-horizontal form-label-left" action="ubahsandi.php" method="post">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="oldpass">Password Lama<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="password" id="oldpass" name="oldpass" required="required" class="form-control col-md-12 col-xs-12" placeholder="Password Lama" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="newpass1">Password Baru<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="password" id="newpass1" name="newpass1" required="required" class="form-control col-md-12 col-xs-12" placeholder="Password Baru" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="newpass2">Ulangi Password Baru<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="password" id="newpass2" name="newpass2" required="required" class="form-control col-md-12 col-xs-12" placeholder="Ketik Ulang Password Baru" />
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
						  <button type="reset" class="btn btn-primary">Reset</button>
                          <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                      </div>
                    </form>
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