<div class="col-md-3 left_col menu_fixed">
  <div class="left_col scroll-view">
	<div class="navbar nav_title" style="border: 0;">
	  <a href="index.php" class="site_title"><i class="fa fa-th-large"></i> <span>FarmaStok</span></a>
	</div>

	<div class="clearfix"></div>

	<!-- menu profile quick info 
	<div class="profile clearfix">
	  <div class="profile_pic">
		<img src="images/user.png" alt="..." class="img-circle profile_img">
	  </div>
	  <div class="profile_info">
		<span>Welcome,</span>
		<h2><?php //echo $_SESSION['nama']; ?></h2>
	  </div>
	</div>
	 /menu profile quick info -->

	<br />

	<!-- sidebar menu -->
	<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
	  <div class="menu_section">
		<ul class="nav side-menu">
		  <li><a href="index.php"><i class="fa fa-home"></i> Beranda </a> </li>
		  <li><a href="obatin.php"><i class="fa fa-plus-square-o"></i> Penerimaan Obat</a></li>
		  <li><a href="obatout.php"><i class="fa fa-minus-square-o"></i> Pengeluaran Obat</a></li>
		  <li><a><i class="fa fa-line-chart"></i> Laporan <span class="fa fa-chevron-down"></span></a>
		  	  <ul class="nav child_menu">
		  	  	<li><a href="lapin.php">Laporan Penerimaan</a></li>
		  	  	<li><a href="lapout.php">Laporan Pengeluaran</a></li>
		  	  	<li><a href="lapstok.php">Laporan Persediaan</a></li>
		  	  </ul>
		  </li>
		  <li><a><i class="fa fa-cogs"></i> Setting <span class="fa fa-chevron-down"></span></a>
			  <ul class="nav child_menu">
			  	<li><a href="dataobat.php">Data Obat </a></li>
			  	<li><a href="datasupp.php">Data Supplier </a></li>
			  	<li><a href="datasat.php">Apotik Satelit </a></li>
			  	<li><a href="ubahsandi.php">Ubah Password </a></li>
			  </ul>
		  </li>	                  
		</ul>
	  </div>

	</div>
	<!-- /sidebar menu -->

	<!-- /menu footer buttons -->
	<div class="sidebar-footer hidden-small">
	  <a data-toggle="tooltip" data-placement="top" title="Settings">
		<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
	  </a>
	  <a data-toggle="tooltip" data-placement="top" title="FullScreen">
		<span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
	  </a>
	  <a data-toggle="tooltip" data-placement="top" title="Lock">
		<span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
	  </a>
	  <a data-toggle="tooltip" data-placement="top" title="Logout" href="logout.php">
		<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
	  </a>
	</div>
	<!-- /menu footer buttons -->
  </div>
</div>
