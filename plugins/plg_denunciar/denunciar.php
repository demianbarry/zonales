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
	
	
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
				
	$database = &JFactory::getDbo();
		
		
		$sql		= "CREATE TABLE IF NOT EXISTS `#__denunciar_tb` (`id` int(11) NOT NULL auto_increment, comment longtext, `content_id` int(11) default NULL, `user_id` int(11) default NULL, PRIMARY KEY  (`id`)) ENGINE = MYISAM AUTO_INCREMENT =1 DEFAULT
		CHARSET = utf8 AUTO_INCREMENT =1;";
		$database->setQuery($sql);
		$database->query();
			
	
	$task = JRequest::getVar('task', '', 'post');

	switch ($task) {
		
	case "denunciar":
	
			$contentid = JRequest::getVar('content_id', '', 'post');
			$userid = JRequest::getVar('user_id', '', 'post');


			$sql = "INSERT INTO #__denunciar_tb (user_id, content_id,comment) 
				    VALUES('$userid', '$contentid','')" ;
			$database->setQuery($sql);
			$database->query();
			
			
			$from = "denuncia@zonales.com.ar";
			
			$fromname = 'Denuncia!!';
			
			$recipient[] = 'juanmanuelcortez@gmail.com';
			
			$subject = 'Denunciaron Articulo ';
			
			$pageURL = 'http';
			// if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			$pageURL .= "://";
			if ($_SERVER["SERVER_PORT"] != "80") {
			  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			} else {
			  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			}
			
			$body = '
			<p>Hola Administrador!</p>
			<p>El siguiente articulo ha sido denunciado  <br />
			Para ver el articulo haga <a href="'.$pageURL.'index.php?option=com_content&view=article&id='.$contentid.'">Click  aca</a></p>
			<p>Para Editar haga <a href="'.$pageURL.'administrator/index.php?option=com_content&sectionid=-1&task=edit&cid[]='.$contentid.'">Click aca</a> </p>
			';
			
			$mode = 1;
			
			$replyto = 'no_reply@somewhere.com';
			
			$replytoname = 'NO REPLY - Zonales';
			 
			JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode,'','', '', $replyto, $replytoname);
	break;	
	
	}
	
	$plugin = & JPluginHelper::getPlugin('content', 'denunciar');
	$pluginParams   = new JParameter( $plugin->params );
	$denunciardisplay = $pluginParams->get('denunciardisplay');

	
$mainframe->registerEvent( $denunciardisplay, 'plgContentdenunciar' );


function denunciarPageURL() 
{
			$pageURL = 'http';
			// if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			$pageURL .= "://";
			if ($_SERVER["SERVER_PORT"] != "80") {
			  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			} else {
			  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			}
			return $pageURL;
}

	
	
function plgContentdenunciar( &$row, &$params, $page=0 )
{
			$url = JUri::base(true);
			$uri = & JFactory::getURI();
			$plugin = & JPluginHelper::getPlugin('content', 'denunciar');
			$pluginParams   = new JParameter( $plugin->params );		
			$database = &JFactory::getDbo();
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
			$denunciarimage = '<img src="plugins/content/like/notlike.png" style="vertical-align: text-bottom; border: 0; margin-left:5px;">';
			
			$query		= "SELECT count(*) FROM #__denunciar_tb WHERE content_id = $content_id";
			$database->setQuery( $query );
			$count		= $database->loadResult();
			
			
			$query		= "SELECT count(*) FROM #__denunciar_tb WHERE content_id = $content_id AND user_id = $user_id";
			$database->setQuery( $query );
			$usercount = $database->loadResult();
			
			$query		= "SELECT count(*) FROM #__content_frontpage WHERE content_id = $content_id";
			$database->setQuery( $query );
			$onfrontpage = $database->loadResult();
			

	if (($showfrontpage == 'no') and ($onfrontpage > 0)) {
			

			
			}
			
	if (($showfrontpage =="yes") or (!$onfrontpage)) {
	
			$css = '<!-- CSS STYLES -->

				<style type="text/css">
				<!--

				div#denunciar{
	
				color: ' . $textcolor . ';
				font-size: ' . $textsize . 'px;
				float: ' . $textfloat . ';
				clear: ' . $textclear . ';

				}
			
			--></style>';



		
			if (($user->id) && ($count > 1)){
		
			$html = '';
			$html .= $css . '<div id="denunciar">  &nbsp;';
			$html .= $denunciarimage . ' <i>' . $count . ' Usuarios denunciaron esta articulo.</i>';
			$html .= '</div>';
		
			return $html;
		
		}
		
		
		
	
		
		
		
		if(($user_id) && ($usercount == 0 )){
		
			
			$html = '';
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
			
			
			$html .= $css . '<div id="denunciar">';
			$html .= '<form method="post" action="' . denunciarPageURL()  . '">';
			$html .= '<span class="denunciar">';
			$html .= ' ' . $denunciarimage . ' <input type="submit" value="denunciar" class="text_button">';	
			$html .= '<input type="hidden" name="task" value="denunciar" />';
			$html .= '<input type="hidden" name="option" value="com_content" />';
			$html .= '<input type="hidden" name="user_id" value="'. $user_id . '" />';
			$html .= '<input type="hidden" name="content_id" value="' . $content_id . '" />';
			$html .= '<input type="hidden" name="url" value="'. JURI::current() . '" />';
			$html .= '</span>';
			$html .= '</form>';
			$html .= '</div>';

			return $html;
			
		}	
		
	} // end if on front page
	
}


