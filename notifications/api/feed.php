<?php
	session_start();
	$userid = $_SESSION['UserID'];
	include('../includes/sqlconnect.php');
	header('Content-Type: application/json');

	$feed = new stdClass;

	$feed->notifications = mysql_query_select("
		select id, notificationTypeId, message, dateAndTime
		from notification
		where dateAndTime >=
			ifnull((
				select dateAndTime
				from notificationCheck
				where userId = '$userid'
				limit 1
			), dateAndTime)
		order by dateAndTime desc
		limit 3;
	");

	$feed->count = mysql_query_single("
		select count(*) count
		from notification
		where dateAndTime >=
			ifnull((
				select dateAndTime
				from notificationCheck
				where userId = '$userid'
				limit 1
			), dateAndTime);
	")->count;


	echo json_encode($feed);
?>