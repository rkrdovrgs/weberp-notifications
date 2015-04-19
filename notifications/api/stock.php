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
if($_GET['location'] !== null)
	$filters[] = "locations.locationname like '%" . $_GET['location'] . "%'";
if($_GET['quantityFrom'] !== null)
	$filters[] = "locstock.quantity >= '" . $_GET['quantityFrom'] . "'";
if($_GET['quantityTo'] !== null)
	$filters[] = "locstock.quantity <= '" . $_GET['quantityTo'] . "'";

$filters = sizeof($filters) > 0 ? "AND " . join(" AND ", $filters) : "";

$rows = $_GET['rows'] ?: 10;
$page = $_GET['page'] ?: 1;
$start = ($page - 1) * $rows;


$sql="SELECT stockmaster.stockid,
               stockmaster.description,
               stockmaster.categoryid,
			   stockcategory.categorydescription,
               stockmaster.decimalplaces,
               locstock.loccode,
               locations.locationname,
               locstock.quantity
        FROM stockmaster INNER JOIN locstock
        ON stockmaster.stockid=locstock.stockid
        INNER JOIN locations ON locstock.loccode = locations.loccode
		INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='$userid' AND locationusers.canview=1
		INNER JOIN stockcategory ON stockcategory.categoryid = stockmaster.categoryid
        WHERE locstock.quantity <= stockmaster.shrinkfactor $filters
        ORDER BY locstock.loccode,
			stockmaster.categoryid,
			stockmaster.stockid,
			stockmaster.decimalplaces
		LIMIT $start, $rows";

$collection = mysql_query_select($sql);
echo json_encode($collection);

?>