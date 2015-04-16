<?php
	session_start();
	$userid = $_SESSION['UserID'];
	include('../includes/sqlconnect.php');
	header('Content-Type: application/json');

	$currentCount = $_GET['current'];

	$feed = new stdClass;

	$feed->count = mysql_query_single("
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
	")->count;

	$limit = $feed->count - $currentCount;

	$feed->notifications = mysql_query_select("
		select *
		from notification
		where dateAndTime >=
			ifnull((
				select dateAndTime
				from notificationCheck
				where userId = '$userid'
				limit 1
			), dateAndTime)
		order by dateAndTime desc
		limit $limit;
	");

	$notifications = array();
	foreach($feed->notifications as $nt){
		if($nt->userId !== $userid)
			$notifications[] = $nt;
	}
	$feed->notifications = $notifications;
	


	echo json_encode($feed);
?>