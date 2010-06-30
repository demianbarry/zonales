<?php
defined('_JEXEC') or die('Restricted Access');
require_once(JApplicationHelper::getPath( 'admin_html') );
JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
switch($task) {
    case 'conf':
        conf($option);
        break;
    case 'saveconf':
        saveconf($option, $task);
        break;
    case 'prune':
        pruneclassified();
        break;
    case 'categories':
        categories($option);
        break;
    case 'add':
    case 'edit':
        editClassified($option);
        break;
    case 'apply':
    case 'save':
        saveClassified($option, $task);
        break;
    case 'remove':
        removeClassifieds($option);
        break;
    case 'addcategory':
    case 'editcategory':
        editCategory();
        break;
    case 'removecategory':
        removecategory();
        break;
    case 'savecategory':
        savecategory($option, $task);
        break;
    case 'showcss':
        showcss($option);
        break;
    case "savecss":
        $file =JRequest::getVar( 'file', 1);
        $csscontent = JRequest::getVar( 'csscontent', 1);
        saveCss($file, $csscontent, $option);
        break;
    case 'email':
        email($option);
        break;
    case 'savemail':
        savemail($option);
        break;
    default:
        showClassifieds($option);
        break;
}
function email($option) {
    $db	=& JFactory::getDBO();
    $row =& JTable::getInstance('email', 'Table');
    $cid = JRequest::getVar('cid', array(0), '', 'array' );
    $id = $cid[0];
    $row->load(1);
    HTML_classifieds::email($row, $option);
}
function savemail($option) {
    global $mainframe;
    $row =& JTable::getInstance('email', 'Table');
    if (!$row->bind(JRequest::get('post'))) {
        echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
        exit();
    }


    if (!$row->store()) {
        echo "<string> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
        exit();
    }
    $mainframe->redirect('index.php?option=' . $option, 'Email Settings Saved');
}
function showcss($option) {
    $file = "../components/com_aardvertiser/aardvertiser.css";
    @chmod($file, 0766);
    $permission = is_writable($file);

    HTML_classifieds::showcss($file, $option);
}
function saveCss($file, $csscontent, $option) {
    global $mainframe;
    $app =& JFactory::getApplication();
    if (is_writable($file)) {
        if (!$handle = fopen($file, 'w+')) {
            echo "Cannot open file ($file)";
            exit;
        }
        if (fwrite($handle, $csscontent) === FALSE) {
            echo "Cannot write to file ($file)";
            exit;
        }
        fclose($handle);
        $mainframe->redirect('index.php?option=com_aardvertiser&task=conf','CSS Styles Saved!');
    }
    else {
        $mainframe->redirect('index.php?option=com_aardvertiser&task=conf','CSS could not be written');
    }
}
function saveconf($option, $task) {
    global $mainframe;
    $row =& JTable::getInstance('config', 'Table');
    if (!$row->bind(JRequest::get('post'))) {
        echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
        exit();
    }
    if (!$row->store()) {
        echo "<string> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
        exit();
    }
    $mainframe->redirect('index.php?option=' . $option, 'Config Saved');
}
function conf($option) {
    $db	=& JFactory::getDBO();
    $row =& JTable::getInstance('config', 'Table');
    $cid = JRequest::getVar('cid', array(0), '', 'array' );
    $id = $cid[0];
    $row->load(1);
    $lists = array();
    $currency = array (
            '0' => array('value' => '&pound;', 'text' => 'GBP'),
            '1' => array('value' => '$', 'text' => 'ARG'),
            '2' => array('value' => '&euro;', 'text' => 'EUR'),
            '3' => array('value' => '&yen;', 'text' => 'JPY'),
    );
    $access = array (
            '0' => array('value' => '1', 'text' => 'Public (Guest)'),
            '1' => array('value' => '0', 'text' => 'Registered'),
    );
    $catimg[] = JHTML::_( 'select.option', '0', 'No' );
    $catimg[] = JHTML::_( 'select.option', '1', 'Yes' );
    $map[] = JHTML::_( 'select.option', '0', 'Off' );
    $map[] = JHTML::_( 'select.option', '1', 'On' );
    $distance[] = JHTML::_( 'select.option', '0', 'Off' );
    $distance[] = JHTML::_( 'select.option', '1', 'On' );
    $emailusers[] = JHTML::_( 'select.option', '0', 'Off' );
    $emailusers[] = JHTML::_( 'select.option', '1', 'On' );
    $lists['catimg'] = JHTML::_('select.radioList', $catimg, 'catimg', 'class="inputbox"' . '', 'value', 'text', $row->catimg );
    $lists['map'] = JHTML::_('select.radioList', $map, 'map', 'class="inputbox"' . '', 'value', 'text', $row->map );
    $lists['currency'] = JHTML::_('select.genericList', $currency, 'currency', 'class="inputbox"'. '', 'value', 'text', $row->currency );
    $lists['distance'] = JHTML::_('select.radioList', $distance, 'distance', 'class="inputbox"'. '', 'value', 'text', $row->distance );
    $lists['access'] = JHTML::_('select.genericList', $access, 'access', 'class="inputbox"'. '', 'value', 'text', $row->access );
    $lists['emailusers'] = JHTML::_('select.radioList', $emailusers, 'emailusers', 'class="inputbox"'. '', 'value', 'text', $row->emailusers );


    HTML_classifieds::config($row, $lists, $option);
}
function pruneclassified() {
    global $mainframe;
    $query = "SELECT * FROM #__aard_config WHERE id ='1'";
    $db =& JFactory::getDBO();
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }
    foreach ($rows as $row) {
        $prune = $row->prune;
    }
    $today = date("Y-m-d H:i:s", mktime(date('H'),date('i'),date('s'),date('m'),date('d')-$prune,date('y')));
    $query = "DELETE FROM #__aard_ads WHERE date_created < '" . $today . "'";
    $db->setQuery($query);
    if (!$db->query()) {
        echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
    }
    $mainframe->redirect( 'index.php?option=com_aardvertiser', 'Adverts Pruned for the last ' . $prune . ' days');
}
function editClassified($option) {
    $query = "SELECT * FROM #__aard_config WHERE id ='1'";
    $db =& JFactory::getDBO();
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }
    foreach ($rows as $row) {
        $currency = $row->currency;
    }

    $row =& JTable::getInstance('classified', 'Table');
    $cid = JRequest::getVar( 'cid', array(0), '', 'array' );
    $id = $cid[0];
    $row->load($id);
    $lists = array();
    $ad_delivery = array (
            '0' => array('value' => 'Pickup Only', 'text' => 'Pickup Only'),
            '1' => array('value' => 'Delivery Only', 'text' => 'Delivery Only'),
            '2' => array('value' => 'Pickup or delivery', 'text' => 'Pickup or delivery'),
    );
    $lists['ad_delivery'] = JHTML::_('select.genericList', $ad_delivery, 'ad_delivery', 'class="inputbox"'. '', 'value', 'text', $row->ad_delivery );
    $ad_state = array (
            '0' => array('value' => 'For Sale', 'text' => 'For Sale'),
            '1' => array('value' => 'Wanted', 'text' => 'Wanted'),
    );

    $query = "SELECT id, cat_name FROM #__aard_cats";
    $objD = &JFactory::getDBO();
    $objD->setQuery($query);
    $aryReturnedCategories = $objD->loadObjectList();

    foreach ($aryReturnedCategories as $objCat) {
        $arySelectOptions[] = JHTML::_('select.option', $objCat->id , $objCat->cat_name );
    }
    $lists['cat_name'] = JHTML::_('select.genericlist', $arySelectOptions , 'cat_id', 'class="inputbox"', 'value', 'text' , 0 );
    $lists['ad_state'] = JHTML::_('select.genericList', $ad_state, 'ad_state', 'class="inputbox"'. '', 'value', 'text', $row->ad_state );
    $lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $row->published);

    // agregado por G2P
    $expiration_date = (!$row->date_expiration) ? date('Y-m-d') : $row->date_expiration;

    $addTagsUrl = JRoute::_('index.php?option=com_customproperties&controller=hierarchictagging&view=hierarchictagging&ce_name=ad&id='.$row->id);
    $selectTags = 'select v.name as value from #__custom_properties cp, #__custom_properties_values v, #__aard_ads a where cp.value_id=v.id and cp.ref_table="ad" and cp.content_id=a.id and a.id=' . $row->id;
    $db = JFactory::getDBO();
    $db->setQuery($selectTags);
    $dbTags = $db->loadObjectList();

    $aux = array();
    foreach ($dbTags as $tag) {
        $aux[] = $tag->value;
    }
    $tags = implode(', ', $aux);

    $data = array(
        'tags' => $tags,
        'addtagsurl' => $addTagsUrl,
        'expirationdate' => $expiration_date
    );
    // fin

    HTML_classifieds::editClassified($row, $lists, $option, $currency,$data);
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
function saveClassified($option, $task) {
    global $mainframe;
    $row =& JTable::getInstance('classified', 'Table');
    if (!$row->bind(JRequest::get('post'))) {
        echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
        exit();
    }
//start image



    define ("MAX_SIZE","1024");

    $errors=0;
    $userid = $row->user_id;

    if (isset($_FILES['img1'])) {
        if ($_FILES['img2']['size'] == 0) {
            $imageno = 1;
        } else {
            $imageno = count($_FILES);
        }

        if ($imageno == 2) {
            $image =$_FILES["img1"]["name"];
            $uploadedfile = $_FILES['img1']['tmp_name'];
            $image2 =$_FILES["img2"]["name"];
            $uploadedfile2 = $_FILES['img2']['tmp_name'];
            if ($image && $image2) {
                $filename = stripslashes($_FILES['img1']['name']);
                $filename2 = stripslashes($_FILES['img2']['name']);
                $extension = getExtension($filename);
                $extension2 = getExtension($filename2);
                $extension = strtolower($extension);
                $extension2 = strtolower($extension2);
                if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
                    echo ' Unknown Image extension ';
                    $errors=1;
                }
                elseif (($extension2 != "jpg") && ($extension2 != "jpeg") && ($extension2 != "png") && ($extension2 != "gif")) {
                    echo ' Unknown Image extension ';
                    $errors=1;
                }
                else {
                    $size=filesize($_FILES['img1']['tmp_name']);
                    $size2=filesize($_FILES['img2']['tmp_name']);

                    if ($size > MAX_SIZE*1024 & $size2 > MAX_SIZE*1024) {
                        echo "You have exceeded the size limit";
                        $errors=1;
                    }

                    if($extension=="jpg" || $extension=="jpeg" ) {
                        $uploadedfile = $_FILES['img1']['tmp_name'];
                        $src = imagecreatefromjpeg($uploadedfile);
                    }
                    else if($extension=="png") {
                        $uploadedfile = $_FILES['img1']['tmp_name'];
                        $src = imagecreatefrompng($uploadedfile);
                    }
                    else {
                        $src = imagecreatefromgif($uploadedfile);
                    }
                    if($extension2=="jpg" || $extension2=="jpeg" ) {
                        $uploadedfile2 = $_FILES['img2']['tmp_name'];
                        $src2 = imagecreatefromjpeg($uploadedfile2);
                    }
                    else if($extension2=="png") {
                        $uploadedfile2 = $_FILES['img2']['tmp_name'];
                        $src2 = imagecreatefrompng($uploadedfile2);
                    }
                    else {
                        $src2 = imagecreatefromgif($uploadedfile2);
                    }
                    list($width,$height)=getimagesize($uploadedfile);

                    list($width2,$height2)=getimagesize($uploadedfile2);
                    $newwidth=150;
                    $newheight = 100;

                    $tmp=imagecreatetruecolor($newwidth,$newheight);
                    $tmp2=imagecreatetruecolor($newwidth,$newheight);

                    imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
                    imagecopyresampled($tmp2,$src2,0,0,0,0,$newwidth,$newheight,$width2,$height2);

                    $thisdir = getcwd();
                    $dir = ("/components/com_aardvertiser/images/users");
                    $dir1 = ("/components/com_aardvertiser/images/users/$userid");
                    $find = "\administrator";
                    $thisdir = str_replace($find, "", $thisdir);

                    if (!file_exists($thisdir.$dir)) {
                        mkdir($thisdir . $dir, 0777);
                    }
                    if (!file_exists($thisdir.$dir1)) {
                        mkdir($thisdir . $dir1, 0777);
                    }



                    $filename = "components/com_aardvertiser/images/users/".$userid."/thumb-".$_FILES['img1']['name']; //thumb
                    $filename1 = "components/com_aardvertiser/images/users/".$userid."/".$_FILES['img1']['name']; //src
                    $filename2 = "components/com_aardvertiser/images/users/".$userid."/thumb-".$_FILES['img2']['name']; //thumb
                    $filename12 = "components/com_aardvertiser/images/users/".$userid."/".$_FILES['img2']['name']; //src


                    imagejpeg($tmp,"../".$filename,100);
                    imagejpeg($src,"../".$filename1,100);

                    imagejpeg($tmp2,"../".$filename2,100);
                    imagejpeg($src2,"../".$filename12,100);

                    imagedestroy($src);
                    imagedestroy($tmp);
                    imagedestroy($src2);
                    imagedestroy($tmp2);

                }
            }
        } else {
            $image =$_FILES["img1"]["name"];
            $uploadedfile = $_FILES['img1']['tmp_name'];


            $filename = stripslashes($_FILES['img1']['name']);
            $extension = getExtension($filename);
            $extension = strtolower($extension);
            if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
                echo ' Unknown Image extension ';
                $errors=1;
            }
            else {
                $size=filesize($_FILES['img1']['tmp_name']);

                if ($size > MAX_SIZE*1024) {
                    echo "You have exceeded the size limit";
                    $errors=1;
                }

                if($extension=="jpg" || $extension=="jpeg" ) {
                    $uploadedfile = $_FILES['img1']['tmp_name'];
                    $src = imagecreatefromjpeg($uploadedfile);
                }
                else if($extension=="png") {
                    $uploadedfile = $_FILES['img1']['tmp_name'];
                    $src = imagecreatefrompng($uploadedfile);
                }
                else {
                    $src = imagecreatefromgif($uploadedfile);
                }

                list($width,$height)=getimagesize($uploadedfile);

                $newwidth=150;
                $newheight=100;
                $tmp=imagecreatetruecolor($newwidth,$newheight);

                imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

                $thisdir = getcwd();
                $dir = ("/components/com_aardvertiser/images/users");
                $dir1 = ("/components/com_aardvertiser/images/users/$userid");
                $find = "\administrator";
                $thisdir = str_replace($find, "", $thisdir);


                if (!file_exists($thisdir.$dir)) {
                    mkdir($thisdir . $dir, 0777);
                }
                elseif (!file_exists($thisdir.$dir1)) {
                    mkdir($thisdir . $dir1, 0777);
                }


                $filename = "components/com_aardvertiser/images/users/".$userid."/thumb-".$_FILES['img1']['name']; //thumb
                $filename1 = "components/com_aardvertiser/images/users/".$userid."/".$_FILES['img1']['name']; //src



                imagejpeg($tmp,"../".$filename,100);
                imagejpeg($src,"../".$filename1,100);

                imagedestroy($src);
                imagedestroy($tmp);
            }
        }



        //end image
        $row->ad_desc = JRequest::getVar( 'ad_desc', '', 'post', 'string', JREQUEST_ALLOWRAW );

        $img1array = JRequest::getVar( 'img1', '', 'FILES', 'array');
        if ($img1array["name"] == '') {

        } else {
            $row->ad_img1 = $img1array["name"];
            $row->ad_img1small = "thumb-".$img1array["name"];
        }

        $img2array = JRequest::getVar( 'img2', '', 'FILES', 'array');
        if ($img2array["name"] == '') {
        } else {
            $row->ad_img2 = $img2array["name"];
            $row->ad_img2small = "thumb-".$img2array["name"];
        }
    }


    if (!$row->store()) {
        echo "<string> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
        exit();
    }
    $mainframe->redirect('index.php?option=' . $option, 'Ads Saved');
}
function showClassifieds($option) {
    global $option, $mainframe;
    $filter_order		= $mainframe->getUserStateFromRequest( $option.'filter_order',		'filter_order',	'',	'cmd' );
    $filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir',	'filter_order_Dir',	'asc',	'word' );
    $filter_state		= $mainframe->getUserStateFromRequest( $option.'filter_state',		'filter_state',	'',	'word' );
    $limit = JRequest::getVar('limit', $mainframe->getCfg('list_limit'));
    $limitstart = JRequest::getVar('limitstart', 0);
    if (!$filter_order) {
        $filter_order = 'ad_name';
    }
    $order = ' ORDER BY '. $filter_order .' '. $filter_order_Dir .' ';
    $db =& JFactory::getDBO();
    $query = "SELECT count(*) FROM #__aard_ads";
    $db->setQuery($query);
    $total = $db->loadResult();
    $query = 'SELECT a.id, a.ad_name, a.user_id, a.ad_state, a.contact_name, a.published, a.date_created, c.cat_name FROM #__aard_ads a, #__aard_cats c WHERE a.cat_id=c.id' . $order . ' ';
    $db->setQuery($query, $limitstart, $limit);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }

    $lists['order_Dir']	= $filter_order_Dir;
    $lists['order']		= $filter_order;
    jimport('joomla.html.pagination');
    $pageNav = new JPagination($total, $limitstart, $limit);
    HTML_classifieds::showClassifieds($option, $lists, $rows, $pageNav);
}
function removeClassifieds($option) {
    global $mainframe;
    $cid = JRequest::getVar('cid', array(), '', 'array' );
    $db =& JFactory::getDBO();
    if(count($cid)) {
        $cids = implode(',', $cid );
        $query = "DELETE FROM #__aard_ads WHERE id IN ($cids)";
        $db->setQuery($query);
        if (!$db->query()) {
            echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
        }
    }
    $mainframe->redirect( 'index.php?option=' . $option );
}
/*========================================================================================
 *===============================Categories Begin Here!!!!================================
 *=======================================================================================*/
