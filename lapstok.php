<!DOCTYPE html>
<html lang="en">

<?php include('head.php');?>

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
                  <h2>Laporan Persedian Obat</h2>
                  <div class="clearfix"></div>
                </div>
                
                <div class="x_content">
                  <br />
				  <div class="table-responsive">
					  <table class="table table-striped jambo_table bulk_action">
						<thead>
						  <tr class="headings">
							<th class="column-title">No. </th>
							<th class="column-title">Nama Obat</th>
							<th class="column-title">Persediaan</th>
							<th class="column-title">Satuan</th>
							<th class="column-title">Limit</th>
						  </tr>
						</thead>
						<tbody>
							<?php 
							$sql="select tobat.kdobat, tobat.nmobat, tobat.limitstok, tobat.satuan, sum(sisa) as jml from tobatin inner join tobat on tobatin.kdobat=tobat.kdobat group by tobat.kdobat";
							$result=$db->query($sql);
							if ($result) {
								while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
								  ?>
								  <tr>
								  <td><?=$row['kdobat'];?></td>
								  <td><?=$row['nmobat'];?></td>
								  <td><?=$row['jml'];?></td>
								  <td><?=$row['satuan'];?></td>
								  <td><?=$row['limitstok'];?></td>
								  </tr>
								<?php
								}
							} ?>
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