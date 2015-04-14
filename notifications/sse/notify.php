<?php
	$myfile = fopen("notifications.txt", "w") or die("Unable to open file!");
	$txt = time();
	fwrite($myfile, $txt);
	fclose($myfile);
	echo $txt;
?>