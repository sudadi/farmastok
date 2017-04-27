<!DOCTYPE html>
<html lang="en">

<?php include('head.php');

$today = date("Ymd");
$newnum = 1;
$sql = "SELECT max(id) AS last, kdtrans FROM tobatin";
$result=$db->query($sql);
if ($result){
    $row=$result->fetch_array(MYSQLi_ASSOC);
    $lastcode=$row['kdtrans'];
    $lastnum=$subtr($lastcode, 8, 3);
    $newnum=$lastnum+1;
}
$newcode = $today.sprintf('%03s', $newnum);
    
if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_POST[])) ;
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
                  <h2>Data Pengeluaran Obat</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <a href="transobatout.php" class="btn btn-primary"><i class="fa fa-plus-square"></i> Trans. Pengeluaran Obat</a>
	          	  <br /><br />
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