<?php
$port = array_key_exists($_POST,'port') && $_POST['port'] ? $_POST['port'] : $_GET['port'];
$host = "http://". (array_key_exists($_POST,'host') && $_POST['host'] ? $_POST['host'] : $_GET['host']);
$host .=":". $port . "/";

define ('HOSTNAME', $host);



$path = array_key_exists($_POST,'ws_path') && $_POST['ws_path'] ? $_POST['ws_path'] : $_GET['ws_path'];
//echo $path;
$url = HOSTNAME.$path;



// Open the Curl session
//echo $url;
$session = curl_init($url);


if (array_key_exists($_POST,'ws_path') && $_POST['ws_path']) {

     $postvars = '';

     while ($element = current($_POST)) {

          $postvars .= key($_POST).'='.$element.'&';

          next($_POST);

     }

     curl_setopt ($session, CURLOPT_POST, true);

     curl_setopt ($session, CURLOPT_POSTFIELDS, $postvars);

}



// Return the call not the headers

curl_setopt($session, CURLOPT_HEADER, false);

curl_setopt($session, CURLOPT_RETURNTRANSFER, true);



// call the data
$output = curl_exec($session);
$info = curl_getinfo($session);

if ($info['http_code'] == 500) {
	header("Content-Type: text/html", true, 500);
} else {
	header("Content-Type: text/javascript");
}
curl_close($session);

echo $output;
?>
