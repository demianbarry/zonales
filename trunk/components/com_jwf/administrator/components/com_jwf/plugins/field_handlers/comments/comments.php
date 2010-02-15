<?php
/**
* Comments Field handler plugin : Displays and manages comments attached to items in workflows
* See "media" directory for the javascript client of this field
*
* @version		$Id: comments.php 1480 2009-08-24 15:11:16Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.plugins
* @author	Mostafa Muhammad <mostafa.mohmmed@gmail.com>
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/


class JWFFieldHandler_comments  extends JWFFieldHandler{
	
	function renderHistoryEntry( $workflow, $entry ){
		
		$historyObject = $entry->value;
		
		if($historyObject->type == 'create')
			return JText::_('Comment created')."<br />".base64_decode($historyObject->value);
		
		if($historyObject->type == 'modifed')
			return JText::_('Comment Modified to')."<br />".base64_decode($historyObject->value);
		
		return '';
		
	}
	
	function display( $steps, $workflow, $data ){
		
		$pManager =& getPluginManager();
		$pManager->loadPlugins('acl');
		$response  = $pManager->invokeMethod( 'acl', 'getMyGroupId',  array($workflow->acl),null );
		$myGroups  = $response[$workflow->acl];
		$user      =& JFactory::getUser();
		
	
		JHTML::_('script'    , 'comments.js' , 'media/com_jwf/plugins/field_handlers/comments/');
		JHTML::_('stylesheet', 'comments.css', 'media/com_jwf/plugins/field_handlers/comments/');
		$currentStep = null; 
		foreach($steps as $step){
			if( $step->current == 1 ){
				$currentStep = $step;
				break;
			}
		}

		$output  = JHTML::_('jwf.startJSBlock',2);
		$output .= JHTML::_('jwf.indentedLine',"var workflowID = $currentStep->wid;",3);
		$output .= JHTML::_('jwf.indentedLine',"var stationID  = $currentStep->sid;",3);
		$output .= JHTML::_('jwf.indentedLine',"var itemID     = $currentStep->iid;",3);
		$output .= JHTML::_('jwf.indentedLine',"var stepID     = $currentStep->id;",3);
		$output .= JHTML::_('jwf.indentedLine',"var jtoken     = '".JUtility::getToken()."';",3);
		$output .= JHTML::_('jwf.endJSBlock'  ,2);

		$output .= JHTML::_('jwf.indentedLine',"<div id='field-comments'>",2);
		$output .= JHTML::_('jwf.indentedLine',"<div id='jwf_comment_overlay'></div><img id='jwf_comment_loading' src='administrator/components/com_jwf/plugins/field_handlers/comments/loading.gif' alt='loading' />",2);
		if( $data ){
			$k = false;
			foreach($data as $comment){
				$k = !$k;
				$class = $k?'odd':'even';
				$output .= JHTML::_('jwf.indentedLine',"<div class='comment $class'>",2);
				if(	in_array($workflow->admin_gid,array_keys($myGroups)) ||
					canManageWorkflows() ||
					$user->get('id') == $comment->created_by ){
					$output .= JHTML::_('jwf.indentedLine',"<a class='edit' href='javascript:onEdit({$comment->id})'>".JText::_('Edit')."</a>",2);
					$output .= JHTML::_('jwf.indentedLine',"<a class='edit' href='javascript:deleteComment({$comment->id})'>".JText::_('Delete')."</a>",2);
				}
				$output .= JHTML::_('jwf.indentedLine',"<div class='created'>".JText::_('By')." <span class='user'>$comment->creator </span> ".JText::_('On')." $comment->created ( <span class='ago'>".JHTML::_('jwf.timeDifference',$comment->created) . JTEXT::_('Ago')." </span> )</div>",2);
				if( $comment->created != $comment->modified ){
					$output .= JHTML::_('jwf.indentedLine',"<div class='modified'>".JText::_('Last modified By')." <span class='user'> $comment->modifier </span>".JText::_('On')." $comment->modified ( <span class='ago'>".JHTML::_('jwf.timeDifference',$comment->modified) . JTEXT::_('Ago')."</span> )</div>",2);
				}
				$output .= JHTML::_('jwf.indentedLine',"<br clear='all' />",2);
				$output .= JHTML::_('jwf.indentedLine',"<div class='text' id='jwf_comment_{$comment->id}'>".base64_decode($comment->value)."</div>",2);

				$output .= JHTML::_('jwf.indentedLine',"</div>",2);
			}

		}
		$output .= JHTML::_('jwf.indentedLine',"<div id='jwf_comment_compact_editor' style='display:none'><textarea id='jwf_comment_compact_editor_input'></textarea><br /><input type='button' onclick='saveComment()' value='".JText::_('Save')."' /><input type='button' onclick='javascript:cancelEdit()' value='".JText::_('Cancel')."' /></div>",2);
		$output .= JHTML::_('jwf.indentedLine',"<div id='jwf_comment_editor'>",2);
		$output .= JHTML::_('jwf.indentedLine',"<label for='jwf_comment_editor_input'>".JText::_('Your Comment')."</label>",2);
		$output .= JHTML::_('jwf.indentedLine',"<textarea id='jwf_comment_editor_input'></textarea>",2);
		$output .= JHTML::_('jwf.indentedLine',"<input type='button' value='".JText::_('Post')."' onclick='newComment()' />",2);
		$output .= JHTML::_('jwf.indentedLine',"</div>",2);
		$output .= JHTML::_('jwf.indentedLine',"</div>",2);
		return $output;
		
		
	}
	function delete( $workflow, $steps, $storedComments, $incomingComment ){
		
		$pManager =& getPluginManager();
		$pManager->loadPlugins('acl');
		$response = $pManager->invokeMethod( 'acl', 'getMyGroupId',  array($workflow->acl),null );
		$myGroups = $response[$workflow->acl];
		$user     =& JFactory::getUser();
		
		$isNew = intval($incomingComment['commentID'])==0?true:false;
		
		$isAuthorized = false;
		
		$currentComment = searchObjectArray( $storedComments , 'id', $incomingComment['commentID']);
		if(	canManageWorkflows()){$isAuthorized = true;}
		elseif( in_array($workflow->admin_gid,array_keys($myGroups))){$isAuthorized = true;}
		else {
			if( $currentComment != null && $user->get('id') == $currentComment->created_by ){
				$isAuthorized = true;
			}
		}

		if( !$isAuthorized ){
			return 0;
		}
		
		require_once JWF_BACKEND_PATH.DS. 'models'.DS.'field.php';
		$fieldModel = new JWFModelField();
		
		require_once JWF_BACKEND_PATH.DS. 'models'.DS.'history.php';
		$historyModel = new JWFModelHistory();
		
		$historyObject->type = 'delete';
		$historyObject->value= $currentComment->value;
		$historyModel->add( $workflow->id, 
							$workflow->stations[$currentComment->sid], 
							$currentComment->iid,
							'field.comments',
							JText::_('Comment Deleted'),
							$historyObject);
									
		if( $fieldModel->delete( array(intval($incomingComment['commentID'])) ) )return 1;
		return 0;
	}
	
	function save( $workflow, $steps, $storedComments, $incomingComment ){
		
		$pManager =& getPluginManager();
		$pManager->loadPlugins('acl');
		$response = $pManager->invokeMethod( 'acl', 'getMyGroupId',  array($workflow->acl),null );
		$myGroups = $response[$workflow->acl];
		$user     =& JFactory::getUser();
		
		$isNew = intval($incomingComment['commentID'])==-1?true:false;
		
		$isAuthorized = false;
	
		//The HUGE Authorization routine
		/*
		Global Administrator -> Allowed to do everything
	
		Old Comment
			Workflow manager -> Allowed after making sure the supplied WID matches a workflow they have authority upon 
			Normal user      -> Allowed if s/he's the creator of the comment
		
		New Comment 
			Workflow manager -> Allow if WID matches a workflow they have authority upon
			Normal user -> Allowed only if the item is in their station
		*/
		
		if(	canManageWorkflows()){$isAuthorized = true;}
		elseif( in_array($workflow->admin_gid,array_keys($myGroups))){$isAuthorized = true;}
		else {
			if( $isNew ){
					//Allow normal users to add comments to the latest step ONLY
					$currentStep = searchObjectArray( $steps , 'current', 1);
					foreach( $myGroups as $gid => $name ){
						if($workflow->stations[$incomingComment['sid']]->group == $gid){
							if( $currentStep->iid == $incomingComment['iid'] &&
								$currentStep->id  == $incomingComment['tid'] ){
								$isAuthorized = true;
							}	
						}
					}
			} else {
				$currentComment = searchObjectArray( $storedComments , 'id', $incomingComment['commentID']);
				if( $currentComment != null && $user->get('id') == $currentComment->created_by ){
					$isAuthorized = true;
				}
			}
		}			

		if( !$isAuthorized ){
			return 0;
		}
		
		$datenow =& JFactory::getDate();

		
		$incomingComment['type'] = 'comments';
		
		if( !$isNew ){
			$incomingComment['id'] = intval($incomingComment['commentID']);
			$incomingComment['modified']    = $datenow->toMySQL();
			$incomingComment['modified_by'] = $user->get('id');
		} else {
			$incomingComment['created']  = $datenow->toMySQL();
			$incomingComment['modified'] = $datenow->toMySQL();
	
			$incomingComment['created_by']  = $user->get('id');
			$incomingComment['modified_by'] = $user->get('id');
			
		}
		$incomingComment['value'] = base64_encode( $incomingComment['text'] );

		require_once JWF_BACKEND_PATH.DS. 'models'.DS.'history.php';
		$historyModel = new JWFModelHistory();
		
		require_once JWF_BACKEND_PATH.DS. 'models'.DS.'field.php';
		$fieldModel = new JWFModelField();
		
		if($fieldModel->save( $incomingComment )){
			$historyObject = new stdClass();
			
			if($isNew){
				$historyObject->type = 'create';
				$historyObject->value= $incomingComment['value'];
				$historyModel->add( $workflow->id, 
									$workflow->stations[$incomingComment['sid']], 
									$incomingComment['iid'],
									'field.comments',
									JText::_('Comment Added'),
									$historyObject);
			} else {
				$historyObject->type = 'modify';
				$historyObject->value= $incomingComment['value'];
				$historyModel->add( $workflow->id, 
									$workflow->stations[$incomingComment['sid']], 
									$incomingComment['iid'],
									'field.comments',
									JText::_('Comment Modified'),
									$historyObject);
				
			}
			return 1;
		}
		return 0;
	}
}