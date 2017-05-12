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
                        <form action="ajaxlapstok.php" method="post" class="form-horizontal form-label-left" data-stok>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Filter</label>
                                <div class="col-md-3">
                                  <select class="form-control col-sm-12" name="filter">
                                      <option selected="selected" value="0">Semua</option>
                                      <option value="1">Dibawah Limit</option>
                                      <!--<option value="2">Mendekati E.D</option>-->
                                  </select>
                                </div>
                                <div class="col-sm-1 col-xs-12">&nbsp;</div>
                                <div class="col-sm-3 col-md-offset-0 col-xs-12 col-xs-offset-4">
                                <button type="submit" class="btn btn-primary">Tampil</button>
                                <button class="btn btn-primary" onclick="window.print()">Print</button>
                                </div>
                            </div>
                        </form>
                        <div class="x_content">
                        <br />
                            <div class="table-responsive" id="report">
				<div class="text-center"><b>Pilih jenis filter lalu klik tampil</b></div>
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
	  $('form[data-stok]').autosubmit();
	});
</script>
</body>
</html>