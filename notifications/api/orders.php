<?php
session_start();
include('../includes/sqlconnect.php');
header('Content-Type: application/json');
$userid = $_SESSION['UserID'];

$filters = array();
if($_GET['client'] !== null)
	$filters[] = "debtorsmaster.name like '%" . $_GET['client'] . "%'";
if($_GET['branch'] !== null)
	$filters[] = "custbranch.brname like '%" . $_GET['branch'] . "%'";
if($_GET['dateFrom'] !== null)
	$filters[] = "salesorders.orddate >= '" . substr($_GET['dateFrom'], 0, 10) . "'";
if($_GET['dateTo'] !== null)
	$filters[] = "salesorders.orddate <= '" . substr($_GET['dateTo'], 0, 10) . "'";
if($_GET['pendingOnly'] !== null && $_GET['pendingOnly'] === 'true')
	$filters[] = "salesorderdetails.completed = 0";

$filters = sizeof($filters) > 0 ? "AND " . join(" AND ", $filters) : "";

$rows = $_GET['rows'] ?: 10;
$page = $_GET['page'] ?: 1;
$start = ($page - 1) * $rows;

$sql="SELECT salesorders.orderno, 
			debtorsmaster.name, 
			currencies.decimalplaces AS currdecimalplaces, 
			custbranch.brname, 
			salesorders.customerref, 
			salesorders.orddate, 
			salesorders.deliverto, 
			salesorders.deliverydate, 
			SUM(salesorderdetails.unitprice*salesorderdetails.quantity*(1-salesorderdetails.discountpercent)) AS ordervalue,
			IF(salesorderdetails.completed = 0, null, 'true') completed
		FROM salesorders 
			INNER JOIN salesorderdetails ON salesorders.orderno = salesorderdetails.orderno 
			INNER JOIN debtorsmaster ON salesorders.debtorno = debtorsmaster.debtorno 
			INNER JOIN custbranch ON salesorders.branchcode = custbranch.branchcode AND salesorders.debtorno = custbranch.debtorno 
			INNER JOIN currencies ON debtorsmaster.currcode = currencies.currabrev 
		WHERE salesorders.quotation=0
			$filters
		GROUP BY salesorders.orderno, 
			debtorsmaster.name, 
			currencies.decimalplaces, 
			custbranch.brname, 
			salesorders.customerref, 
			salesorders.orddate, 
			salesorders.deliverydate, 
			salesorders.deliverto 
		ORDER BY salesorders.orddate desc, salesorderdetails.completed
		LIMIT $start, $rows";

$collection = mysql_query_select($sql);
echo json_encode($collection);

?>