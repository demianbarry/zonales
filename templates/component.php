<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php echo '<?xml version="1.0" encoding="utf-8"?'.'>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />

<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/main.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/content.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/modules.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/glassnav.css">

<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/js/swfobject.js"></script>

</head>

<body>

<center>
<div id="wrapper">
	<div id="top">
		<div id="logoTop" style="float:left;"></div><!-- END #logoTop -->
    
    <div style="float:right; padding-right:228px;">
    	<jdoc:include type="modules" name="topCenter" style="xhtml" />
    </div>
    
  </div><!-- END #top -->  

  <div id="mainMenu">
    <jdoc:include type="modules" name="top" style="xhtml" />
  </div><!-- end #mainMenu -->

	<div class="lineSplit" style="background:url(<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/images/linea_03.gif); height:10px; display:block; font-size:0; margin-bottom:10px;"></div>
  <div id="containerContent">
    <div id="container-l">
      <div id="mainContent">
        <jdoc:include type="component" />
        <jdoc:include type="modules" name="main" style="xhtml" />    
      </div><!-- END #mainContent -->	
      <div id="otherContent">
        <jdoc:include type="modules" name="other" style="xhtml" />
      </div><!-- END #otherContent -->	
      <div class="clear"></div>
    
    	<jdoc:include type="modules" name="cols" style="xhtml" />
      
    </div><!-- END #container-l -->
    <div id="container-r">
      <div id="bannersContent">
        <jdoc:include type="modules" name="right" style="xhtml" />
      </div><!-- END #bannersContent -->
    </div><!-- END #container-r -->
    <div class="clear"></div>
  </div><!-- END #containerContent -->
  

  
  <div id="bottom">
  	<div id="logoBottom">
	  </div><!-- END #logoBottom -->
  </div><!-- END #bottom -->
  
  <div id="buttons_bottom">
  	<div id="buttons_bottom-inner">
	    <jdoc:include type="modules" name="bottom" style="xhtml" />
  	</div><!-- END #buttons_bottom-inner -->
  </div><!-- END #buttons_bottom -->
  
  <div id="copy">
    Copyright 2009 ZONALES.COM.AR | Todos los derechos reservados.
  </div><!-- END #copy -->
</div><!-- END #wrapper -->
</center>

</body>
</html>
