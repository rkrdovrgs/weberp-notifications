<?php
session_start();
$retry = rand(3000, 7000);
$uid = $_GET['uid'];
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

/**
 * Constructs the SSE data format and flushes that data to the client.
 *
 * @param string $id Timestamp/id of this connection.
 * @param string $msg Line of text that should be transmitted.
 */
function sendMsg($id, $msg) {
  echo "id: $id" . PHP_EOL;
  echo "data: $msg" . PHP_EOL;
  echo "retry: $retry" . PHP_EOL;
  echo PHP_EOL;
  ob_flush();
  flush();
}

$lastCheck = date($_SESSION[$uid]);

$myfile = fopen("notifications.txt", "r");
$date = fread($myfile,filesize("notifications.txt"));
$lastNotification = date($date);





if($lastCheck !== null && $lastCheck < $lastNotification){
	$resp = new stdClass;
	$resp->lastCheck = $lastCheck;
	$resp->lastNotification = $lastNotification;
	$resp->retry = $retry;
	$message = json_encode($resp);
	$message = 1;
	$_SESSION[$uid] = $lastNotification;
	sendMsg($uid, $message);
}
else{
    if($lastCheck === null)
        $_SESSION[$uid] = $lastNotification;
	echo "retry: $retry" . PHP_EOL;
}

?>