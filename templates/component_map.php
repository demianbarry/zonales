
<?php echo '<?xml version="1.0" encoding="utf-8"?'.'>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />

<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/main.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/content.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/modules.css">

<?php JHTML::script('swfobject.js'); ?>
</head>

<body>

<div id="map" style="margin:auto; width:800px; height:560px;">
  No se puede cargar la pel&iacute;cula.
</div><!-- END #map -->
<script type="text/javascript">
  // <![CDATA[
  var so = new SWFObject("<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/swf/inicio.swf", "top_nav", "800", "560", "8", "#FFFFFF");
  so.addParam("scale", "noscale");
  // so.addParam("wmode", "transparent");
  so.write("map");
  // ]]>
</script>


</body>
</html>
