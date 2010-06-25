<?php
//PHP Code Simon Phillips
//Copyright Aardpress 2010 
//http://www.aardpress.com
//Do not remove these comments
class HTML_classifieds
{
function showCategories($rows, $option, $today)
	{
	  JHTML::stylesheet("aardvertiser.css","components/com_aardvertiser/")
		?>

<?php 
$addlink = JRoute::_('index.php?option=' . $option . '&task=add');
$myads = JRoute::_('index.php?option=' . $option . '&task=myads');
$user =& JFactory::getUser();
$user_id = $user->get( 'id' );
if ($user_id == 0) {
	$state = 'Please <a href="index.php?option=com_user&view=login">log in</a> to submit your own item.';
}
else {
	$state = '<a href="' . $addlink . '">Advertise an item</a> - <a href="' . $myads . '">My Adverts</a>';
};

echo
'<div style="border: 0px; height: 1%;">
  <div>
    <div style="valign: middle;"">
      <div class="componentheading" style="margin-bottom:7px;">
        Classified Ads
      </div>
    </div>
    <div style="valign: middle; margin-bottom:3px;">
      - ' . $state . '
    </div>
  </div>
</div>';
?>
<div align="center">
<div id="cats">
<?php
foreach($rows as $row)
{
		$db =& JFactory::getDBO();
		//$query = "SELECT * FROM #__aard_ads WHERE cat_name = '".$row->cat_name."' AND paid = '1'";
	$query = "SELECT * FROM #__aard_ads WHERE cat_id =" . $row->id . " AND date_created >= '" . $today . "'";
		$db->setQuery($query);
	$nums = $db->loadObjectList();
	$link = JRoute::_('index.php?option=' . $option . '&cat_name=' . $row->cat_name . '&task=view');
	if ($row->cat_img == '') {
	echo
	' <div id="catrow">
	    <div id="catcol" style="width:100%;">
        <h4 style="margin-top:5px; margin-bottom:3px;" >
          <a href="' . $link . '">' . $row->cat_name . '</a> - (' . count($nums) . ')
        </h4>
        <div class="catdesctext">
          ' . $row->cat_desc . '
        </div>
	    </div>
   </div>';
	}
	else
	{
		echo
	' <div>
	    <div style="width:1%;">
        <img src="' . $row->cat_img . '" class="catimg"></img>
      </div>
      <div>
        <h3 style="margin-top:5px; margin-bottom:3px;" >
          <a href="' . $link . '">' . $row->cat_name . '</a> - (' . count($nums) . ')
        </h3>
        <div class="catdesctext">
          ' . $row->cat_desc . '
        </div>
	    </div>
    </div>';
	}
	
}
?></div></div><br /><br clear="all" />
<?php 
include 'footer.php';
	
	}
function showAds($rows, $option, $currency, $catimg)
{
JHTML::stylesheet("aardvertiser.css","components/com_aardvertiser/")

	?>
<div id="ads">

	<?php 

	if (!$rows) {
		echo 'There are no ads in this category.';
	}
	$i = 0;
	foreach ($rows as $row)
	{
		
		$rand = rand(1,2);
		if ($rand == 1) {
		$imgurl = "components/com_aardvertiser/images/users/".$row->user_id."/".$row->ad_img1small;	
		} else {
		$imgurl = "components/com_aardvertiser/images/users/".$row->user_id."/".$row->ad_img2small;
		}
	$link = JRoute::_('index.php?option=' . $option . '&id=' . $row->id . '&task=viewad');
	echo '<div>';
	
	echo '<div style="';
	if (!$catimg) {echo 'padding-top:10px; height:41px; '; }
	echo 'width: 62%; margin: auto 0; float: left; padding: 3px 0px 3px 0px;">';
	
	if ($catimg) {
			
		echo '<img src="'.$imgurl.'" width="110px" height="73px" style="float:left; margin-right:5px;"/>';
	}
	echo '<h3';
	if (!$catimg) {
		echo ' style="margin-top:13px;"';
	} 
	echo '>';
	if ($catimg) {
	echo '<br /><br />';
	}
	echo '<a style="margin-bottom:5px; float:left;" href="' . $link . '">' . $row->ad_name . ' - ' . $row->ad_state . '</a>
      </h3>
    </div>
    <div style="width: 38%; margin: auto 0; float: left;" class="ads">
		  <div align="right" class ="adsum">';
	if ($catimg) {
			
		echo '<br />';
	}
	echo 'Item Price: ' . $currency . $row->ad_price . '<br />Item Location: ' . $row->ad_location . ' <br /><b>' . $row->ad_state . '</b></div>
		</div>
		</div>
		 ';
	}
	?>
<br clear="all" />	
</div><?php
	$link = 'index.php?option=' . $option; ?>
<br /><a href="<?php echo $link; ?>">&lt; return to categories</a><br /><br />
	<?php 
	include 'footer.php';
}
function showAd($row, $cat_name, $option, $currency, $font, $state_color, $detail_color, $find, $map)
{
	//Cloak the email address
global $mainframe;
$plugin =& JPluginHelper::getPlugin('content', 'emailcloak');
$pluginParams = new JParameter( $plugin->params );
require_once (JPATH_SITE.DS.'plugins'.DS.'content'.DS.'emailcloak.php');

$row2 = new stdClass;
$row2->text = $row->contact_email;

plgContentEmailCloak($row2,$pluginParams);

$row->contact_email = $row2->text;
//End cloaking
	$postlink = 'index.php?option=' . $option . '&id=' . $row->id . '&task=viewad';
	$link = 'index.php?option=' . $option . '&cat_name=' . $cat_name . '&task=view';
	JHTML::stylesheet("aardvertiser.css","components/com_aardvertiser/")
	?>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.1.min.js"></script>
<script type="text/javascript" src="components/com_aardvertiser/js/fancybox/jquery.fancybox-1.3.0.pack.js"></script>
	<link rel="stylesheet" href="components/com_aardvertiser/js/fancybox/jquery.fancybox-1.3.0.css" type="text/css" media="screen">
	<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function postit(){ //check postcode format is valid
 test = document.postcode.userpost.value; size = test.length
 test = test.toUpperCase(); //Change to uppercase
 while (test.slice(0,1) == " ") //Strip leading spaces
  {test = test.substr(1,size-1);size = test.length
  }
 while(test.slice(size-1,size)== " ") //Strip trailing spaces
  {test = test.substr(0,size-1);size = test.length
  }
 document.postcode.userpost.value = test; //write back to form field
 if (size < 6 || size > 8){ //Code length rule
  alert(test + " is not a valid postcode - wrong length");
  document.postcode.userpost.focus();
  return false;
  }
 if (!(isNaN(test.charAt(0)))){ //leftmost character must be alpha character rule
   alert(test + " is not a valid postcode - cannot start with a number");
   document.postcode.userpost.focus();
   return false;
  }
 if (isNaN(test.charAt(size-3))){ //first character of inward code must be numeric rule
   alert(test + " is not a valid postcode - alpha character in wrong position");
   document.postcode.userpost.focus();
   return false;
  }
 if (!(isNaN(test.charAt(size-2)))){ //second character of inward code must be alpha rule
   alert(test + " is not a valid postcode - number in wrong position");
   document.postcode.userpost.focus();
   return false;
  }
 if (!(isNaN(test.charAt(size-1)))){ //third character of inward code must be alpha rule
   alert(test + " is not a valid postcode - number in wrong position");
   document.postcode.userpost.focus();
   return false;
  }
 if (!(test.charAt(size-4) == " ")){//space in position length-3 rule
   alert(test + " is not a valid postcode - no space or space in wrong position");
   document.postcode.userpost.focus();
   return false;
   }
 count1 = test.indexOf(" ");count2 = test.lastIndexOf(" ");
 if (count1 != count2){//only one space rule
   alert(test + " is not a valid postcode - only one space allowed");
   document.postcode.userpost.focus();
   return false;
  }
return true;
}
//  End -->
</script>
<script language="javascript">
$(document).ready(function() {
	

	/* JQUERY fancybox */
	
	$("a#image1").fancybox({
		'cyclic'		:	true,
		'overlayOpacity'		:	0.6,
		'overlayColor'		:	'#666',
		'transitionIn'	: 'elastic',
		'transitionOut'	: 'elastic',
		'titlePosition' : 'inside'

	});
	$("a#image2").fancybox({
		'cyclic'		:	true,
		'overlayOpacity'		:	0.6,
		'overlayColor'		:	'#666',
		'transitionIn'	: 'elastic',
		'transitionOut'	: 'elastic',
		'titlePosition' : 'inside'

	});
	
});
</script>

    <div class="gen-1">
      <div class="detailtab aard-ad-1">
        <div align="left" class="aard-ad-1-1">
          <div class="contentheading">
            <b><?php echo $row->ad_name; ?></b> - <?php echo $row->ad_state; ?>
          </div>
        </div>
        <div align="right" class="aard-ad-1-2">
          Category:  <a href="<?php echo $link; ?>" class="gen-2"><?php echo $row->cat_name; ?></a>
        </div>
        <div class="gen-6"></div>
      </div>
      <div class="aard-ad-2">
        <div class="aard-ad-2-1">
          <?php echo $row->ad_desc; ?>
        </div>
        <div class="aard-ad-2-2">
          <div class="gen-6">
            <center>
              <b><font color="<?php echo $state_color; ?>" size="5%"><?php echo $row->ad_state; ?></font></b>
            </center>
          </div>
        </div>
        
                <div class="aard-ad-2-22">
          <div class="gen-66">
            <center>
            <a id="image1" rel="gall" href="<?php if ($row->ad_img1 == "") {
            	echo "components/com_aardvertiser/images/noimage.png";
            } else {
            	echo "components/com_aardvertiser/images/users/".$row->user_id."/".$row->ad_img1; }?>">
              <img alt="<?php echo $row->ad_name; ?>" src="<?php if ($row->ad_img1small == "") {
            	echo "components/com_aardvertiser/images/noimage.png";
            } else {
            	echo "components/com_aardvertiser/images/users/".$row->user_id."/".$row->ad_img1small; }?>" style="border:dashed 1px; height:100px; width:150px;" />
              </a><a id="image2" rel="gall" href="<?php if ($row->ad_img2 == "") {
            	echo "components/com_aardvertiser/images/noimage.png";
            } else {
            	echo "components/com_aardvertiser/images/users/".$row->user_id."/".$row->ad_img2; }?>">
              <img alt="<?php echo $row->ad_name; ?>" src="<?php if ($row->ad_img2small == "") {
            	echo "components/com_aardvertiser/images/noimage.png";
            } else {
            	echo "components/com_aardvertiser/images/users/".$row->user_id."/".$row->ad_img2small; }?>" style="border:dashed 1px; height:100px; width:150px;" />
              </a>
            </center>
          </div>
       		 </div>
       		
        <div class="aard-ad-2-3">
          <div align="left" class="gen-5">
            <b><font color="<?php echo $font; ?>">Item Location:</font></b>
          </div>
        </div>
        <div class="aard-ad-2-4">
          <font color="<?php echo $detail_color; ?>" class="gen-2"><?php echo $row->ad_location; ?></font>
        </div>
        <div class="aard-ad-2-3">
          <div align="left" class="gen-5">
            <b><font color="<?php echo $font; ?>">Postcode:</font></b>
          </div>
        </div>
        <div class="aard-ad-2-4">
          <font color="<?php echo $detail_color; ?>" class="gen-2"><?php echo $row->ad_post; ?></font>
        </div>
        <div class="aard-ad-2-3">
          <div align="left" class="gen-5">
            <b><font color="<?php echo $font; ?>">Delivery Options:</font></b>
          </div>
        </div>
        <div class="aard-ad-2-4">
          <font color="<?php echo $detail_color; ?>" class="gen-2"><?php echo $row->ad_delivery; ?></font>
        </div>
        <div class="aard-ad-2-3">
          <div align="left" class="gen-5">
            <b><font color="<?php echo $font; ?>">Item Asking Price:</font></b>
          </div>
        </div>
        <div class="aard-ad-2-4">
          <font color="<?php echo $detail_color; ?>" class="gen-2"><?php echo $currency . $row->ad_price; ?></font>
        </div>
        <div class="aard-ad-2-11">
          <div align="left"></div>
        </div>
        <div class="aard-ad-2-12"></div>
        <div class="aard-ad-2-3">
          <div align="left" class="gen-5">
            <b><font color="<?php echo $font; ?>">Contact's Name:</font></b>
          </div>
        </div>
        <div class="aard-ad-2-4">
          <font color="<?php echo $detail_color; ?>" class="gen-2"><?php echo $row->contact_name; ?></font>
        </div>
        <div class="aard-ad-2-3">
          <div align="left" class="gen-5">
            <b><font color="<?php echo $font; ?>">Contact Tel. No:</font></b>
          </div>
        </div>
        <div class="aard-ad-2-4">
          <font color="<?php echo $detail_color; ?>" class="gen-2"><?php ?>
          
         <style type="text/css">
	#box11{
	visibility:visible;
	}
	#box22 {
	visibility:hidden;
	margin-top:-20px;
	text-decoration:none;
	}
	</style>
