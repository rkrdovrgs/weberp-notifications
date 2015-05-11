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
		case 'stock':
			$orderno = $_GET['orderNumber'];
			$stock = mysql_query_select("
				SELECT sm.stockid,
					sm.description,
					ls.quantity
				FROM stockmaster sm
					INNER JOIN locstock ls ON sm.stockid=ls.stockid
					INNER JOIN locations l ON ls.loccode = l.loccode
					INNER JOIN locationusers lu ON lu.loccode=l.loccode AND lu.userid='$userid' AND lu.canview=1
					INNER JOIN salesorderdetails sod ON sod.stkcode=sm.stockid
						AND sod.orderno=$orderno
				WHERE ls.quantity <= sm.shrinkfactor
			");

			$userid = '$$system$$';

			foreach($stock as $st) {
				$productDescription = $st->description;
				$msg = "Product \"$productDescription\" low in stock after sale order #$orderno";
				mysql_query_exec("
					insert into notification (notificationTypeId, message, userId) 
					values($typeId, '$msg', '$userid')
				");
			}

			break;
		case 'transactions':
			$transactionType = $_GET['transactionType'];
			$amount = $_GET['amount'];
			$msg = "$userid registered a new bank transaction. $transactionType of $amount";
			
			mysql_query_exec("
				insert into notification (notificationTypeId, message, userId) 
				values($typeId, '$msg', '$userid')
			");
			break;
	}
	
	


	$myfile = fopen("notifications.txt", "w") or die("Unable to open file!");
	$txt = time();
	fwrite($myfile, $txt);
	fclose($myfile);
	echo $txt;
?>