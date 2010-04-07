<?php
// ensure this file is being included by a parent file
defined( '_JEXEC' ) or die( 'Restricted access' );


function com_install() {
    $file = new JFile();
    $basePath = 'libraries' . DS . 'solr' . DS;
    $basePathClient = $basePath . 'client.php';
    $basePathQuery = $basePath . 'query.php';
    $dest = JPATH_LIBRARIES . DS . 'solr' . DS;

    $file->copy($basePathClient, $dest);
    $file->copy($basePathQuery, $dest);
}

?>
