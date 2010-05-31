<?php echo '<?xml version="1.0" encoding="utf-8"?'.'>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" xmlns:fb="http://www.facebook.com/2008/fbml" >
<script language="JavaScript">

window.moveTo(0,0);
window.resizeTo(screen.width,screen.height);

</script>
    <head>
<jdoc:include type="head" />

<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/reset.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/main.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/content.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/modules.css" />

<?php JHTML::script('swfobject.js'); ?>
</head>

<body>

<div>
  <jdoc:include type="modules" name="user9" style="xhtml" />
</div><!-- END #map -->

<div style="width:900px; height:560px; margin-top:80px;">
  <jdoc:include type="modules" name="index" style="xhtml" />
</div><!-- END #map -->

<div id="copy" style="padding-left:60px; border:none;">
  Copyright 2009 ZONALES.COM.AR | Todos los derechos reservados.
</div><!-- END #copy -->

<div id="w3c" style="padding-left:60px;">
	<a href="http://validator.w3.org/check?uri=referer"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/images/w3c_xhtml_01.gif" alt="Valid XHTML 1.0 Transitional" /></a>
</div><!-- END #w3c -->

<?php
    $user =& JFactory::getUser();
    if ($user->guest):
?>
    <script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/es_LA"></script>
    <script type="text/javascript">  FB.init("91778705a13235cd3efe59d31e4d31bf","index.php?option=com_user&task=login&provider=Facebook/xd_receiver.htm");</script>
<?php endif ?>
</body>
</html>
