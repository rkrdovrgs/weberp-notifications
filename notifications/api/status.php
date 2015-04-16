<?php
session_start();
include('../includes/sqlconnect.php');
header('Content-Type: application/json');

$userid = $_SESSION['UserID'];
$status = mysql_query_single("
		select count(*) count
		from notification
		where dateAndTime >=
			ifnull((
				select dateAndTime
				from notificationCheck
				where userId = '$userid'
				limit 1
			), dateAndTime)
			and userId <> '$userid';
	");

echo json_encode($status);

?>