<script language="JavaScript">
	function changeb(){
	document.getElementById("box11").style.visibility = "hidden";
	document.getElementById("box22").style.visibility = "visible";
	}

	</script>
<div id="box11" onMouseOver="changeb()" >
Hover to show
</div>
<div id="box22" >
<?php echo $row->contact_tel; ?>
</div></font>
</div>
        <div class="aard-ad-2-3">
          <div align="left" class="gen-5">
            <b><font color="<?php echo $font; ?>">Contact Email:</font></b>
          </div>
        </div>
        <div class="aard-ad-2-4">
          <font color="<?php echo $detail_color; ?>" class="gen-2">
<style type="text/css">
	#box1{
	visibility:visible;
	
	}
	#box2 {
	visibility:hidden;
	margin-top:-20px;
	text-decoration:none;
	}
	</style>
<script language="JavaScript">
	function change(){
	document.getElementById("box1").style.visibility = "hidden";
	document.getElementById("box2").style.visibility = "visible";
	}
	

	</script>

<div id="box1" onMouseOver="change()" >
Hover to show
</div>
<div id="box2" >

<?php 
	echo $row->contact_email;
?>
</div>
</font>
        </div>
        <div class="aard-ad-2-3">
          <div align="left" class="gen-5">
            <b><font color="<?php echo $font; ?>">Date Submitted:</font></b>
          </div>
        </div>
        <div class="aard-ad-2-4">
          <font color="<?php echo $detail_color; ?>" class="gen-2"><?php echo $row->date_created; ?></font>
        </div>
        <div class="aard-ad-2-3">
          <div align="left" class="gen-5">
            <b><font color="<?php echo $font; ?>">
            
            
      
    <?php 
