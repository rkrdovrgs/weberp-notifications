<?php
include('sqlconnect.php');
$myfile = fopen("sqlscripts", "r") or die("Unable to open file!");
$sqls = explode(';', fread($myfile,filesize("sqlscripts")));

foreach ($sqls as $sql) {
	if($sql !== '') {
		mysql_query_exec($sql);
	}
}
?>
