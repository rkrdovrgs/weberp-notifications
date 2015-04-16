<?php
	session_start();
	$userid = $_SESSION['UserID'];
	include('../includes/sqlconnect.php');
	header('Content-Type: application/json');

	

	$lastChecked = mysql_query_single("
		select dateAndTime
		from notificationCheck
		where userId = '$userid'
		limit 1;
	")->dateAndTime;
	
	if($lastChecked === null)
		mysql_query_exec("
			insert into notificationCheck
			select '$userid', CURRENT_TIMESTAMP
		");
	else
		mysql_query_exec("
			update notificationCheck
			set dateAndTime = CURRENT_TIMESTAMP
			where userId = '$userid'
		");
?>