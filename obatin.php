<!DOCTYPE html>
<html lang="en">

<?php include('head.php');

$tgltrans = date("Y-m-d");
    
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if(isset($_POST['filtgl'])) $tgltrans=date('Y-m-d', strtotime($_POST['filtgl']));                                           
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
                  <h2>Data Penerimaan Obat</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form action="transobatin.php" method="post">
                        <input type="hidden" name="new" value="true">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> Trans. Penerimaan Obat</button>
                    </form> 
                  <br /><br />
                  <form action="obatin.php" method="post" data-parsley-validate class="form-horizontal form-label-left">
                    <div class="form-group">
                        <label for="filtgl" class="control-label col-sm-2">Filter Tanggal</label>
                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <input type="date" name="filtgl" id="filtgl" class="form-control" value="<?=$tgltrans;?>" required="required">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </form>
	          	  <div class="table-responsive">
                  	<table class="table table-striped jambo_table bulk_action">
                      <thead>
                        <tr class="headings">
                          <th class="column-title">Kode Trans</th>
                          <th class="column-title">Nama Supplier</th>
                          <th class="column-title">Tgl Trans.</th>
                          <th class="column-title">Tot. Item</th>
                          <th class="column-title">Opsi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        	$sql = "SELECT kdtrans, tgltrans, sum(qty) as total, nmsupp FROM tsupp INNER JOIN tobatin ON tsupp.kdsupp = tobatin.kdsupp where tobatin.tgltrans='$tgltrans' GROUP BY kdtrans";
                        	$result = $db->query($sql);
                            $n=1;
                        	if ($result) {
								while($row = $result->fetch_array(MYSQL_ASSOC)){
                                    $notrans=$row['kdtrans'];
									echo "<tr>";
									echo "<td>".$row['kdtrans']."</td>";
									echo "<td>".$row['nmsupp']."</td>";
									echo "<td>".$row['tgltrans']."</td> ";
									echo "<td>".$row['total']."</td>";
									echo "<td><a href='transobatin.php?edit=$notrans' data-toggle='tooltip' title='Edit'><i class='fa fa-edit'></i></a> &nbsp";
									echo "<a href='transobatin.php?hapus=$notrans' data-toggle='tooltip' title='Hapus' onClick='return confirm(\"Yakin menghapus data tersebut?\")'><i class='fa fa-trash'></i></a> ";
									echo "</tr>";								
                                    $n++;
								}								
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