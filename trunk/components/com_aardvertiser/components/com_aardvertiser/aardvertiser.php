<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.helper');
require_once 'helper.php';
require_once(JApplicationHelper::getPath( 'html' ) );
require_once 'uploadexception.php';
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.$option.DS.'tables');

define ("MAX_SIZE","1048576");

switch($task) {
    case 'remove':
        removeAd($option);
        break;
    case 'view':
        viewAds($option);
        break;
    case 'viewad':
        viewAd($option);
        break;
    case 'edit':
    case 'add':
        editAd($option);
        break;
    case 'save':
        save();
        break;
    case 'myads':
        myAds($option);
        break;
    default:
        showPublishedCategories($option);
        break;
}
function showPublishedCategories($option) {

    $query = "SELECT * FROM #__aard_config WHERE id ='1'";
    $db =& JFactory::getDBO();
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }
    foreach ($rows as $row) {
        $access = $row->access;
    }

    $query = "SELECT days_shown, emailusers FROM #__aard_config WHERE id ='1'";
    $db =& JFactory::getDBO();
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }

    foreach ($rows as $row) {
        $dayy = $row->days_shown;
        $mailconf = $row->emailusers;
    }

    $today = date("Y-m-d H:i:s", mktime(date('H'),date('i'),date('s'),date('m'),date('d')-$dayy,date('y')));

    //$query = "SELECT * FROM #__aard_cats WHERE published = '1' ORDER BY ordering, cat_name";
    $query = "select v.id, v.label, v.name, v.default, cp.field_id from #__custom_properties_values v, #__custom_properties_fields f, (select vl.id, vl.name from #__custom_properties_values vl) vp where v.field_id=f.id and f.name='root_clasificados' and vp.name='rubro' and vp.id=v.parent_id";
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }
    $user =& JFactory::getUser();
    $user_id = $user->get( 'id' );
    if ($user_id == $access) {
        echo '<div class="componentheading">Please Log in to view this page.</div>';
    }
    else {
        HTML_classifieds::showCategories($rows, $option, $today);
    }
    $db =& JFactory::getDBO();
    $result = $db->setQuery("SELECT * FROM #__aard_ads WHERE published = '1' AND date_created > '" . $today . "' AND emailed = '0'");
    $rows = $db->loadObjectList();
    $number = count($rows);
    if ($mailconf == 1 & $number > 0) {
        $db =& JFactory::getDBO();
        $result = mysql_query("SELECT * FROM #__aard_ads WHERE published = '1' AND date_created > '" . $today . "' AND emailed = '0'");
        $count = 0;
        //if row's date_created + days_shown - 1 = today
        //then send mail


        $now = date("Y-m-d", mktime(date('m'),date('d'),date('y')));
        $email[0] = "";
        $id[0] = "";
        $name[0] = "";
        while($rows = mysql_fetch_array($result)) {
            $startdate = strtotime($rows['date_created']); //The date the ad was created


            $datefinishstamp =  strtotime("+$dayy days", $startdate);
            $datefinish = date("Y-m-d", $datefinishstamp); // The date that the ad finishes on

            $datefinstamp = strtotime("-1 days", $datefinishstamp);
            $datefin = date("Y-m-d", $datefinstamp); //The date one day from when the ad ends



            if ($datefin == $now) { //if today = finishdate - 1 then put email into array
                $email[$count] = $rows['contact_email'];
                $ad[$count] = $rows['ad_name'];
                $id[$count] = $rows['id'];
                $name[$count] = $rows['contact_name'];
                $count ++;
            }


        }


        $query = "SELECT * FROM #__aard_email WHERE id ='1'";
        $db =& JFactory::getDBO();
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $subject = "";
        $fromname = "";
        $fromemail = "";

        foreach ($rows as $row) {
            $subject = $row->subject;
            $fromname = $row->fromname;
            $fromemail = $row->fromemail;
        }
        $total = count($email);
        $count = 1;
        for (; ; ) {
            if ($count > $total) {
                break;
            }

            $to = $email[$count-1];
            $body = '<html>
		Dear '.$name[$count-1].',
		<p>
		Your Ad, '.$name[$count-1].' is about to expire please renew it if it has not already been sold</p>
		</html>
		';
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            // Additional headers
            $headers .= 'From: '.$fromname.' <'.$fromemail.'>' . "\r\n";


            mail($to, $subject, $body, $headers);
            $query = "UPDATE #__aard_ads SET emailed='1' WHERE id ='".$id[$count-1]."'";
            $db =& JFactory::getDBO();
            $db->setQuery($query);
            $dosql = $db->query();


            $count++;
        }
        //mail ends here



    }
}
function viewAds($option) {
    $cat_name = JRequest::getVar('cat_name', 0);

    $query = "SELECT * FROM #__aard_config WHERE id ='1'";
    $db =& JFactory::getDBO();
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }
    foreach ($rows as $row) {
        $access = $row->access;
    }

    $query = "SELECT * FROM #__aard_config WHERE id ='1'";
    $db =& JFactory::getDBO();
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }
    foreach ($rows as $row) {
        $dayy = $row->days_shown;
        $currency = $row->currency;
        $catimg = $row->catimg;
    }

    $today = date("Y-m-d H:i:s", mktime(date('H'),date('i'),date('s'),date('m'),date('d')-$dayy,date('y')));
    $query = "SELECT a.id, a.ad_name, a.user_id, a.ad_state, a.contact_name, a.published, a.date_created, a.ad_img2small, a.ad_img1small, a.ad_price, a.ad_location, v.name AS cat_name FROM #__aard_ads a, #__custom_properties cp, #__custom_properties_values v WHERE a.cat_id=cp.content_id AND cp.value_id=v.id AND cp.value_id=v.id AND v.name = '" . $cat_name . "' AND cp.ref_table='aard_ads' AND date_created > '" . $today . "'";
    $db->setQuery($query);
    $rows = $db->loadObjectList();

    foreach ($rows as $row) {
        $query = "SELECT img.src FROM #__aard_ads_images img WHERE img.ad_id = $row->id";
        $db->setQuery($query);
        $row->images = $db->loadObjectList();
    }

    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }
    $user =& JFactory::getUser();
    $user_id = $user->get( 'id' );
    if ($user_id == $access) {
        echo '<div class="componentheading">Please Log in to view this page.</div>';
    }
    else {
        echo '<div class="componentheading">' . $cat_name . '</div>';
        HTML_classifieds::showAds($rows, $option, $currency, $catimg);
    }

}
function viewAd($option) {
    $id = JRequest::getVar('id', 0);
    $query = "SELECT * FROM #__aard_config WHERE id ='1'";
    $db =& JFactory::getDBO();
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }
    foreach ($rows as $row) {
        $access = $row->access;
    }

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
        $font = $row->font_color;
        $detail_color = $row->ad_detail_font;
        $state_color = $row->ad_state_font;
        $find = $row->distance;
        $map = $row->map;
    }

    $row =& JTable::getInstance('classified', 'Table');
    $row->load($id);
    if(!$row->published) {
        JError::raiseError(404, JText::_('Invalid ID Provided') );
    }
    $user =& JFactory::getUser();
    $user_id = $user->get( 'id' );
    if ($user_id == $access) {
        echo '<div class="componentheading">Please Log in to view this page.</div>';
    }
    else {
        $query = "SELECT cat_name FROM #__aard_cats WHERE id=" . $row->cat_id;
        $db->setQuery($query);
        $db_cat_name = $db->loadObject();
        
        // update impressions
        $impressions = $row->impressions + 1;
        $update = "UPDATE #__aard_ads SET impressions=$impressions WHERE id=". $row->id;
        $db->setQuery($update);
        $db->query();
        HTML_classifieds::showAd($row, $db_cat_name->cat_name,$option, $currency, $font, $state_color, $detail_color, $find, $map);
    }
}
function removeAd($option) {
    global $mainframe;
    $cid = JRequest::getVar('cid', array(), '', 'array' );
    $db =& JFactory::getDBO();
    $user =& JFactory::getUser();
    $user_id = $user->get( 'id' );
    if(count($cid)) {
        $cids = implode(',', $cid );
        $query = "DELETE FROM #__aard_ads WHERE id IN ($cids) AND user_id = '" . $user_id . "'";
        $db->setQuery($query);
        if (!$db->query()) {
            echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
        }
    }
    $mainframe->redirect( 'index.php?option=' . $option . '&task=myads');
}
function paid($option) {
    global $mainframe;
    $cid = JRequest::getVar( 'id', array(0), '', 'array' );
    $db =& JFactory::getDBO();


    $user =& JFactory::getUser();
    $user_id = $user->get( 'id' );
    if(count($cid)) {
        $cids = implode(',', $cid );
        $query = "UPDATE #__aard_ads SET paid = 1, date_created = '" . date("Y-m-d H:i:s") . "' WHERE id IN ($cids)";
        $db->setQuery($query);
        if (!$db->query()) {
            echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
        }
    }

    $mainframe->redirect( 'index.php?option=' . $option , "Advertisement Saved");



}
function editAd($option) {
    $user =& JFactory::getUser();
    $user_id = $user->get( 'id' );
    if ($user_id == 0) {
        echo 'Please <a href="index.php?option=com_user&view=zlogin">log in</a> to submit an ad.';
        return ;
    }

    $query =  "SELECT * FROM #__aard_config WHERE id ='1'";
    $db	=& JFactory::getDBO();
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
    $objD = &JFactory::getDBO();

    $lists['ad_delivery'] = getHTMLList('entrega', 'ad_delivery');
    $lists['ad_state'] = getHTMLList('intencion', 'ad_state');
    $lists['cat_name'] = getHTMLList('rubro', 'cat_id');
    $lists['ad_type'] = getHTMLList('tipo', 'ad_type');

    $expiration_date = (!$row->date_expiration) ? date('Y-m-d') : $row->date_expiration;
    $data = array(
        'expirationdate' => $expiration_date
    );

    if ($user_id == $row->user_id) {
        HTML_classifieds::editAd($row, $lists, $option, $currency,$data);
    }
    elseif ($row->user_id == "") {
        HTML_classifieds::editAd($row, $lists, $option, $currency,$data);
    }
    else {
        echo 'Invalid User';
    }
}

