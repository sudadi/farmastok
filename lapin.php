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
                  <h2>Laporan Penerimaan Obat</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                <br />
                <form action="ajaxlapin.php" method="post" class="form-horizontal form-label-left" data-obatin>
                  <div class="form-group">
                    <label for="bln" class="control-label col-sm-2 col-xs-4">Bulan</label>
                    <div class="col-sm-3 col-xs-8">
                      <select class="form-control col-sm-12" name="bln" id="bln">
                        <option selected="selected">Pilih Bulan</option>
                        <?php 
                        for ($i = 0; $i <= 11; ++$i) {
                                 $time = strtotime(sprintf('+%d months', $i));
                                 $value = date('m', $time);
                                 $label = date('F', $time);
                                 if ($bulan == ($i+1)) {
                                         printf('<option value="%s" selected="selected">%s</option>', $value, $label);
                                 } else {
                                         printf('<option value="%s">%s</option>', $value, $label);
                                 }
                         }
                         ?>
                      </select>
                    </div>
                    <label for="tahun" class="control-label col-sm-1 col-xs-4">Tahun</label>
                    <div class="col-sm-2 col-xs-8">
                        <select name="tahun" id="tahun" class="form-control input-sm">
                            <option selected="selected">Tahun</option>
                            <?php 
                                $sql="select year(tgltrans) as tahun from tobatin group by tahun";
                                $result = $db->query($sql);
                                if ($result){
                                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                                        if ($tahun ==  $row['tahun']){
                                            printf('<option value="%s" selected="selected">%s</option>', $row['tahun'], $row['tahun']);
                                        } else {
                                            printf('<option value="%s">%s</option>', $row['tahun'], $row['tahun']);
                                        }
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-1 col-xs-12">&nbsp;</div>
                    <div class="col-sm-3 col-md-offset-0 col-xs-12 col-xs-offset-4">
                        <button type="submit" class="btn btn-primary">Tampil</button>
                   
                        <button class="btn btn-primary" onclick="window.print()">Print</button>
                    </div>
                    </div>
                </form>
                <div class="table-responsive" id="report">
                      <div class="text-center"><b>Pilih bulan dan tahun lalu klik tampil</b></div>
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
(function($) {
    $.fn.autosubmit = function() {
        this.submit(function(event) {
          event.preventDefault();
          var form = $(this);
          $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize()
          }).done(function(data) {
            // Optionally alert the user of success here...
            $("#report").html(data);
          }).fail(function(data) {
            // Optionally alert the user of an error here...
          });
        });
        return this;
    }
})(jQuery);

$(function() {
  $('form[data-obatin]').autosubmit();
});
</script>
</body>
</html>