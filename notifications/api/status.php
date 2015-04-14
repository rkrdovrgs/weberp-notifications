<?php
header('Content-Type: json/application');
$data = new stdClass;
$data->count = 15;
echo json_encode($data);

?>