function save() {
    //submit but set paid to 0
    $query =  "SELECT * FROM #__aard_config WHERE id ='1'";
    $db	=& JFactory::getDBO();
    $db->setQuery($query);
    $rowConfig = $db->loadObject();

    global $mainframe;
    $row =& JTable::getInstance('classified', 'Table');
    if (!$row->bind(JRequest::get('post'))) {
        echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
        exit();
    }
    //start image

    $errors=0;
    $userid = $row->user_id;


    $row->ad_desc = JRequest::getVar( 'ad_desc', '', 'post', 'string', JREQUEST_ALLOWRAW );
    if (!$row->store()) {
        echo "<string> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
        exit();
    }

    /*
     * Modified by G2P
     */

    $catId = JRequest::getInt('cat_id',0,'post');
    $adTypeId = JRequest::getInt('ad_type',0,'post');
    $adStateId = JRequest::getInt('ad_state',0,'post');
    $adDelivery = JRequest::getInt('ad_delivery',0,'post');

    $adId = getAdId($row->ad_name);

    applyTag($catId, $adId);
    applyTag($adTypeId, $adId);
    applyTag($adStateId, $adId);
    applyTag($adDelivery, $adId);

        ##############
    // extension => format
    $allowedExtensions = array(
        'jpg' => 'jpeg',
        'jpeg' => 'jpeg',
        'gif' => 'gif',
        'png' => 'png'
    );

    $files = "imag";

    foreach ($_FILES[$files]["name"] as $key => $filename) {

        // Use
        if ($_FILES[$files]['error'][$key] === UPLOAD_ERR_OK) {
            $filename = stripslashes($filename);
            $extension = getExtension($filename);
            $extension = strtolower($extension);
            // si la extension no pertenece a algunas de las permitidas
            if (!array_key_exists($extension, $allowedExtensions)) {
                echo 'Unknown Image extension';
                continue;
            }
            $uploadedfile = $_FILES[$files]['tmp_name'][$key];
            $size = $_FILES[$files]['size'][$key];

            if ($size > MAX_SIZE) {
                echo "You have exceeded the size limit";
                continue;
            }

            // indico que funcion creara la imagen dependiendo de la extension
            $func = "imagecreatefrom" . $allowedExtensions[$extension];
            $src = $func($uploadedfile);

            list($width,$height)=getimagesize($uploadedfile);
            $newwidth= $rowConfig->default_image_width;
            $newheight = $rowConfig->default_image_height;
            $tmp=imagecreatetruecolor($newwidth,$newheight);
            imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

            $thisdir = getcwd();
            $imagesDir = (DS."components".DS."com_aardvertiser".DS."images".DS."users");
            $userDir = ($imagesDir . DS . $userid);

            if (!file_exists($thisdir.$imagesDir)) {
                mkdir($thisdir . $imagesDir, 0777);
            }
            if (!file_exists($thisdir.$userDir)) {
                mkdir($thisdir . $userDir, 0777);
            }

            $originalFilename = JPATH_ROOT.$userDir.DS.$filename . ".jpg"; //src

            imagejpeg($tmp,$originalFilename,100);

            imagedestroy($src);
            imagedestroy($tmp);

            $imageRow =& JTable::getInstance('image', 'Table');
            $result = registerImage($imageRow,$originalFilename, $adId);
        } else {
            throw new UploadException($_FILES[$files]['error'][$key]);
        }

        
    }
    ###############

    /*
     * End modification
     */

    $mainframe->redirect('index.php?option=com_aardvertiser', 'Ad Saved');
}
function myAds($option) {
    $query = "SELECT * FROM #__aard_config WHERE id ='1'";
    $db =& JFactory::getDBO();
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }
    foreach ($rows as $row) {
        $dayy = $row->days_shown;
        $currency = $row->currency;
    }

    $cat_name = JRequest::getVar('cat_name', 0);

    $user =& JFactory::getUser();
    $user_id = $user->get( 'id' );
    $query = "SELECT a.id, a.impressions, a.ad_name, a.user_id, a.ad_state, a.contact_name, a.published, a.date_created, a.ad_img2small, a.ad_img1small, a.ad_price, a.ad_location, c.cat_name FROM #__aard_ads a, #__aard_cats c WHERE a.cat_id=c.id AND user_id = '" . $user_id . "'";
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }
    $query = "SELECT name FROM #__users WHERE id = '" . $user_id . "'";
    $db->setQuery($query);
    $names = $db->loadObjectList();
    $user =& JFactory::getUser();
    $user_id = $user->get( 'id' );
    if ($user_id == 0) {
        echo ' <a href="index.php?option=com_user&view=login">Please log in.</a>';
    }
    else {
        HTML_classifieds::showmyAds($rows, $names, $dayy, $currency, $option);
    }


}
?>
