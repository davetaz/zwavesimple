<?php

	error_reporting(E_ALL ^ E_NOTICE);

	$code = $_GET["code"];
	if ($code == "") $code = $_POST["code"];
	$command = $_GET["command"];
	if ($command == "") $command = $_POST["command"];
	$deviceID = $_POST["node"];
	$level = $_POST["level"];
	$type = $_POST["type"];

/*	
	$command = "control";
	$deviceID = 4;
	$op = "on";
	$type = "Binary Power Switch";
*/
		
	$config = file_get_contents("/home/davetaz/code.json");
	$config = json_decode($config,true);

	if ($code != $config["code"])	{
		http_response_code(403);
		echo "403 Forbidden";
		exit();
	}

	if ($command == "devices") {
		echo getDevices();
		exit();
	}
	if ($command == "control") {
		doControl($deviceID,$level,$type);
		exit();
	}
	http_response_code(400);
	echo "400 Bad Request";
	exit();

function doControl($device,$level,$type) {
	
	$url = "http://localhost/server.php?command=control&node=" . $device . "&type=" . rawurlencode($type) . "&level=" . $level;

	$content = file_get_contents($url);
	echo $content;
	$res = getLevelResponse($content);
	if ($res == $level) {
		echo "OK" . $url;
	} else {
		http_response_code(500);
		echo "Internal server error";
	}
}

function getLevelResponse($content) {
	$bits = explode(" ",$content,2);
	$main = $bits[1];
	$bits = explode("=",$main);
	$key = trim($bits[0]);
	for($i=1;$i<count($bits);$i++) {
		$parts = explode(" ",trim($bits[$i]),2);
		$value = $parts[0];
		$out[$key] = $value;
		$key = $parts[1];
	}
	return $out["Level"];
	
}

function getDevices() {
	$stuff = file_get_contents("http://localhost/server.php?command=devices");
	$devices_raw = explode("\n",$stuff);
	for($i=0;$i<count($devices_raw);$i++) {
		$devices[] = getDeviceData($devices_raw[$i]);
	}
	return json_encode($devices,JSON_PRETTY_PRINT);
}

function getDeviceData($line) {
	$parts = explode("~",$line);
	$ret["id"] = $parts[2];
	$ret["name"] = $parts[1];
	$ret["type"] = $parts[4];
	$ret = getOtherData($parts[5],$ret);
	return $ret;
}

function getOtherData($string,$ret) {
	$parts = explode("=",$string);
	$key = trim($parts[0]);
	for($i=1;$i<count($parts);$i++) {
		if (strpos($parts[$i],"On and Off Enabled") !== false) {
			$value = "On and Off Enabled";
			$ret[$key] = $value;
			$key = trim(str_replace("On and Off Enabled","",$parts[$i]));	
		} else {
			$bits = explode(" ",trim($parts[$i]),2);
			$value = $bits[0];
			$ret[$key] = $value;
			$key = $bits[1];
		}
	}
	return $ret;
}
?>
