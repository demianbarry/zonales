<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.user.helper');
require_once (JPATH_BASE . DS . 'components' . DS . 'com_zonales' . DS . 'helper.php');
require_once (JPATH_BASE . DS . 'components' . DS . 'com_zonales' . DS . 'ZContext.php');
require (JPATH_BASE . DS . 'components' . DS . 'com_eqzonales' . DS . 'helper.php');
require_once(JPATH_BASE . DS . 'components' . DS . 'com_eqzonales' . DS . 'controllers' . DS . 'eq.php');
require_once(JPATH_BASE . DS . 'components' . DS . 'com_eqzonales' . DS . 'controllers' . DS . 'band.php');
require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_eqzonales' . DS . 'helper' . DS . 'helper.php');
JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_eqzonales' . DS . 'tables');

setlocale(LC_ALL, 'es_AR.utf8');
setlocale(LC_ALL, "es_ES@america", "es_ES", "buenos_aires");

$eq_z_com = new EqZonalesControllerEq();
$eq_z_com->addModelPath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_eqzonales' . DS . 'models');
$user = &JFactory::getUser();

$eq_actual = $eq_z_com->retrieveUserEqImpl($user->id);

if (!isset($zonal) || !$zonal):
    $zonal_com = new comZonalesHelper;
    $zonal_actual_label = $zonal_com->getZonalActualLabel();
else:
    $zonal_actual_label = $zonal->label;
endif;

//include ('nocache.php');
$mainframe = JFactory::getApplication();
$session = JFactory::getSession();
$zCtx = $session->get('zCtx');
if (!isset($zCtx)) {
    $zCtx = new ZContext();
    $session->set('zCtx', $zCtx);
}
JHTML::_('behavior.modal');
?>
<!-- 26042011 -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Zonales</title>
<!--        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />-->
<!--        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/reset.css" type="text/css" />-->
<!--        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/main.css" type="text/css" />-->
<!--        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/general.css" type="text/css" />-->
<!--        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/modules.css" type="text/css" />-->
<!--        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/mod_eq.css" type="text/css" />-->
<!--        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/system/css/ZoneStyle.css" type="text/css"/>-->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
            <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
                <link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow' rel='stylesheet' type='text/css'>
                    <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>


                        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/reset.css" />
                        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/main.css" />
                        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/content.css" />
                        <script type="text/javascript" src="<?php echo $this->baseurl ?>/media/system/js/mootools-core.js"></script>
                        <script type="text/javascript" src="<?php echo $this->baseurl ?>/media/system/js/mootools-more.js"></script>

                        <script language="javascript" type="text/javascript" src="components/com_zonales/map2.js"></script>
                        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
                        <script type="text/javascript" src="/media/system/js/OpenLayers.js"></script>
                        <!--
                        <script language="javascript" type="text/javascript" src="http://<?php echo gethostbyname('g2p2-node') ?>:4000/socket.io/socket.io.js"></script>
                        -->
                        <script language="javascript" type="text/javascript" src="http://192.168.0.2:4000/socket.io/socket.io.js"></script>
                        <script language="javascript" type="text/javascript">
                            var loguedUser = ['<?php echo implode("','", (JUserHelper::getUserGroups(JFactory::getUser()->get('id')))) ?>'];
                        </script>
                        </head>

                        <body>

                            <div id="top_bar" style="">
                                <div id="top_bar_left">
                                    <a href="#" title="Zonales"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/img/zonales_logo.png" alt="Zonales" id="logo_top" /></a>
                                </div><!-- #top_bar_left -->

                                <jdoc:include type="modules" name="header"/>
                                <jdoc:include type="modules" name="login"/>

                                <div class="clear"></div>
                            </div><!-- /#top_bar -->

                            <div id="content-only">
                                <div class="border1">                
                                    <div class="padding10">
                                        
                                        <jdoc:include type="component" />                        

                                    </div><!-- /.padding10 -->                
                                </div><!-- /.border1 -->
                            </div><!-- /#content -->

                            <div id="footer">
                                <div class="padding10">
                                    <p><a href="#">TÃ©rminos y condiciones</a> | <a href="#">Reclamos y consultas</a><br />
                                        Copyright 2012 - Zonales.com - www.zonales.com - info@zonales.com</p>
                                </div>
                            </div><!-- /#footer -->

                        </body>
                        </html>
