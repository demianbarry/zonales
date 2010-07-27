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


$sql		= "CREATE TABLE IF NOT EXISTS `#__like_tb` (`id` int(11) NOT NULL auto_increment, `content_id` int(11) default NULL, `user_id` int(11) default NULL, PRIMARY KEY  (`id`)) ENGINE = MYISAM AUTO_INCREMENT =1 DEFAULT
		CHARSET = utf8 AUTO_INCREMENT =1;";
$database->setQuery($sql);
$database->query();


$task = JRequest::getVar('task', '', 'post');

switch ($task) {

    case "like":

        $contentid = JRequest::getVar('content_id', '', 'post');
        $userid = JRequest::getVar('user_id', '', 'post');


        $sql = "INSERT INTO #__like_tb (user_id, content_id)
				    VALUES('$userid', '$contentid')" ;
        $database->setQuery($sql);
        $database->query();
        break;

    case "unlike":

        break;

}

$plugin = & JPluginHelper::getPlugin('content', 'like');
$pluginParams   = new JParameter( $plugin->params );
$likedisplay = $pluginParams->get('likedisplay');


$mainframe->registerEvent( $likedisplay, 'plgContentLike' );


function likePageURL() {
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



function plgContentLike( &$row, &$params, $page=0 ) {
    $url = JUri::base(true);
    $uri = & JFactory::getURI();
    $plugin = & JPluginHelper::getPlugin('content', 'like');
    $pluginParams   = new JParameter( $plugin->params );
    $database = &JFactory::getDbo();
    $user = &JFactory::getUser();
    $content_id 	= $row->id;

    $query = "SELECT count(*)
                FROM #__custom_properties
                WHERE value_id IN (".(is_array($pluginParams->get('notice_tags')) ? implode(",", $pluginParams->get('notice_tags')) : $pluginParams->get('notice_tags')).")
                AND ref_table='content' AND content_id = $row->id";
    $database->setQuery($query);
    if($database->loadResult() > 0) {
        
        if($user->id==0 || $user->id=='' )
            $user_id = $user->id;
        else
            $user_id = 1222;

        $usercount = '';
        $count = '';
        $showthumb = $pluginParams->get('showthumb');
        $linkcolor = $pluginParams->get('linkcolor');
        $textcolor = $pluginParams->get('textcolor');
        $textsize = $pluginParams->get('textsize');
        $textfloat = $pluginParams->get('textfloat');
        $textclear = $pluginParams->get('textclear');
        $showfrontpage = $pluginParams->get('showfrontpage');
        if ($showthumb == 'yes') {
            $likeimage = '<img src="plugins/content/like/thumbsup2.jpg" style="vertical-align: text-bottom; border: 0;">';
        }
        if ($showthumb == 'no') {
            $likeimage = '';
        }

        $query		= "SELECT count(*) FROM #__like_tb WHERE content_id = $content_id";
        $database->setQuery( $query );
        $count		= $database->loadResult();


        $query		= "SELECT count(*) FROM #__like_tb WHERE content_id = $content_id AND user_id = $user_id";
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

				div#like{
	
				color: ' . $textcolor . ';
				font-size: ' . $textsize . 'px;
				float: ' . $textfloat . ';
				clear: ' . $textclear . ';

				}
			
			--></style>';



            if ((!$user->id) && ($count == 1)) {

                $html = '';
                $html .= $css . '<div id="like">';
                $html .= $likeimage . ' <i> ' . $count . ' Les gusta el articulo.</i>';
                $html .= '</div>';

                return $html;

            }

            if ((!$user->id) && ($count > 1)) {

                $html = '';
                $html .= $css . '<div id="like">';
                $html .= $likeimage . ' <i>' . $count . ' Les gusta el articulo.</i>';
                $html .= '</div>';

                return $html;

            }


            if (($user->id) && ($usercount == 1) && ($count == 1)) {

                $html ='';
                $html .= $css . '<div id="like">';
                $html .= $likeimage . ' ' . '<i>A ti te gusta este articulo.</i>';
                $html .= '</div>';

                return $html;

            }

            if (($user->id) && ($usercount == 1) && ($count > 1)) {

                $html ='';
                $html .= $css . '<div id="like">';
                $html .= $likeimage . ' ' . '<i>You and ' . ($count - 1) . ' otras personas les gusta este articulo.</i>';
                $html .= '</div>';

                return $html;

            }



            if(($user_id) && ($usercount == 0 )) {


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


                $html .= $css . '<div id="like">';
                $html .= '<form method="post" action="' . likePageURL()  . '">';
                $html .= '<span class="like">';
                $html .= ' ' . $likeimage . ' <input type="submit" value="Me gusta" class="text_button">';
                $html .= '<input type="hidden" name="task" value="like" />';
                $html .= '<input type="hidden" name="option" value="com_content" />';
                $html .= '<input type="hidden" name="user_id" value="'. $user_id . '" />';
                $html .= '<input type="hidden" name="content_id" value="' . $content_id . '" />';
                $html .= '<input type="hidden" name="url" value="'. JURI::current() . '" />';
                $html .= '</span>';
                $html .= '</form>';
                $html .= '</div>';

                return $html;

            }
        }
    } // end if on front page

}


