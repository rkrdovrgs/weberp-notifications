<?php
	session_start();
	$userid = $_SESSION['UserID'];
	include('../includes/sqlconnect.php');
	header('Content-Type: application/json');

	
	$lastChecked = "(select dateAndTime
						from notificationCheck
						where userId = '$userid'
						limit 1
					)";
	$notifications = mysql_query_select("
		select id, notificationTypeId, message, dateAndTime, IF($lastChecked <= dateAndTime, true, false) isNew 
		from notification
		order by dateAndTime desc
		limit 15;
	");


	echo json_encode($notifications);
?>