function categories($option) {
    global $option, $mainframe;
    $limit = JRequest::getVar('limit', $mainframe->getCfg('list_limit'));
    $limitstart = JRequest::getVar('limitstart', 0);
    $db =&JFactory::getDBO();
    $query = "SELECT count(*) FROM #__aard_cats";
    $db->setQuery($query);
    $total = $db->loadResult();
    $query = "SELECT * FROM #__aard_cats ORDER BY ordering, cat_name";
    $db->setQuery($query, $limitstart, $limit);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }
    jimport('joomla.html.pagination');
    $pageNav = new JPagination($total, $limitstart, $limit);
    HTML_classifieds::showCategories($option, $rows, $pageNav);

    /*global $option, $mainframe;
	$limit = JRequest::getVar('limit', $mainframe->getCfg('list_limit'));
	$limitstart = JRequest::getVar('limitstart', 0);
	$db =& JFactory::getDBO();
	$query = "SELECT count(*) FROM #__class_cats";
	$db->setQuery($query);
	$total = $db->loadResult();
	$query = "SELECT c.*, a.ad_name FROM #__class_cats AS c LEFT JOIN #__class_ads AS a ON a.cat_id = c.id";
	$db->setQuery($query, $limitstart, $limit );
	$rows = $db->loadObjectList();
	if ($db->getErrorNum())
	{
		echo $db->stderr();
		return false;
	}
	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);
	HTML_classifieds::showCategories($option, $rows, $pageNav);*/
}
function editCategory() {
    global $option;
    $row=& JTable::getInstance('category', 'Table');
    $cid = JRequest::getVar('cid', array(0), '', 'array' );
    $id = $cid[0];
    $row->load($id);
    HTML_classifieds::editCategory($row, $option);
}
function savecategory($option, $task) {
    global $option, $mainframe;
    $row =& JTable::getInstance('Category', 'Table');
    if (!$row->bind(JRequest::get('post'))) {
        echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
        exit();
    }

    if (!$row->store()) {
        if (!$row->bind(JRequest::getget('post'))) {
            echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
            exit();
        }
    }
    $mainframe->redirect('index.php?option=' . $option . '&task=categories', 'Category changes saved');
}
function removecategory() {
    global $option, $mainframe;
    $cid = JRequest::getVar('cid', array(), '', 'array' );
    $db =& JFactory::getDBO();
    if(count($cid)) {
        $cids = implode(',', $cid);
        $query = "DELETE FROM #__aard_cats WHERE id IN ( $cids )";
        $db->setQuery( $query );
        if (!$db->query()) {
            echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";

        }
    }
    $mainframe->redirect('index.php?option=' . $option . '&task=categories', 'Category Deleted');
}
?>