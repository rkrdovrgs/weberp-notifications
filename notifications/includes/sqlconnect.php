<?php
$servername = "localhost";
$username = "matemat3_weberp";
$password = '$$gallopinto$$';
$dbname = "matemat3_weberp";

// Create connection
$link = mysql_connect($servername, $username, $password)
			or die('Could not connect: ' . mysql_error());
mysql_select_db($dbname) or die('Could not select database');

function mysql_query_select($sql){
	$encode = array();
	$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
	while($row = mysql_fetch_object($result)) {
	   $encode[] = $row;
	}
	return $encode;
}

function mysql_query_single($sql){
	$encode = mysql_query_select($sql);
	return $encode[0];
}

function mysql_query_exec($sql){
	$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
	return $resul;
}

?>