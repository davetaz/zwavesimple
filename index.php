<?php

	error_reporting(E_ALL ^ E_WARNING);

	$config = file_get_contents("config.json");
	$config = json_decode($config,true);


/*	
	$code = $_GET["code"];
	if ($code != $config["code"])	{
		http_response_code(403);
		echo "403 Forbidden";
		exit();
	}
*/
	include('_includes/header.html');
	
	echo '<section id="devices"></section>';

	include('_includes/footer.html');

?>
