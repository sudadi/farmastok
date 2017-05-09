<?php
   //include('dbconfig.php');
   session_start();
   
   if(!isset($_SESSION['login_user'])){
		header("location:login.php");
   } else {
   		$user_check = $_SESSION['login_user'];
   		$result = mysqli_query($db, "select userid from tuser where userid = '$user_check'");
   		//$row = mysqli_fetch_array($result);
  		//$login_session = $row['user'];
  		if (($result->num_rows == 0) || (isset($_SESSION['tgl']) && $_SESSION['tgl'] < date('Y/m/d'))){
			// last request was more than 30 minutes ago
			session_unset();     // unset $_SESSION variable for the run-time 
			session_destroy();   // destroy session data in storage
			header("location:login.php");
		} 
		$_SESSION['LAST_ACTIVITY'] = time();
		$_SESSION['tgl'] = date('Y/m/d');
   }
?>