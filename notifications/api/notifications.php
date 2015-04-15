<?php
	session_start();
	$userid = $_SESSION['UserID'];
	include('../includes/sqlconnect.php');
	header('Content-Type: application/json');

	

	$notifications = query_object("
		select id, notificationTypeId, message, dateAndTime
		from notification
		order by dateAndTime desc
		limit 10;
	");


	echo json_encode($notifications);
?>