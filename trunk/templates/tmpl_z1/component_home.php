<?php echo '<?xml version="1.0" encoding="utf-8"?'.'>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" xmlns:fb="http://www.facebook.com/2008/fbml" >
    <head>
        <jdoc:include type="head" />

        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/reset.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/content.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/modules.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/home.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/_<?php echo $this->params->get('mainColor'); ?>.css" />

        <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/js/window.js"></script>
        <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/js/login_action_switch.js"></script>
        <?php JHTML::script('swfobject.js'); ?>
    </head>

    <body>
        <center>
            <div id="home_top">
            </div>

            <div id="home_zona">
                <div id="home_zona_text">
                    Aca va texto, efectos, imágenes, etc. referidos a la zona
                </div>
                <div id="home_zona_mod">
                    <jdoc:include type="modules" name="home_zona" style="xhtml" />
                </div>

            </div>

            <div id="home_login">
                <div id="home_login_text">
                    Sumate a zonales!!. Textos, imágenes, efectos referidos invitando a los usuarios a Zonales
                </div>
                <div id="home_login_mod">
                    <jdoc:include type="modules" name="home_login" style="xhtml" />
                </div>
            </div>

            <div id="copy">
                Copyright 2009 ZONALES.COM.AR | Todos los derechos reservados. <a href="http://validator.w3.org/check?uri=referer"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/images/w3c_xhtml_01.gif" alt="Valid XHTML 1.0 Transitional" /></a>
            </div><!-- END #copy -->

            <?php
            $user =& JFactory::getUser();
            if ($user->guest):
                ?>
            <script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/es_LA"></script>
            <script type="text/javascript">  FB.init("91778705a13235cd3efe59d31e4d31bf","index.php?option=com_user&task=login&provider=Facebook/xd_receiver.htm");</script>
            <?php endif ?>
        </center>
    </body>
</html>
