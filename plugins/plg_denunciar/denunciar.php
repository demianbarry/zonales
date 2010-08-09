<?php
/**
 * @version		$Id: vote.php 10381 2008-06-01 03:35:53Z pasamio $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$database = &JFactory::getDbo();
$sql		= "CREATE TABLE IF NOT EXISTS `#__denunciar_tb` (
                    `id` int(11) NOT NULL auto_increment,
                     comment longtext,
                     `content_id` int(11) default NULL,
                     `user_id` int(11) default NULL,
                     `state` tinyint(1) default 0, 
                     `created` datetime default '0000-00-00 00:00:00',
                     PRIMARY KEY  (`id`))
                     ENGINE = MYISAM AUTO_INCREMENT =1 DEFAULT CHARSET = utf8 AUTO_INCREMENT =1;";
$database->setQuery($sql);
$database->query();

$user	= & JFactory::getUser();

$plugin = & JPluginHelper::getPlugin('content', 'denunciar');
$pluginParams   = new JParameter( $plugin->params );

$task = JRequest::getVar('task', '', 'post');
switch ($task) {
    case "denunciar":
        $contentid = JRequest::getVar('content_id', '', 'post');
        $denuncia = JRequest::getVar('denuncia', '', 'post');

        // registro la denuncia
        $sql = "INSERT INTO #__denunciar_tb (user_id, content_id,comment,created, state)
				    VALUES($user->id, $contentid,'$denuncia','".gmdate('Y-m-d H:i:s')."',0)" ;
        $database->setQuery($sql);
        $database->query();

        // despublico el artículo
        /*$sql = "UPDATE #__content
                    SET state = ".$pluginParams->get('state').",
                        modified = '".gmdate('Y-m-d H:i:s')."'
                    WHERE id = $contentid";
        $database->setQuery($sql);
        $database->query();*/

        $from = "denuncia@zonales.com.ar";

        $fromname = JText::_('Denuncia - Zonales');

        $recipient[] = $pluginParams->get('recipient');

        $subject = JText::_('El artículo Nº ').$contentid.JText::_(' fue denunciado.');

        $pageURL = 'http';
        // if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }

        $body = '
			<p>'.JText::_('Señor editor:').'</p>
			<p>'.JText::_('El artículo nº ').'<a href="'.$pageURL.'index.php?option=com_content&view=article&id='.$contentid.'&tmpl=component_edit">'.$contentid.'</a>'.JText::_(' ha sido denunciado por un usuario, por el siguiente motivo:').'</p>
                        <p>'.$denuncia.'</p>
			';

        $mode = 1;

        $replyto = 'no_reply@zonales.com.ar';

        $replytoname = JText::_('NO REPLY - Zonales');

        JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode,'','', '', $replyto, $replytoname);

        //Lanzo un full import de Solr si se denunció un artículo.
        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'helper'.DS.'helper.php');
        $helper = new comEqZonalesHelper();
        $helper->launchSolrImport(true);
        break;

}

$denunciardisplay = $pluginParams->get('denunciardisplay');


$mainframe->registerEvent( $denunciardisplay, 'plgContentdenunciar' );
$mainframe->registerEvent( 'onAfterContentSave', 'plgContentPublish' );

function denunciarPageURL() {
    $pageURL = 'http';
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}



