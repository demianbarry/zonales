<?php

function getHTMLList($type,$name,$default = null){
    $objD = &JFactory::getDBO();
        $query = "select v.id, v.label, v.default from #__custom_properties_values v, #__custom_properties_fields f, (select vl.id, vl.name from #__custom_properties_values vl) vp where v.field_id=f.id and f.name='root_clasificados' and vp.name='$type' and vp.id=v.parent_id";
    $objD->setQuery($query);
    $dbTypes = $objD->loadObjectList();
    $options = array();
    foreach ($dbTypes as $type) {
        if ($type->default){
            $default = $type->label;
        }
        if ($default && $type->default == $default){
            $default = $type->label;
        }
        $options[] = JHTML::_('select.option', $type->id , $type->label );
    }
    return JHTML::_('select.genericList', $options, $name, 'class="inputbox"'. '', 'value', 'text', $default );
}

function applyTag($tagId,$contentId) {
    $table = "aard_ads";
    $selectField = "SELECT f.id FROM #__custom_properties_fields f WHERE f.name='root_clasificados'";
    $db = JFactory::getDBO();
    $db->setQuery($selectField);
    $dbfield = $db->loadObject();
    $fieldId = $dbfield->id;

    $query = "INSERT INTO #__custom_properties(ref_table,content_id,field_id,value_id) VALUES ('$table',$contentId,$fieldId,$tagId)";
    $db->setQuery($query);
    $db->query();
}

function getAdId($name){
    $query = "SELECT a.id FROM #__aard_ads a WHERE a.ad_name='$name'";
    $db = JFactory::getDBO();
    $db->setQuery($query);
    $dbad = $db->loadObject();

    return (isset ($dbad->id)) ? $dbad->id : null ;
}

function getExtension($str) {

    $i = strrpos($str,".");
    if (!$i) {
        return "";
    }

    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
}

//function registerImage($filename,$adId,$isThumbnail = false) {
//    $thumb = ($isThumbnail) ? '1' : '0';
//    $query = "INSERT INTO #__aard_ads_images(ad_id,name,small) VALUES ($adId,'$filename',$thumb)";
//    $db = JFactory::getDBO();
//    $db->setQuery($query);
//    $db->query();
//}

function registerImage($row,$filename,$adId,$isThumbnail = false) {
    $thumb = ($isThumbnail) ? '1' : '0';
    $row->name = $filename;
    $row->small = $thumb;
    $row->ad_id = $adId;

    return $row->store();
}

?>
