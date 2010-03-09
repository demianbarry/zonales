<?php
/**
 * ACL handler plugin for Joomla! ACL system (both 1.5 and 1.6)
 *
 * @version		$Id: joomla.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
 * @package		Joomla
 * @subpackage	JWF.plugins
 * @author	Mostafa Muhammad <mostafa.mohmmed@gmail.com>
 * @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
 * @license		GNU/GPL
 */


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class JWFACLHandler_joomla extends JWFACLHandler {


    function getMyGroupId() {

        $user = & JFactory::getUser();

        list($major,$minor,$version) = explode('.',JVERSION);

        //1.6
        if( $major == 1 && $minor == 6 ) {
            return $user->groups;
        }

        //1.5
        if( $major == 1 && $minor == 5 ) {
            return array($user->gid => $user->usertype);
        }

        return 0;
    }

    function getUsers( $gid ) {

        $gid = intval($gid);
        list($major,$minor,$version) = explode('.',JVERSION);

        //1.6
        if( $major == 1 && $minor == 6 ) {
            $db  =& JFactory::getDBO();
            $sql =
                    "SELECT g.user_id as id,u.name,u.username,u.email,u.password,n.title as usertype, u.block,u.sendEmail ,g.group_id as gid,u.registerDate,u.lastvisitDate,u.activation,u.params"
                    ."\nFROM `#__user_usergroup_map` AS g"
                    ."\nINNER JOIN `#__users` AS u ON g.user_id = u.id"
                    ."\nINNER JOIN `#__usergroups` AS n ON n.id = g.group_id"
                    ."\nWHERE g.group_id=$gid";
            $db->setQuery( $sql );
            return $db->loadObjectList();
        }

        //1.5
        if( $major == 1 && $minor == 5 ) {
            $db  =& JFactory::getDBO();
            $sql = "SELECT * FROM #__users WHERE gid=$gid";
            $db->setQuery( $sql );
            return $db->loadObjectList();
        }

        return null;
    }

    function getACL() {
        static $aclList;

        list($major,$minor,$version) = explode('.',JVERSION);
        //Cache acl settings
        if( $aclList != null )return $aclList;

        //1.5
        if( $major == 1 && $minor == 5 ) {
            $db  =& JFactory::getDBO();
            $sql = "SELECT id,name FROM `#__core_acl_aro_groups` LIMIT 2,999999999";
            $db->setQuery( $sql );
            $temp = $db->loadObjectList();
            $aclList = array();
            foreach( $temp as $g )$aclList[$g->id] = $g->name;
            /*$aclList =
				array(
					18 => 'Registered',
					19 => 'Author' ,
					20 => 'Editor',
					21 => 'Publisher',
					23 => 'Manager',
					24 => 'Administrator',
					25 => 'Super Administrator'
				);*/
        }

        //1.6
        if( $major == 1 && $minor == 6 ) {
            $db  =& JFactory::getDBO();
            $sql = "SELECT id,title FROM `#__usergroups`";
            $db->setQuery( $sql );
            $temp = $db->loadObjectList();
            $aclList = array();
            foreach( $temp as $g )$aclList[$g->id] = $g->title;
        }
        return $aclList;
    }
}
