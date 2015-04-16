<?php
session_start();
include('../includes/sqlconnect.php');
header('Content-Type: application/json');
$userid = $_SESSION['UserID'];

$filters = array();
if($_GET['transType'] !== null)
	$filters[] = "systypes.typename like '%" . $_GET['transType'] . "%'";
if($_GET['dateFrom'] !== null)
	$filters[] = "transdate >= '" . substr($_GET['dateFrom'], 0, 10) . "'";
if($_GET['dateTo'] !== null)
	$filters[] = "transdate <= '" . substr($_GET['dateTo'], 0, 10) . "'";

$filters = sizeof($filters) > 0 ? "WHERE " . join(" AND ", $filters) : "";

$rows = $_GET['rows'] ?: 10;
$page = $_GET['page'] ?: 1;
$start = ($page - 1) * $rows;


$sql="SELECT 	banktrans.currcode,
					banktrans.amount,
					banktrans.amountcleared,
					banktrans.functionalexrate,
					banktrans.exrate,
					banktrans.banktranstype,
					banktrans.transdate,
					banktrans.transno,
					banktrans.ref,
					bankaccounts.bankaccountname,
					systypes.typename,
					systypes.typeid
				FROM banktrans
				INNER JOIN bankaccounts
				ON banktrans.bankact=bankaccounts.accountcode
				INNER JOIN systypes
				ON banktrans.type=systypes.typeid
				$filters
				ORDER BY banktrans.transdate desc
				LIMIT $start, $rows";

$transactions = mysql_query_select($sql);
echo json_encode($transactions);

?>