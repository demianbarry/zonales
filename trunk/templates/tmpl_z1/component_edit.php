<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php echo '<?xml version="1.0" encoding="utf-8"?'.'>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
<jdoc:include type="head" />

        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/reset.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/content.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/modules.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/glassnav.css" />

        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/_<?php echo $this->params->get('mainColor'); ?>.css" />

        <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/js/swfobject.js"></script>
        <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/js/window.js"></script>

        <?php JHTML::_('behavior.mootools'); ?>

    </head>

    <body>

        <center>
            <div id="wrapper">
                <div id="top">

                    <jdoc:include type="modules" name="topSearch" style="xhtml" />

                    <div id="mainMenu">
                        <jdoc:include type="modules" name="top" style="xhtml" />
                    </div><!-- end #mainMenu -->

                </div><!-- END #top -->

                <div class="lineSplit" style="background:url(<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/images/linea_03.gif); height:10px; display:block; font-size:0; margin-bottom:10px;"></div>

                <div id="containerContent">

                    <div style="margin-left:10px;">
                        <div id="container-l">
                            <div id="mainContent">
                                <jdoc:include type="component" />
                                <jdoc:include type="modules" name="main" style="xhtml" />
                            </div><!-- END #mainContent -->
                            <div class="clear"></div>
                        </div><!-- END #container-l -->
                    </div>

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

                <div id="w3c">
                    <a href="http://validator.w3.org/check?uri=referer"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/images/w3c_xhtml_01.gif" alt="Valid XHTML 1.0 Transitional" /></a>
                </div><!-- END #w3c -->

            </div><!-- END #wrapper -->
        </center>

        <?php
        $user =& JFactory::getUser();
        if ($user->guest):
            ?>
        <script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/es_LA"></script>
    <script type="text/javascript">FB.init("91778705a13235cd3efe59d31e4d31bf","index.php?option=com_user&task=login&provider=Facebook/xd_receiver.htm");</script>
        <?php endif ?>
    </body>
</html>
