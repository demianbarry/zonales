<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<?php 

defined('_JEXEC') or die('Restricted access');  

jimport('joomla.application.component.controller');  

class EjemploController extends JController {
    function display() {
        parent::display();
    }
}
$doc =& JFactory::getDocument();
$doc->addStyleSheet( 'templates/' . $doc->template . '/css/mod_krss.css' );

$urls = $params->get('urls');

$urls = explode ("\n", $urls);

foreach ($urls as $v) {
    ($v!=""?$file[]=$v:"");
}

include ('rss2_class.php');

$feed = new Feed('http://www.example.com/feed.rss');

$indice=1;
foreach ($file as $file) {
    $feed = new Feed($file);
    $i=1;
    $x=1;
    while ($nota = $feed->next()) {
        ($nota->PubDate != ""? $noticia[$indice]["fecha"]=$nota->PubDate:($nota->date != ""? $noticia[$indice]["fecha"]=$nota->date :	($nota->updated != ""? $noticia[$indice]["date"]=$nota->updated:"") ) );
        ($nota->title!=""?$noticia[$indice]["titulo"] = "" . $nota->title . "<br>":"");
        ($nota->description!=""?$noticia[$indice]["descripcion"]=$nota->description. "<br>":"");
        ($nota->image!=""?$noticia[$indice]["imagen"]="<img src=\"".$nota->image. "\"><br>":"");
        (isset($nota->url)?$noticia[$indice++]["web"]="<a href=\"".$nota->url. "\">" . $nota->url . "</a><br>":"");
        if ($x++ >5) {
            break;
        }
    }
}
array_multisort ($noticia, SORT_ASC);
echo '<div class="modkrss">';
foreach ($noticia as $nota) {
    foreach ($nota as $i => $v) {
        echo "<p><b>" . $i . "</b>: " . $v . "</p><br>";
    }
    echo "<div class=\"splitter\"></div>";
}
echo '</div>';
?>