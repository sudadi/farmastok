<!DOCTYPE html>
<html lang="en">

<?php include('head.php');
$kdtrans = $noretur = $kdsupp = $nmsupp = $nmobat = $kdobat = '';
$noretur = '094834734';
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
                            <h2>Retur Penerimaan Obat</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <form class="form-horizontal form-label-left" data-parsley-validate method="post" action="returin.php">
                                <div class="form-group">
                                    <div class="visible-xs col-xs-12">
                                        <label class="control-label col-xs-4">No. Retur</label>
                                        <label class="control-label col-xs-8"><?=$noretur;?></label>
                                    </div>
                                    <label class="control-label col-sm-2 col-xs-4" for="kdtrans">Kode Trans</label>
                                    <div class="col-sm-3 col-xs-6">
                                        <input type="text" name="kdtrans" id="kdtrans" required="required" class="form-control col-sm-12" value="<?=$kdtrans;?>" />
                                    </div>
                                    <div class="col-sm-1 col-xs-2 ">
                                        <a href="" class="btn btn-primary">Cari</a>
                                    </div>
                                    <div class="control-label hidden-xs col-sm-3">
                                        <label class="control-label col-sm-12">No. Retur #<?=$noretur;?></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2 col-xs-4" for="kdsupp">Kode Supplier</label>
                                    <div class="col-sm-3 col-xs-6">
                                        <input type="text" name="kdsupp" id="kdsupp" class="form-control" value="<?=$kdsupp;?>" readonly />
                                    </div>
                                    <div class="col-sm-1 col-xs-12"></div>
                                    <label class="control-label col-sm-2 col-xs-4" for="nmsupp">Nama Supplier</label>
                                    <div class="col-sm-3 col-xs-8">
                                        <input type="text" name="nmsupp" id="nmsupp" class="form-control" value="<?=$nmsupp;?>" readonly />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2 col-xs-4" for="kdobat">Kode Obat</label>
                                    <div class="col-sm-3 col-xs-6">
                                        <input type="text" name="kdobat" id="kdobat" class="form-control" value="<?=$kdobat;?>" readonly />
                                    </div>
                                    <div class="col-sm-1 col-xs-12"></div>
                                    <label class="control-label col-sm-2 col-xs-4" for="nmobat">Nama Obat</label>
                                    <div class="col-sm-3 col-xs-8">
                                        <input class="form-control" type="text" name="nmobat" id="nmobat" value="<?=$nmobat;?>" readonly />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2 col-xs-4" for="qty">QTY</label>
                                    <div class="col-sm-2 col-xs-4">
                                        <input type="number" name="qty" id="qty" class="form-control" value="<?=$qty;?>" />
                                    </div>
                                </div>
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-xs-offset-3 col-sm-offset-5">
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                        <a href="returin.php?selesai=true" class="btn btn-warning">Selesai</a>
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