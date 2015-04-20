<?php
	session_start();
	$userid = $_SESSION['UserID'];
	include('../includes/sqlconnect.php');
	header('Content-Type: application/json');

	$typeCounter = new stdClass;
	$typeCounter->stock = mysql_query_single("
		SELECT count(stockmaster.stockid) count
			FROM stockmaster INNER JOIN locstock
			ON stockmaster.stockid=locstock.stockid
			INNER JOIN locations
			ON locstock.loccode = locations.loccode
			INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='$userid' AND locationusers.canview=1
			WHERE locstock.quantity <= stockmaster.shrinkfactor
	")->count;



	$dateInterval = "DATE_SUB(curdate(), INTERVAL 1 MONTH)";
	$products = "
		SELECT locstock.quantity,
			ABS(SUM(IF(stockmoves.qty >= 0, 0, stockmoves.qty))) sold 
		FROM stockmaster 
			INNER JOIN stockcategory ON stockcategory.categoryid = stockmaster.categoryid
			INNER JOIN locstock ON locstock.stockid = stockmaster.stockid
			INNER JOIN locationusers ON locationusers.loccode=locstock.loccode AND locationusers.userid='admin' AND locationusers.canview=1 	
			INNER JOIN stockmoves ON stockmoves.stockid = stockmaster.stockid 
		WHERE (locstock.quantity > 0)
			AND stockmoves.trandate > $dateInterval 					 
		GROUP BY stockmaster.stockid 
		HAVING (sold / locstock.quantity * 100) < 30
	";
	$typeCounter->products = mysql_query_single("
		SELECT count(*) count FROM ($products) p
	")->count;


	$credits="
		SELECT debtorsmaster.debtorno, 
			SUM(debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc) AS fxbalance
		FROM debtorsmaster 
			INNER JOIN currencies ON debtorsmaster.currcode = currencies.currabrev 
			INNER JOIN debtortrans ON debtorsmaster.debtorno = debtortrans.debtorno 
		GROUP BY debtorsmaster.debtorno, debtorsmaster.name, currencies.currency, currencies.decimalplaces
		HAVING fxbalance > 0";
	$typeCounter->credits = mysql_query_single("
		SELECT count(*) count FROM ($credits) c
	")->count;


	echo json_encode($typeCounter);
?>