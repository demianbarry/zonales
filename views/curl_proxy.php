<?php
$port = array_key_exists("port",$_POST) && $_POST["port"] ? $_POST["port"] : $_GET["port"];
$host = "http://". (array_key_exists("host",$_POST) && $_POST["host"] ? $_POST["host"] : $_GET["host"]);
$path = array_key_exists("ws_path",$_POST) && $_POST["ws_path"] ? $_POST["ws_path"] : $_GET["ws_path"];
$url = $host .":". $port . "/".$path;

//$url = "http://".$_POST["host"].":".$_POST["port"]."/".$_POST['ws_path'];
//echo $url;
$session = curl_init();

if(strrpos($url, "/solr/update/json?{")){
    curl_setopt($session, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	//extract data from the post
	$params = substr($url, strpos($url, "?") + 1);//'port='.$port.'&host='.$host.'&ws_path='.$path;
	$fields = explode("&",$params);			
	//set the url, number of POST vars, POST data
	curl_setopt($session,CURLOPT_URL,substr($url, 0, strpos($url, "?") != -1 ? strpos($url, "?") : strlen($url) - 1));
	curl_setopt($session,CURLOPT_POST,count($fields));
	curl_setopt($session,CURLOPT_POSTFIELDS,$params);	
} else {
	// Return the call not the headers
	curl_setopt($session,CURLOPT_URL,$url);	
}

curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
curl_setopt($session, CURLOPT_HEADER, false);
//curl_setopt($session, CURLOPT_HEADER, true);
// call the data
$output ="";
$output = curl_exec($session);
$httpcode = curl_getinfo($session, CURLINFO_HTTP_CODE);

//var_dump($info);
//echo "CODE: " . $info;
header("Content-Type: text/html", true, $httpcode);

//var_dump($session);
//var_dump($output);
curl_close($session);

if($httpcode == 200) {
	echo $output;
} else {
	echo $output;
}
//echo $output;
?>
