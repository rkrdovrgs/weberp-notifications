<?php
session_start();
include('../includes/sqlconnect.php');
header('Content-Type: application/json');
$userid = $_SESSION['UserID'];

$filters = array();
$havingFilters = array();
if($_GET['client'] !== null)
	$filters[] = "debtorsmaster.name like '%" . $_GET['client'] . "%'";
if($_GET['dateFrom'] !== null)
	$havingFilters[] = "duedate >= '" . substr($_GET['dateFrom'], 0, 10) . "'";
if($_GET['dateTo'] !== null)
	$havingFilters[] = "duedate <= '" . substr($_GET['dateTo'], 0, 10) . "'";

$filters = sizeof($filters) > 0 ? "WHERE " . join(" AND ", $filters) : "";
$havingFilters = sizeof($havingFilters) > 0 ? "AND " . join(" AND ", $havingFilters) : "";

$rows = $_GET['rows'] ?: 10;
$page = $_GET['page'] ?: 1;
$start = ($page - 1) * $rows;

$sql="SELECT debtorsmaster.debtorno, 
			debtorsmaster.name, 
			currencies.currency, 
			currencies.decimalplaces, 
			SUM((debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc)/debtortrans.rate) AS balance, 
			SUM(debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc) AS fxbalance, 
			SUM(IF(debtortrans.ovamount > 0, debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc, 0)) fxbalances_output, 
			SUM(IF(debtortrans.ovamount < 0, debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc, 0)) fxbalances_input, 
			SUM(CASE WHEN debtortrans.prd > '1' THEN (debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount)/debtortrans.rate ELSE 0 END) AS afterdatetrans, 
			SUM(CASE WHEN debtortrans.prd > '1' AND (debtortrans.type=11 OR debtortrans.type=12) THEN debtortrans.diffonexch ELSE 0 END) AS afterdatediffonexch, 
			SUM(CASE WHEN debtortrans.prd > '1' THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount ELSE 0 END ) AS fxafterdatetrans,
			MAX(IF(debtortrans.ovamount > 0, debtortrans.inputdate, NULL)) lastinputdate,
			debtorsmaster.paymentterms,
			DATE_ADD(MAX(IF(debtortrans.ovamount > 0, debtortrans.inputdate, NULL)),INTERVAL debtorsmaster.paymentterms DAY) duedate
		FROM debtorsmaster 
			INNER JOIN currencies ON debtorsmaster.currcode = currencies.currabrev 
			INNER JOIN debtortrans ON debtorsmaster.debtorno = debtortrans.debtorno 
		$filters
		GROUP BY debtorsmaster.debtorno, debtorsmaster.name, currencies.currency, currencies.decimalplaces
		HAVING fxbalance > 0 
			$havingFilters
		LIMIT $start, $rows";

$collection = mysql_query_select($sql);
echo json_encode($collection);

?>