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
                            <h2>Data Pengeluaran Obat</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <a href="transobatout.php" class="btn btn-primary"><i class="fa fa-plus-square fa-lg"></i> Trans. Pengeluaran Obat</a>
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
                                          <th class="column-title">Unit Penerima</th>
                                          <th class="column-title">Tot. Item</th>
                                          <th class="column-title">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql="SELECT `kdtrans`, `nmsat`, sum(`qty`) as `total` "
                                        . "FROM `tobatout` "
                                        . "INNER JOIN `tsatelit` ON `tsatelit`.`kdsat` = `tobatout`.`kdsat"
                                        . "group by `kdtrans`";
                                        $result=$db->query($sql);
                                        if($result){
                                            while ($row = $result->fetch_row(MYSQLI_ASSOC)) {
                                                $notrans=$row['kdtrans'];
                                                ?>
                                        <tr>
                                            <td><?=$row['kdtrans'];?></td>
                                            <td><?=$row['nmsat'];?></td>
                                            <td><?=$row['total'];?></td>
                                            <td>
                                                <a href="transobatin.php?kdtransout=<?=$notrans;?>" data-toggle="tooltip" title="Edit"><i class="fa fa-edit fa-lg"></i></a> &nbsp;
						<a href="#" onclick="showdetail(<?=$notrans?>)" data-toggle="tooltip" title="Detail Transaksi"><i class="fa fa-th-list fa-lg"></i></a>
                                            </td>
                                        </tr>
                                        <?php
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
<script>
function showdetail(notrans) {
	$.ajax({
	type:"post",
	url	:"ajaxobatout.php",
	data:"notrans="+notrans,
	success:function(data){
		$("#detail").html(data);
		$('#modaldetail').modal('show');
	}
	});
}
</script>
</body>
</html>