$id = $row->id;
$result = mysql_query("SELECT ad_post FROM jos_aard_ads WHERE id ='$id'");
while($ad = mysql_fetch_array($result))
  {
  $adpost = $ad['ad_post'];

  }
 
  $adsub = substr($adpost, 0, 4);
    //echo '<b>Post Code:</b> ' . $adpost;
if ($_POST) {
	$userpost = $_POST["userpost"];
    $usersub = substr($userpost, 0, 4);
  
}
else {
	$usersub = '';
}
    

//ad postcode
$result = mysql_query("SELECT x, y FROM jos_aard_post WHERE postcode = '$adsub' LIMIT 1");
$row = mysql_fetch_array($result);
	$gridx[0] = $row['x'];
	$gridy[0] = $row['y'];
	
//user postcode
$result = mysql_query("SELECT x, y FROM jos_aard_post WHERE postcode = '$usersub' LIMIT 1");
$row = mysql_fetch_array($result);
	$gridx[1] = $row['x'];
	$gridy[1] = $row['y'];
	
//Get Distances x y
if ($gridx[1] > $gridx[0]) {
$distancex = $gridx[1] - $gridx[0];
}
else {
	$distancex = $gridx[0] - $gridx[1];
}
if ($gridy[1] > $gridy[0]) {
	$distancey = $gridy[1] - $gridy[0];
}
else {
	$distancey = $gridy[0] - $gridy[1];
}
echo '';
if ($usersub == '') {
	
}
else {
$dist = sqrt(($distancex * $distancex) + ($distancey * $distancey));
$out = '<font color="' . $detail_color . '">' . $adpost . ' to ' . $userpost . ' is ' . round($dist / 1000, 1) . 'km approx. </font>';

}
if ($map == 1) {
	echo '<a target="_blank" href="components/com_aardvertiser/aardmapv2.php?adpost=' . $adpost.'">Get directions with Google Maps</a>
	
	
	
	      </font></b>
          </div>
        </div>
        <div class="aard-ad-2-4">
         &nbsp;
        </div>
        <div class="gen-4"></div>
      </div>
    </div>
	
	';
}
if ($find == 1) {?><div align="left" ><b><font color="<?php echo $font; ?>">Distance Finder: </font></b>
	
    <?php  if ($_POST) {
      echo $out;
	//if ($map == 0) {
 //     } else {
//	      echo '<a target="_blank" href="components/com_aardvertiser/aardmapv2.php?userpost='. $userpost . '&adpost=' . $adpost.'">(directions)</a></font>';
 //     }
	    } else {
	    	echo '<form action="' . $postlink . '" method="POST" id="postcode" name="postcode" onsubmit="return postit()">
<font color="' . $detail_color . '"> Enter your Postcode: </font><input type="text" name="userpost" size="10" maxlength="10" />
<input type="submit" value="Submit" />
</form>';
	    }
?><?php }
else {
//do nothing
}?>
    </div>
    <br />
  	<div align="left" ><a href="<?php echo $link; ?>">&lt; return to category</a></div>
  	<br />
	
	<?php 	
	include 'footer.php';
}
function editAd($row, $lists, $option, $currency,$data)
	{
		$editor =& JFactory::getEditor();
		?>
		<script language="javascript" type="text/javascript">
<!--
function submitbutton(pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'cancel') {
                submitform( pressbutton );
                return;
        }
 
        <?php
                $editor =& JFactory::getEditor();
                echo $editor->save( 'ad_desc' );
        ?>
        submitform(pressbutton);
}
//-->
</script>

