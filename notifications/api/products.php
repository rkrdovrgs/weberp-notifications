<?php
session_start();
include('../includes/sqlconnect.php');
header('Content-Type: application/json');
$userid = $_SESSION['UserID'];

$filters = array();
if($_GET['description'] !== null)
	$filters[] = "stockmaster.description like '%" . $_GET['description'] . "%'";
if($_GET['category'] !== null)
	$filters[] = "stockcategory.categorydescription like '%" . $_GET['category'] . "%'";
if($_GET['quantityFrom'] !== null)
	$filters[] = "locstock.quantity >= '" . $_GET['quantityFrom'] . "'";
if($_GET['quantityTo'] !== null)
	$filters[] = "locstock.quantity <= '" . $_GET['quantityTo'] . "'";

$filters = sizeof($filters) > 0 ? "AND " . join(" AND ", $filters) : "";

$rows = $_GET['rows'] ?: 10;
$page = $_GET['page'] ?: 1;
$start = ($page - 1) * $rows;

$dateInterval = "DATE_SUB(curdate(), INTERVAL 1 MONTH)";
$sql="SELECT stockmaster.stockid, 
			stockmaster.description, 
			stockmaster.units,
			stockmaster.categoryid,
			stockcategory.categorydescription,
			locstock.quantity,
			max(stockmoves.trandate) lasttrandate,
			ABS(SUM(IF(stockmoves.qty >= 0, 0, stockmoves.qty))) sold
		FROM stockmaster 
			INNER JOIN stockcategory ON stockcategory.categoryid = stockmaster.categoryid
			INNER JOIN locstock ON locstock.stockid = stockmaster.stockid
			INNER JOIN locationusers ON locationusers.loccode=locstock.loccode AND locationusers.userid='admin' AND locationusers.canview=1 	
			INNER JOIN stockmoves ON stockmoves.stockid = stockmaster.stockid 
		WHERE (locstock.quantity > 0)
			AND stockmoves.trandate > $dateInterval 
			$filters					 
		GROUP BY stockmaster.stockid 
		HAVING ((sold / locstock.quantity * 100) < 30)
		ORDER BY stockmaster.stockid
		LIMIT $start, $rows";

$collection = mysql_query_select($sql);
echo json_encode($collection);

?>