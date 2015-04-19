<?php
	session_start();
	$userid = $_SESSION['UserID'];
	include('../includes/sqlconnect.php');
	header('Content-Type: application/json');

	$typeCounter = new stdClass;
	$typeCounter->stock = mysql_query_single(
		"SELECT count(stockmaster.stockid) count
			FROM stockmaster INNER JOIN locstock
			ON stockmaster.stockid=locstock.stockid
			INNER JOIN locations
			ON locstock.loccode = locations.loccode
			INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='$userid' AND locationusers.canview=1
			WHERE locstock.quantity <= stockmaster.shrinkfactor"
	)->count;


	echo json_encode($typeCounter);
?>