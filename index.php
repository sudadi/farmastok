<!DOCTYPE html>
<html lang="en">

<?php include('head.php');

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
              <img src="images/banner.jpg" class="img-rounded img-responsive"></img>
              </div>
            </div><br />
            <div class="row">
                <div class="col-lg-3 col-md-4 col-xs-6">
                    <div class="x_panel">
                        <div class="x_title text-center">
                            <b>Laporan Penerimaan</b>
                        </div>
                        <div class="x_content">
                            <a href="lapin.php"><img class="img-responsive img-rounded" src="images/in.png"></img></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-xs-6">
                    <div class="x_panel">
                        <div class="x_title text-center">
                            <b>Laporan Pengeluaran</b>
                        </div>
                        <div class="x_content">
                            <a href="lapout.php"><img class="img-responsive img-rounded" src="images/out.png"></img></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-xs-6">
                    <div class="x_panel">
                        <div class="x_title text-center">
                            <b>Persediaan Obat</b>
                        </div>
                        <div class="x_content">
                            <a href="lapstok.php"><img class="img-responsive img-rounded" src="images/stok.png"></img></a>
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