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

<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/js/swfobject.js"></script>

<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/js/glassnav.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/glassnav.css">


</head>

<body>

<div id="wrapper">
	<div id="top">
		<div id="logoTop"></div><!-- END #logoTop -->
    
    <div style="position:absolute; margin-left:280px;">
    	<jdoc:include type="modules" name="topCenter" style="xhtml" />
    </div>
    
    <div style="position:absolute; margin-left:808px; margin-top:5px;">
	    <jdoc:include type="modules" name="topRight" style="xhtml" />
    </div>
  
  </div><!-- END #top -->  

  <!-- glassmenu -->
  <div id="navigation">
  <ul id="mymenu">
    <li id="s0"><a href="<?php echo $this->baseurl ?>">Inicio</a></li>
    <li id="s1"><a href="#">Noticias</a></li>
    <li id="s2"><a href="index.php?option=com_content&view=section&layout=blog&id=3&Itemid=15">Agenda</a></li>
    <li id="s3"><a href="index.php?option=com_content&view=category&layout=blog&id=2&Itemid=6">La Voz del Vecino</a></li>
  </ul>
  </div>
  <div id="sublinks">
    <ul id="s0_m"></ul>
    <ul id="s1_m">
      <jdoc:include type="modules" name="sub1" style="xhtml" />
    </ul>
      <ul id="s2_m"></ul>
      <ul id="s3_m"></ul>
  </div>
  <!-- glassmenu -->

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
  
  <div id="footer">
    <div id="footer-l">
    	<jdoc:include type="modules" name="footer" style="xhtml" />
    </div><!-- END #footer-l -->
    <div id="footer-r">
    	Zonales.com.ar
    </div><!-- END #footer-r -->
    <div class="clear"></div>
  </div><!-- END #footer -->
  <div id="copy">
    Copyright 2009 ZONALES.COM.AR | Todos los derechos reservados.
  </div><!-- END #copy -->
</div><!-- END #wrapper -->

</body>
</html>
