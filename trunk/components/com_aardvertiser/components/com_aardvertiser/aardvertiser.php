<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.helper');
require_once(JApplicationHelper::getPath( 'html' ) );
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.$option.DS.'tables');
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

    $query = "SELECT * FROM #__aard_cats WHERE published = '1' ORDER BY ordering, cat_name";
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
    $query = "SELECT a.id, a.ad_name, a.user_id, a.ad_state, a.contact_name, a.published, a.date_created, a.ad_img2small, a.ad_img1small, a.ad_price, a.ad_location, c.cat_name FROM #__aard_ads a, #__aard_cats c WHERE a.cat_id=c.id AND c.cat_name = '" . $cat_name . "' AND date_created > '" . $today . "'";
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
    $ad_delivery = array (
            '0' => array('value' => 'Pickup Only', 'text' => 'Pickup Only'),
            '1' => array('value' => 'Delivery Only', 'text' => 'Delivery'),
            '2' => array('value' => 'Pickup or delivery', 'text' => 'Pickup or delivery'),
    );
    $lists['ad_delivery'] = JHTML::_('select.genericList', $ad_delivery, 'ad_delivery', 'class="inputbox"'. '', 'value', 'text', $row->ad_delivery );
    $ad_state = array (
            '0' => array('value' => 'For Sale', 'text' => 'For Sale'),
            '1' => array('value' => 'Wanted', 'text' => 'Wanted'),
    );
    $query = 'SELECT id, cat_name FROM #__aard_cats';
    $objD = &JFactory::getDBO();
    $objD->setQuery($query);
    $aryReturnedCategories = $objD->loadObjectList('id');

    foreach ($aryReturnedCategories as $objCat) {
        $arySelectOptions[] = JHTML::_('select.option', $objCat->id , $objCat->cat_name );
    }

    $lists['cat_name'] = JHTML::_('select.genericlist', $arySelectOptions , 'cat_name', 'class="inputbox"', 'value', 'text' , 0 );
    $lists['ad_state'] = JHTML::_('select.genericList', $ad_state, 'ad_state', 'class="inputbox"'. '', 'value', 'text', $row->ad_state );

    // type of ad
    $query = "select v.id, v.label as value, v.default from jos_custom_properties_values v, jos_custom_properties_fields f, (select vl.id, vl.name from jos_custom_properties_values vl) vp where v.field_id=f.id and f.name='root_clasificados' and vp.name='tipo' and vp.id=v.parent_id";
    $objD->setQuery($query);
    $dbTypes = $objD->loadObjectList();
    $ad_type_options = array();
    $default = null;
    foreach ($dbTypes as $type) {
        if ($type->default){
            $default = $type->value;
        }
        $ad_type_options[] = JHTML::_('select.option', $type->id , $type->value );
    }
    $lists['ad_type'] = JHTML::_('select.genericList', $ad_type_options, 'ad_type', 'class="inputbox"'. '', 'value', 'text', $default );

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
function getExtension($str) {

    $i = strrpos($str,".");
    if (!$i) {
        return "";
    }

    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
}
function save() {
    //submit but set paid to 0
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
    if ($_FILES['img1']) {
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


                    imagejpeg($tmp,$filename,100);
                    imagejpeg($src,$filename1,100);

                    imagejpeg($tmp2,$filename2,100);
                    imagejpeg($src2,$filename12,100);

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


                if (!file_exists($thisdir.$dir)) {
                    mkdir($thisdir . $dir, 0777);
                }
                elseif (!file_exists($thisdir.$dir1)) {
                    mkdir($thisdir . $dir1, 0777);
                }


                $filename = "components/com_aardvertiser/images/users/".$userid."/thumb-".$_FILES['img1']['name']; //thumb
                $filename1 = "components/com_aardvertiser/images/users/".$userid."/".$_FILES['img1']['name']; //src



                imagejpeg($tmp,$filename,100);
                imagejpeg($src,$filename1,100);

                imagedestroy($src);
                imagedestroy($tmp);
            }
        }



        //end image
        $row->ad_desc = JRequest::getVar( 'ad_desc', '', 'post', 'string', JREQUEST_ALLOWRAW );

        $img1array = JRequest::getVar( 'img1', '', 'FILES', 'array');
        $row->ad_img1 = $img1array["name"];
        $row->ad_img1small = "thumb-".$img1array["name"];
        $img2array = JRequest::getVar( 'img2', '', 'FILES', 'array');
        $row->ad_img2 = $img2array["name"];
        $row->ad_img2small = "thumb-".$img2array["name"];
    }
    if (!$row->store()) {
        echo "<string> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
        exit();
    }


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