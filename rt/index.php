<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>webERP - Notificaciones</title>

	<script src="../notifications/bower_components/jquery/dist/jquery.js"></script>
    <script>/*
	console.log('<?php echo $_SERVER['HTTP_HOST']?>');
		var conn = new WebSocket('ws:<?php echo $_SERVER['HTTP_HOST']?>:8087');
		conn.onopen = function(e) {
			console.log("Connection established!");
			conn.send('Hello World!!!');
		};

		conn.onmessage = function(e) {
			console.log(e.data);
			document.write(e.data);
		};
		*/
		
		
		var source = new EventSource("ssserver.php");
		source.onmessage = function(event) {
			console.log(event.data);
		};
		
		function sendMessage(){
			$.get('ssserver.php?msg=hello!');
		}
		
	</script>

</head>
<body>
	<input type="button" value="Click" onclick="sendMessage()"/>
</body>
</html>