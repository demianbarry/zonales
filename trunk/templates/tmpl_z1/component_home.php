<?php echo '<?xml version="1.0" encoding="utf-8"?'.'>'; ?>
<?php include_once 'auth_util.php'; ?>
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

        <style type="text/css">

            #containerContent{
                background:none;
            }

            .miniBox{
                margin-bottom:10px;
                padding:5px;                
            }

            .miniBox .padd{
                padding:5px;
            }

            .miniBox p{
                font-size:11px;
            }

            .miniBox .heading{
                display:block;
                padding:10px;
                margin-bottom:10px;
                background:#009CC9;
            }

            .miniBox .heading h1{
                color:#FFFFFF;
                font-family:Georgia, "Times New Roman", Times, serif;
                font-size:18px;
                font-weight:bold;
                letter-spacing:-1px;
            }

            .miniBox label{
                margin-top: 10px;
                margin-bottom:5px;
                display:block;
            }

            .miniBox select, .miniBox input{
                font-family:Arial, Helvetica, sans-serif;
                font-size:11px;
                color:#666666;
            }

            .miniBox select{
                width:200px;
            }

            .miniBox input{
                width:200px;
            }

            .miniBox input.radio{
                margin-bottom:10px;
                width:0;
            }

            .miniBox input.submit{
                width:100px;
            }
        </style>

        <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/js/window.js"></script>        
        <!--<?php JHTML::script('swfobject.js'); ?>-->
        <?php
        $user =& JFactory::getUser();
        if ($user->guest):
            ?>
        <script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/es_LA"></script>
        <script type="text/javascript">  FB.init("<?php echo getApiKey("Facebook")?>","index.php?option=com_user&task=login&provider=Facebook/xd_receiver.htm");</script>
        <?php endif ?>
    </head>

    <body>
        <center>
            <table>
                <tbody>
                    <tr><td>
                            <div id="wrapper">
                                <div id="containerContent">

                                    <div style="margin-left:10px;">
                                        <div id="container-l">
                                            <div id="mainContent">

                                                <img src="templates/<?php echo $mainframe->getTemplate(); ?>/images/home/zonales_top.jpg" style="margin-left:5px; margin-bottom:10px; width: 98%;" alt="Zonales"/>

                                                <div class="miniBox">
                                                    <jdoc:include type="modules" name="home_zona" style="xhtml" />
                                                </div><!-- .miniBox -->
                                            </div><!-- END #mainContent -->
                                            <div id="otherContent">
                                                <div class="miniBox">
                                                    <jdoc:include type="modules" name="home_login" style="xhtml" />
                                                </div><!-- .miniBox -->
                                                <div class="miniBox">
                                                    <jdoc:include type="modules" name="home_register" style="xhtml" />
                                                </div><!-- .miniBox -->

                                            </div><!-- END #otherContent -->
                                            <div class="clear"></div>

                                        </div><!-- END #container-l -->
                                    </div>

                                </div><!-- END #containerContent -->
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <div id="copy">
                                Copyright 2009 ZONALES.COM.AR | Todos los derechos reservados. <a href="http://validator.w3.org/check?uri=referer"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $mainframe->getTemplate(); ?>/images/w3c_xhtml_01.gif" alt="Valid XHTML 1.0 Transitional" /></a>
                            </div><!-- END #copy -->
                        </td>
                    </tr>
                </tfoot>
            </table>
        </center>
    </body>
</html>
