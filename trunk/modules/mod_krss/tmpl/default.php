<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<?php
$file = "http://www.clarin.com/diario/hoy/opinion.xml";
$data = simplexml_load_file($file); 

foreach ($data->channel->item as $item) { 
	echo "<h1>$item->title</h1> <br />\n";
	echo "$item->description <br />\n";
	echo "<hr /> \n"; 
} 
?>
