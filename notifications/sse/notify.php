<?php
	session_start();
	$userid = $_SESSION['UserID'];
	include('../includes/sqlconnect.php');
	
	
	$notificationType = $_GET['type'];
	$typeId = mysql_query_single("
		select id from notificationType where name = '$notificationType'
	")->id;
	
	$msg = '';
	switch ($notificationType) {
		case 'transactions':
			$msg = $userid . ' realizo una nueva transaccion bancaria';
			break;
		default:
			//TODO: Check if product is low in stock
			$msg = 'Producto bajo en stock';
			$userid = 'system';
			break;
	}
	
	mysql_query_exec("
		insert into notification (notificationTypeId, message, userId) 
		values($typeId, '$msg', '$userid')
	");
	
	


	$myfile = fopen("notifications.txt", "w") or die("Unable to open file!");
	$txt = time();
	fwrite($myfile, $txt);
	fclose($myfile);
	echo $txt;
?>