function plgContentdenunciar( &$row, &$params, $page=0 ) {

    $url = JUri::base(true);
    $uri = & JFactory::getURI();
    $plugin = & JPluginHelper::getPlugin('content', 'denunciar');
    $pluginParams   = new JParameter( $plugin->params );

    if($pluginParams->get('notice_tags')) {
        $database = &JFactory::getDbo();

        $query = "SELECT count(*)
                FROM #__custom_properties
                WHERE value_id IN (".(is_array($pluginParams->get('notice_tags')) ? implode(",", $pluginParams->get('notice_tags')) : $pluginParams->get('notice_tags')).")
                AND ref_table='content' AND content_id = $row->id";
        $database->setQuery($query);
        if($database->loadResult() > 0) {

            $user = &JFactory::getUser();
            $content_id 	= $row->id;
            $user_id = $user->id;
            $usercount = '';
            $count = '';
            $showthumb = $pluginParams->get('showthumb');
            $linkcolor = $pluginParams->get('linkcolor');
            $textcolor = $pluginParams->get('textcolor');
            $textsize = $pluginParams->get('textsize');
            $textfloat = $pluginParams->get('textfloat');
            $textclear = $pluginParams->get('textclear');
            $showfrontpage = $pluginParams->get('showfrontpage');
            $denunciarimage = "<a href='#' style='vertical-align: text-bottom; border: 0; margin-left:5px;' onclick=\"$('denunciarDiv$content_id').getStyle('display') == 'none' ? $('denunciarDiv$content_id').setStyle('display', 'block') : $('denunciarDiv$content_id').setStyle('display', 'none')\">".JText::_('Denunciar')."</a>";

            $query          = "SELECT count(*) FROM #__denunciar_tb WHERE content_id = $content_id AND state = 0";
            $database->setQuery( $query );
            $denunciacount = $database->loadResult();

            $query          = "SELECT count(*) FROM #__denunciar_tb WHERE content_id = $content_id AND state = 0 AND user_id = $user_id";
            $database->setQuery( $query );
            $usercount = $database->loadResult();

            if(($user_id) && ($usercount == 0 )) {

                $maxdenouncechars = $pluginParams->get('maxdenouncechars');

                $html = '';

                $html .= '  <script LANGUAGE="JavaScript">
                            <!-- Dynamic Version by: Nannette Thacker -->
                            <!-- http://www.shiningstar.net -->
                            <!-- Original by :  Ronnie T. Moore -->
                            <!-- Web Site:  The JavaScript Source -->
                            <!-- Use one function for multiple text areas on a page -->
                            <!-- Limit the number of characters per textarea -->
                            <!-- Begin
                            function textCounter(field,cntfield,maxlimit) {
                                if (field.value.length > maxlimit) // if too long...trim it!
                                    field.value = field.value.substring(0, maxlimit);
                                    // otherwise, update characters left counter
                                else
                                    cntfield.value = maxlimit - field.value.length;
                            }
                            //  End -->
                            </script>';


                $html .= '<style type="text/css">
			<!--
			.text_button {
			   border: none;
			   background-color: transparent;
			   cursor: pointer;

			   padding: 0;
			   text-decoration: underline; /* if desired */
			   color: ' . $linkcolor . ';  /* or whatever other color you want */
			}-->
			
			
			</style>';


                $html .= '<div id="denunciar" style="margin-bottom: 10px;">';
                $html .= '<form id="denunciarForm'.$content_id.'" method="post" action="'.denunciarPageURL().'">';
                $html .= '<span class="denunciar">';
                $html .= $denunciarimage;

                $html .= '<div id="denunciarDiv'.$content_id.'"             style=" display: none;
                                                                                    background:none repeat scroll 0 0 #F8F8F1;
                                                                                    border:1px solid grey;
                                                                                    padding:5px;
                                                                                    width:350px;
                                                                                    position:absolute">';
                $html .= '<div style="padding: 2px; margin-bottom: 2px;">';
                $html .= '  Indique por favor los motivos de su denuncia. Estos le serán remitidos al editor, quien determinará la pertinencia de su observación. Muchas gracias.';
                $html .= '</div>';
                $html .= '  <textarea id="denunciarTextArea'.$content_id.'" cols="40" rows="5" name="denuncia"
                                onKeyDown="textCounter($(\'denunciarTextArea'.$content_id.'\'),$(\'denunciarCountField'.$content_id.'\'),'.$maxdenouncechars.')"
                                onKeyUp="textCounter($(\'denunciarTextArea'.$content_id.'\'),$(\'denunciarCountField'.$content_id.'\'),'.$maxdenouncechars.')">';
                $html .= '</textarea>';
                $html .= '  <input type="button" onclick="if($(\'denunciarTextArea'.$content_id.'\').getValue() != \'\')$(\'denunciarForm'.$content_id.'\').submit()" value="'.JText::_('Enviar denuncia').'" style="border:1px solid #CCCCCC; color:#666666; font-family:trebuchet MS;font-size:11px;margin:0;padding:0;"/>';
                $html .= '  <input id="denunciarCountField'.$content_id.'" readonly="true"/ value="'.$maxdenouncechars.'" style="   background:none repeat scroll 0 0 transparent;
                                                                                                                                    border:medium none;
                                                                                                                                    float:right;
                                                                                                                                    text-align:right;
                                                                                                                                    width:30px;">';
                $html .= '</div>';
                $html .= '<input type="hidden" name="task" value="denunciar" />';
                $html .= '<input type="hidden" name="option" value="com_content" />';
                $html .= '<input type="hidden" name="content_id" value="'. $content_id .'" />';
                $html .= '<input type="hidden" name="url" value="'. JURI::current() .'" />';
                $html .= '</span>';
                $html .= '</form>';
                $html .= '</div>';

                return $html;
            }


        } // end if on front page
    }
}

function plgContentPublish( &$row, &$params, $page=0 ) {

    /*$url = JUri::base(true);
    $uri = & JFactory::getURI();
    $plugin = & JPluginHelper::getPlugin('content', 'denunciar');
    $pluginParams   = new JParameter( $plugin->params );*/
    $database = &JFactory::getDbo();

    if($row->state == 1) {
        $query = "  UPDATE #__denunciar_tb
                        SET state = 1
                        WHERE content_id = $row->id
                        AND state = 0";
        $database->setQuery($query);
        $database->query();
        //Lanzo un full import de Solr si se descartó una denuncia.
        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'helper'.DS.'helper.php');
        $helper = new comEqZonalesHelper();
        $helper->launchSolrImport(true);
    }
}