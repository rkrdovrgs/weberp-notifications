<?php
session_start();
include('../includes/sqlconnect.php');
header('Content-Type: application/json');
$userid = $_SESSION['UserID'];

$limit = $_GET['rows'];
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
				ORDER BY banktrans.transdate desc";

if($limit !== null)
$sql .= " LIMIT $limit";

echo query_json($sql);

?>