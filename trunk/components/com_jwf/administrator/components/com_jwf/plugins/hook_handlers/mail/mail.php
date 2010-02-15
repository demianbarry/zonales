<?php
/**
* Mail Hook handler plugin : Sends E-mail notifications to user/admins when and item moves through the workflow
*
* @version		$Id: mail.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.plugins
* @author	Mostafa Muhammad <mostafa.mohmmed@gmail.com>
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL	
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class JWFHookHandler_mail  extends JWFHookHandler{
	
	var $translationTable = null;
	
	function __construct(){
		
		$u =& JFactory::getURI();
		$this->translationTable = array(
			'{item:link}'     => '"'.JURI::root().'index.php?option=com_jwf&controller=item&task=edit&wid={$workflow->id}&iid={$currentStep->iid}";',
			'{item:title}'    => '"{$currentStep->title}";',
			'{item:arrival}'  => '"{$currentStep->created}";',
			'{station:task}'  => '"{$currentStation->task}";',
			'{station:title}' => '"{$currentStation->title}";'
		);
		
	}
	
	function _translate($message, $hookParameters, $workflow, $currentStation, $currentStep){
	
		$message = str_replace( '\n', '<br />', $message);
		foreach( $this->translationTable as $key => $value ){
			$translatedValue = eval('return ' . $value);
			$message = str_replace( $key, $translatedValue, $message);
		}
		return $message;
	}
	
	function onArrival( $hookParameters, $workflow, $currentStation, $currentStep){
		
		$pManager =& getPluginManager();
		$pManager->loadPlugins('acl');
		
		$tempResponse  = null;
		
		$tempResponse  = $pManager->invokeMethod( 'acl', 'getUsers',  array($workflow->acl), array($currentStation->group) );
		$userList      = $tempResponse[$workflow->acl];

		$tempResponse  = $pManager->invokeMethod( 'acl', 'getUsers',  array($workflow->acl), array($workflow->admin_gid));
		$adminList     = $tempResponse[$workflow->acl];
		
		$mail = JFactory::getMailer();
		if(intval($hookParameters->SendAdmin) && count($adminList)){
		
			$mail->IsHTML( true );
			foreach($adminList as $admin ){
				$mail->AddRecipient( $admin->email );
			}
			$mail->SetSubject(JText::_('Item moved'));
			$translatedMessage =  $this->_translate($hookParameters->AdminText, $hookParameters, $workflow, $currentStation, $currentStep);
			$mail->SetBody($translatedMessage);
			$mail->Send();
		}
		
		if(intval($hookParameters->SendUser) && count($userList) ){
			$mail->IsHTML( true );
			foreach($userList as $user ){
				$mail->AddRecipient( $user->email );
			}
			$mail->SetSubject(JText::_('New task awaits'));
			$translatedMessage =  $this->_translate($hookParameters->UserText, $hookParameters, $workflow, $currentStation);
			$mail->SetBody( $translatedMessage );
			$mail->Send();
		}
	}
}