<script type="text/javascript">
function validate_email(field,alerttxt)
{
with (field)
  {
  apos=value.indexOf("@");
  dotpos=value.lastIndexOf(".");
  if (apos<1||dotpos-apos<2)
    {alert(alerttxt);return false;}
  else {return true;}
  }
}

function validate_required(field,alerttxt)
{
with (field)
  {
  if (value==null||value=="")
    {
    alert(alerttxt);return false;
    }
  else
    {
    return true;
    }
  }
}

function validate_form(thisform)
{
with (thisform)
  {
  if (validate_required(ad_name,"Ad Title must be filled out!")==false)
  {email.focus();return false;}
  if (validate_required(ad_location,"Location must be filled out!")==false)
  {email.focus();return false;}
  if (validate_required(contact_name,"Contact Name must be filled out!")==false)
  {email.focus();return false;}
  if (validate_required(contact_tel,"Contact Telephone number must be filled out and a number!")==false)
	{email.focus();return false;}
  if (validate_email(contact_email,"Not a valid e-mail address!")==false)
    {email.focus();return false;}
  }
}

contFileInputs = 1;
function addfileinput(){
    contFileInputs = contFileInputs + 1;
    var fileInput = document.createElement('input');
    var placeHolder = $('files');
    fileInput.setAttribute("type", "file");
    fileInput.setAttribute("name", "imag-" + contFileInputs);
    placeHolder.appendChild(fileInput);
}
</script>
		<form action="index.php?option=com_aardvertiser&task=save" enctype="multipart/form-data" onsubmit="return validate_form(this);" method="post" name="adminForm" id="adminForm">
			<fieldset class="adminForm">
				<legend>Ad Details</legend>
				<table class="admintable">
				<tr>
					<td width="153" align="right" class="key">
					Ad Title: 
					</td>
					<td width="334">
					<input class="text_area" type="text" name="ad_name" id="ad_name" size="40" maxlength="250" value="<?php echo $row->ad_name; ?>" />
					</td>
				</tr><tr>
					<td width="153" align="right" class="key">
					Category: 
					</td>
					<td>
					<?php 
					echo $lists['cat_name'];
					?>
					</td>
				</tr>
                                <tr>
					<td width="100" align="right" class="key">
					<?php echo JText::_('SYSTEM_AD_DATE_EXPIRATION') . ':'; ?>
					</td>
					<td>
					<?php echo JHTML::calendar($data['expirationdate'],'date_expiration','date_expiration','%Y-%m-%d',array('class' => 'date')); ?>
				</tr>
                                <tr>
					<td width="100" align="right" class="key">
					<?php echo JText::_('SYSTEM_AD_TYPE') . ':'; ?>
					</td>
					<td>
					<?php echo $lists['ad_type']; ?>
				</tr>
                                <tr>
					<td width="153" align="right" class="key">
					Description: 
					</td>
					<td>
					<?php 
					echo $editor->display( 'ad_desc', $row->ad_desc , '65%', '115', '40', '4');
					?>
					</td>
				</tr><tr>
					<td width="153" align="right" class="key">
					Item Asking Price:
					</td>
					<td>
					<?php echo $currency; ?><input class="text_area" type="text" name="ad_price" id="ad_price" size="7" maxlength="10" value="<?php echo $row->ad_price; ?>" />
					</td>
				</tr><tr>
					<td width="153" align="right" class="key">
					For Sale or Wanted?
					</td>
					<td>
					<?php echo $lists['ad_state']; ?>
					</td>
				</tr><tr>
					<td width="153" align="right" class="key">
					Method Of Transport: 
					</td>
					<td>
					<?php echo $lists['ad_delivery']; ?>
					</td>
				</tr><tr>
					<td width="153" align="right" class="key">
					Location of Item: <br></td>
					<td>
					<input class="text_area" type="text" name="ad_location" id="ad_location" size="30" maxlength="100" value="<?php echo $row->ad_location; ?>" />
					</td>
				</tr><tr>
					<td width="153" align="right" class="key">
					Location Postcode: <br></td>
					<td>
					<input class="text_area" type="text" name="ad_post" id="ad_post" size="10" maxlength="10" value="<?php echo $row->ad_post; ?>" /> <i>Format : XX11 1XX or XX1 1XX (UK Only)</i>
					</td>
				</tr><tr>
					<td width="153" align="right" class="key">
					Contact Name: 
					</td>
					<td>
					<input class="text_area" type="text" name="contact_name" id="contact_name" size="50" maxlength="250" value="<?php echo $row->contact_name; ?>" />
					</td>
				</tr><tr>
					<td width="153" align="right" class="key">
					Contact Telephone No:
					</td>				
					<td>
					<input class="text_area" type="text" name="contact_tel" id="contact_tel" size="30" maxlength="50" value="<?php echo $row->contact_tel; ?>" />
					</td>
				</tr><tr>
					<td width="153" align="right" class="key">
					Contact Email Address: 
					</td>
					<td>
					<input class="text_area" type="text" name="contact_email" id="contact_email" size="40" maxlength="255" value="<?php echo $row->contact_email; ?>" />
					</td>
				</tr>
                                <tr>
					<td width="100" align="right" class="key">
					<?php echo JText::_('SYSTEM_AD_CONTACT_ADDRESS') . ':'; ?>
					</td>
					<td>
					<input class="text_area" type="text" name="contact_address" id="contact_address" size="65" maxlength="255" value="<?php echo $row->contact_address; ?>" />
					</td>
				</tr>
                                <tr>
					<td width="153" align="right" class="key">
					Images:
					</td>
					<td>
                                        <div id="files">
                                            <input size="25" name="<?php if ($row->id > 0) {
					echo "";
	} else {
	echo "img1";
	}?>" type="file" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10pt" class="box"/>
                                        </div>
                                        <button value="<?php echo JText::_('SYSTEM_AD_IMAGE_ADD'); ?>" onclick="addfileinput();"></button>
                                        <br />If resubmitting but not changing images leave fields blank
					</td>
				</tr><tr>
					<td width="153" align="right" class="key">
					
					
					</td><td>
					<?php echo JHTML::_( 'form.token' ); ?>
