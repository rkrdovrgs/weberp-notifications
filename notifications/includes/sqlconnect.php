<?php
$servername = "localhost";
$username = "matemat3_weberp";
$password = '$$gallopinto$$';
$dbname = "matemat3_weberp";

// Create connection
$link = mysql_connect($servername, $username, $password)
			or die('Could not connect: ' . mysql_error());
mysql_select_db($dbname) or die('Could not select database');
function query_object($sql){
	$encode = array();
	$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
	while($row = mysql_fetch_object($result)) {
	   $encode[] = $row;
	}
	return sizeof($encode) === 1 ? $encode[0] : $encode;
}

function query_json($sql){
	return json_encode(query_object($sql));
}





?>