<?php

function getApiKey($provider){
    $query = 'select p.apikey from #__providers p where name="' . $provider . '"';
    $db = JFactory::getDBO();
    $db->setQuery($query);
    $dbApi = $db->loadObject();

    return $dbApi->apikey;
}

?>