<button type="button" onclick="submitbutton('save')"><?php echo JText::_('Save') ?></button>
					</td>
				</tr>
				</table>
			</fieldset>
			<?php $date = (date("Y-m-d H:i:s")); ?>
			<input type="hidden" name="date_created" value="<?php 
			if (!$row->date_created) {
			echo $date;	
			}?>" />
			<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="published" value="1" />
				
			<?php 
			$user =& JFactory::getUser();
			$user_id = $user->get( 'id' );
			?>
			<input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
		</form>
		
<?php 
include 'footer.php';
	}
	function showmyAds($rows, $names, $dayy, $currency, $option)
{
	if(!$rows) {
	echo '<p>You have no ads currently submitted.</P>';
	}
	else {
		foreach ($names as $name)
	{
		echo '<table height="1%"><tr><td valign="middle"><div class="componentheading" 
		style="margin-bottom:7px;">' . $name->name . '&acute;s Advertisements</div></td>
		<td valign="middle">
		 - <a href="index.php?option=com_aardvertiser&task=add">Advertise more items</a></td>
		 </tr></table>';
	}
	echo '<table width="100%">
	<tr bgcolor="#CCCCCC"><td><b>Ad Name</b></td><td><b>Category</b></td>
	<td><b>Sale / Wanted</b></td><td><strong>Price</strong></td><td>
	<strong>Date Submitted</strong></td><td><strong>It has been saw</strong></td>
        <td><b>Remove?</b></td></tr>';
	foreach ($rows as $row)
	{
	$link = JFilterOutput::ampReplace('index.php?option=' . $option . '&task=edit&cid[]=' . $row->id);
	$today = date("Y-m-d H:i:s", mktime(date('H'),date('i'),date('s'),date('m'),date('d')-$dayy,date('y')));
	if ($row->date_created < $today) {
	$bgcolor = '#FFC18F'; 
	}
	else 
	{ 
	$bgcolor = '#FFFFFF'; 
	}
	$linkdel = JFilterOutput::ampReplace('index.php?option=' . $option . '&task=remove&cid[]=' . $row->id);
	?> 
		<script type="text/javascript">
function show_confirm()
{
return confirm("Are you sure you want to delete this record?");
}
</script>
<?php echo'
		<tr bgcolor="' . $bgcolor . '"><td><a href="' . $link . '">' . $row->ad_name . '</a></td><td>
		 ' . $row->cat_name . '</td><td>
		 ' . $row->ad_state . '</td><td>'
		  . $currency . $row->ad_price . '</td><td>
		 ' . $row->date_created . '</td><td>
                     ' . $row->impressions . '</td><td>
		 
		 <a onclick="return show_confirm()" href="' . $linkdel . '"><img height="20px" width="20px" src="images/cancel_f2.png" /></a></td></tr>';
	}
	echo '</table>
	<br /><table><tr><td bgcolor="#FFC18F">
	 Ads shown in this colour are out of date, to renew them, click on their title and resubmit them. <br />
	 If you want to change the images on an ad, please resubmit the ad and delete the old one.</td></tr>
	 </table><BR><BR><BR>';
	}
	include 'footer.php';
}
}
?>