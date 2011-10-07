<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');
require (JPATH_BASE.DS.'components'.DS.'com_eqzonales'.DS.'helper.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_eqzonales'.DS.'controllers'.DS.'eq.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_eqzonales'.DS.'controllers'.DS.'band.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'helper'.DS.'helper.php');
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'tables');

setlocale(LC_ALL, 'es_AR.utf8');
setlocale(LC_ALL,"es_ES@america","es_ES","buenos_aires");

$eq_z_com=new EqZonalesControllerEq();
$eq_z_com->addModelPath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'models' );
$user=&JFactory::getUser();

$eq_actual=$eq_z_com->retrieveUserEqImpl($user->id);

	if(!$zonal):
		$zonal_com=new comZonalesHelper;
		$zonal_actual_label= $zonal_com->getZonalActualLabel();
	else:
		$zonal_actual_label=$zonal->label;
	endif;

include ('nocache.php');
?>
<!-- 26042011 -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
	<jdoc:include type="head" />
	<jdoc:include type="modules" name="head" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/ie.css" />
	<![endif]-->	
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/reset.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/main.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/modules.css" type="text/css" />	
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/mod_eq.css" type="text/css" />	
		<?php JHTML::_('behavior.mootools'); ?> 
	<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/z20/js/swfobject.js"></script>
	<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/z20/js/zonales.js"></script>
</head>

<body>

<div id="wrapper-web">

<div id="topBar">
		<jdoc:include type="modules" name="header"/>
</div><!-- #topBar -->
<form name="ver_portada" action="/" method="post" style="display:none">
		<input type="hidden" name="task" value="clearZonal" />
		<input type="hidden" name="option" value="com_zonales" />
		<?php echo JHTML::_('form.token'); ?>
</form>
	<div id="header">
		<div id="logo-header">
	  <a id="ver_portada" href="#portada">
			<img id="logo" src="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/images/logo.gif" alt="Zonales.com" />
	  </a>
	  
	  
		
		</div><!-- end #logo-head -->
		
		<div id="header-contentRight">
			<div id="wrapper-search">
				<div id="share">
		
		<?php if(isset($zonal_actual_label)): ?>
						<div id="zonal-actual"><p><?php echo $zonal_actual_label; ?></p></div>
					<?php endif; ?>
		
				<?php $arr_codificacion = iconv_get_encoding();
					$codificacion = $arr_codificacion['internal_encoding'];
				?>
		  <p>
					<?php echo utf8_encode(ucfirst(strftime("%A %d.%m.%Y")));?><br /><span id="hora"></span></p>
		  <!--
					<p style="text-transform:uppercase;">
						<strong>Compartinos:</strong> 
						<a href="http://www.facebook.com/pages/Zonales/139612986080624">
							<img src="<?php echo $this->baseurl ?>/images/social/facebook_16x16.png" alt="Zonales en Facebook" />
						</a> 
						<a href="http://twitter.com/zonalizate">
							<img src="<?php echo $this->baseurl ?>/images/social/twitter_16x16.png" alt="Zonales en Twitter" />
						</a> 
						<a href="mailto:contacto@zonales.com">
							<img src="<?php echo $this->baseurl ?>/images/social/email_16x16.png" alt="Contactanos por Email" />
						</a>
					</p>
					-->
		  
				</div>
				<div id="module-searchTop">
					<jdoc:include type="modules" name="topSearch" /><br/>
					
          <div>
          <a href="http://www.facebook.com/zonales" title="Facebook" target="_blank"><img src="<?php echo $this->baseurl ?>/templates/z20/images/btn_facebook.jpg" alt="Facebook" style="margin-top:5px;" /></a>
          <a href="http://twitter.com/zonalizate" title="Twitter" target="_blank"><img src="<?php echo $this->baseurl ?>/templates/z20/images/btn_twitter.jpg" alt="Twitter" style="margin-top:5px;" /></a>
          </div>
          
        </div><!-- #module-searchTop -->
		
		<div class="clear"></div>
		
			</div><!-- #wrapper-search -->
			
			<div id="banner-botonTop">
				<jdoc:include type="modules" name="bannerTop" />
			</div>
			
			<div class="clear"></div>
						
		</div><!-- end #header-contentRight -->
		
		<div class="clear"></div>
		
	<div id="topNav">
		<jdoc:include type="modules" name="top" />
	</div><!-- end #topNav -->
	
	</div><!-- #header -->
	
	<div id="wrapper-body">
		
		<div id="wrapper-content">
			 <div id="equalizer-bt">
								<div id="eq-link">
										<img src="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/images/eq.png" alt="Ecualizador" />
								</div>
								<div id="equalizer" style="opacity:0;">
 								   <jdoc:include type="modules" name="equalizer" />
								</div>
				<script type="text/javascript">
				var equalizer_fx=new Fx.Styles($('equalizer-bt'), {duration: 1000});
				var module_fx=new Fx.Styles($('equalizer'), {duration: 1000});
				
				window.addEvent('domready', function(){
					$('ver_portada').addEvent('click', function(){
						document.forms['ver_portada'].submit();
					});
				});

				$('eq-link').addEvent('click',function(e){
					if($('equalizer').getStyle('display')=="none"){
						e.stop;
						$('equalizer').setStyles({
							visibility:'visible',
							display:'block',
							height:'345px'
							});
						module_fx.start({opacity:'1'});
						equalizer_fx.start({width:'599px'});
						$('mod_eq_main_div').setStyles({
							display:'block'
						});
<?php

if(isset($eq_actual) && $eq_actual != null):
	$eq=$eq_actual[0]->eq;
	$bands=$eq_actual[0]->bands;
	$bands_js;

	if(!empty($eq) && !empty($bands)){
		
		foreach($bands as $band):
			
			$bands_js.="var mySlide".$band->id." = new Slider($('area".$band->id."'), $('knob".$band->id."'), {\n".
					"	steps: 100,\n".
					"	onChange: function(step) {\n".
					"		$('upd".$band->id."').setHTML(step);\n".
					"		$('".$band->id."-".$band->cp_value_id."').value = '".$band->id."-".$band->cp_value_id."-' + step +'-MOD';\n".
			"	}\n".
			"}).set(".$band->peso.");\n";

		endforeach;

		echo $bands_js;
	}

endif;
?>
						
					}else{	
						equalizer_fx.start({width:'350px'});
						
						module_fx.start({opacity:'0'}).chain(function(){
							$('equalizer').setStyles({
								display:'none',
								height:'345px'
							});
						});
					}
					
				});


				</script>

						</div>		
	
			<div id="mainContent">
				<jdoc:include type="component" />
			</div>
			
	  <div id="otherContent">

<!-- -->


<div id="fl_grindetti" style="margin-bottom:10px;">zonales.com</div>

<script type="text/javascript">// <![CDATA[
				var so = new SWFObject("/images/banners/grindetti_zonales_300_x_250.swf", "grindetti", "300", "250", "8");
				so.addParam("scale", "noscale");
				so.addParam("allowScriptAccess", "sameDomain");
				so.addVariable("clickTag", "http://www.nestorgrindetti2011.com.ar/");
				so.write("fl_grindetti");
// ]]></script>


<div id="fl_cdba" style="margin-bottom:10px;">zonales.com</div>

<script type="text/javascript">// <![CDATA[
				var so = new SWFObject("/images/banners/cdba_300x250.swf", "cdba", "300", "250", "8");
				so.addParam("scale", "noscale");
				so.addParam("allowScriptAccess", "sameDomain");
				so.addVariable("clickTag", "http://agendacultural.buenosaires.gob.ar/");
				so.write("fl_cdba");
// ]]></script>

<!-- -->
    
	  	<div id="lavoz">
		
				<!-- # acá van las notas de los vecinos... ver index_tmpl.html -->
		
		</div><!-- end #lavoz -->
 		  	<jdoc:include type="modules" name="other" />	 

			</div><!-- end #otherContent -->
			
			<div class="clear"></div>
			
		</div><!-- end #wrapper-content -->
		
		<div id="wrapper-right">
	
		<div id="module-loginRight">
	  	
		<!-- acá va el módulo de login... tal vez los div "module-loginRight" se pueden eliminar -->
				<jdoc:include type="modules" name="right" />
			
	  </div><!-- end #module-loginRight -->
	
			<!-- acá van los módulos de banners... que también podrían ir todos en la posición "right" -->
				<jdoc:include type="modules" name="bannerRight" />
				<div>
					<a href="http://www.facebook.com/zonales"><img src="images/bot_facebook.jpg" alt="Facebook" /></a>
				</div>
				<div>
					<a href="http://twitter.com/zonalizate"><img src="images/bot_twitter.jpg" alt="Twitter" /></a>
				</div>
		</div><!-- end #wrapper-right -->
		
		<div class="clear"></div>
		
	</div><!-- end #wrapper-body -->
	
	<div id="footer">
		<div id="menuFooter"><jdoc:include type="modules" name="top" /></div><!-- end #menuFooter -->
		
		<p class="copy">Copyright <?php echo date('Y');?> - Zonales.com - www.zonales.com - <a href="mailto:info@zonales.com">info@zonales.com</a></p>
		<jdoc:include type="modules" name="footer" />
	</div><!-- end #footer -->
<script type="text/javascript">
window.addEvent('domready', function() {
	updateClock(); 
	setInterval('updateClock()', 1000 );
});
</script>	
</div><!-- end #wrapper-web -->
</body>
</html>
