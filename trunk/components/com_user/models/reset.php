<?php
/**
 * @version		$Id: reset.php 10919 2008-09-09 20:50:29Z willebil $
 * @package		Joomla
 * @subpackage	User
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
require_once JPATH_COMPONENT . DS . 'helper.php';
require_once JPATH_ROOT . DS . 'modules' . DS . 'mod_message' . DS . 'constants.php';

/**
 * User Component Reset Model
 *
 * @package		Joomla
 * @subpackage	User
 * @since		1.5
 */
class UserModelReset extends JModel
{
	/**
	 * Registry namespace prefix
	 *
	 * @var	string
	 */
	var $_namespace	= 'com_user.reset.';

	/**
	 * Verifies the validity of a username/e-mail address
	 * combination and creates a token to verify the request
	 * was initiated by the account owner.  The token is
	 * sent to the account owner by e-mail
	 *
	 * @since	1.5
	 * @param	string	Username string
	 * @param	string	E-mail address
	 * @return	bool	True on success/false on failure
	 */
	function requestReset($email)
	{
		jimport('joomla.mail.helper');
		jimport('joomla.user.helper');

		$db = &JFactory::getDBO();

		// Make sure the e-mail address is valid
		if (!JMailHelper::isEmailAddress($email))
		{
                        $message = JText::_('INVALID_EMAIL_ADDRESS');
			$this->setError($message);
                        UserHelper::showMessage(ERROR, $message);
			return false;
		}

		// Build a query to find the user
		$query	= 'SELECT id FROM #__users'
				. ' WHERE email = '.$db->Quote($email)
				. ' AND block = 0';

		$db->setQuery($query);

		// Check the results
		if (!($id = $db->loadResult()))
		{
                        $message = JText::_('COULD_NOT_FIND_USER');
			$this->setError($message);
                        UserHelper::showMessage(ERROR, $message);
			return false;
		}

		// Generate a new token
		$token = JUtility::getHash(JUserHelper::genRandomPassword());

		$query	= 'UPDATE #__users'
				. ' SET activation = '.$db->Quote($token)
				. ' WHERE id = '.(int) $id
				. ' AND block = 0';

		$db->setQuery($query);

		// Save the token
		if (!$db->query())
		{
			$message = JText::_('DATABASE_ERROR');
			$this->setError($message);
                        UserHelper::showMessage(ERROR, $message);
			return false;
		}

		// Send the token to the user via e-mail
		if (!$this->_sendConfirmationMail($email, $token))
		{
			return false;
		}

		return true;
	}

	/**
	 * Checks a user supplied token for validity
	 * If the token is valid, it pushes the token
	 * and user id into the session for security checks.
	 *
	 * @since	1.5
	 * @param	token	An md5 hashed randomly generated string
	 * @return	bool	True on success/false on failure
	 */
	function confirmReset($token)
	{
		global $mainframe;

		if(strlen($token) != 32) {
			$this->setError(JText::_('INVALID_TOKEN'));
			return false;
		}

		$db	= &JFactory::getDBO();
		$db->setQuery('SELECT id FROM #__users WHERE block = 0 AND activation = '.$db->Quote($token));

		// Verify the token
		if (!($id = $db->loadResult()))
		{
                        $message = JText::_('INVALID_TOKEN');
			$this->setError($message);
                        UserHelper::showMessage(ERROR, $message);
			return false;
		}

		// Push the token and user id into the session
		$mainframe->setUserState($this->_namespace.'token',	$token);
		$mainframe->setUserState($this->_namespace.'id',	$id);

		return true;
	}

	/**
	 * Takes the new password and saves it to the database.
	 * It will only save the password if the user has the
	 * correct user id and token stored in her session.
	 *
	 * @since	1.5
	 * @param	string	New Password
	 * @param	string	New Password
	 * @return	bool	True on success/false on failure
	 */
	function completeReset($password1, $password2)
	{
		jimport('joomla.user.helper');

		global $mainframe;

		// Make sure that we have a pasword
		if ( ! $password1 )
		{
                        $message = JText::_('MUST_SUPPLY_PASSWORD');
			$this->setError($message);
                        UserHelper::showMessage(ERROR, $message);
			return false;
		}

		// Verify that the passwords match
		if ($password1 != $password2)
		{
                        $message = JText::_('PASSWORDS_DO_NOT_MATCH_LOW');
			$this->setError($message);
                        UserHelper::showMessage(ERROR, $message);
			return false;
		}

		// Get the necessary variables
		$db			= &JFactory::getDBO();
		$id			= $mainframe->getUserState($this->_namespace.'id');
		$token		= $mainframe->getUserState($this->_namespace.'token');
		$salt		= JUserHelper::genRandomPassword(32);
		$crypt		= JUserHelper::getCryptedPassword($password1, $salt);
		$password	= $crypt.':'.$salt;

		// Get the user object
		$user = new JUser($id);

		// Fire the onBeforeStoreUser trigger
		JPluginHelper::importPlugin('user');
		$dispatcher =& JDispatcher::getInstance();
		$dispatcher->trigger('onBeforeStoreUser', array($user->getProperties(), false));

		// Build the query
		$query 	= 'UPDATE #__users'
				. ' SET password = '.$db->Quote($password)
				. ' , activation = ""'
				. ' WHERE id = '.(int) $id
				. ' AND activation = '.$db->Quote($token)
				. ' AND block = 0';

		$db->setQuery($query);

		// Save the password
		if (!$result = $db->query())
		{
                        $message = JText::_('DATABASE_ERROR');
			$this->setError($message);
                        UserHelper::showMessage(ERROR, $message);
			return false;
		}

		// Update the user object with the new values.
		$user->password			= $password;
		$user->activation		= '';
		$user->password_clear	= $password1;

		// Fire the onAfterStoreUser trigger
		$dispatcher->trigger('onAfterStoreUser', array($user->getProperties(), false, $result, $this->getError()));

		// Flush the variables from the session
		$mainframe->setUserState($this->_namespace.'id',	null);
		$mainframe->setUserState($this->_namespace.'token',	null);

		return true;
	}

	/**
	 * Sends a password reset request confirmation to the
	 * specified e-mail address with the specified token.
	 *
	 * @since	1.5
	 * @param	string	An e-mail address
	 * @param	string	An md5 hashed randomly generated string
	 * @return	bool	True on success/false on failure
	 */
	function _sendConfirmationMail($email, $token)
	{
		$config		= &JFactory::getConfig();
		$uri		= &JFactory::getURI();
		$url		= JURI::base().'index.php?option=com_user&view=reset&layout=confirm&map=0';
		$sitename	= $config->getValue('sitename');

		// Set the e-mail parameters
		$from		= $config->getValue('mailfrom');
		$fromname	= $config->getValue('fromname');
		$subject	= JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TITLE', $sitename);
		$body		= JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TEXT', $sitename, $token, $url);

		// Send the e-mail
		if (!JUtility::sendMail($from, $fromname, $email, $subject, $body))
		{
                    $message = JText::_('ERROR_SENDING_CONFIRMATION_EMAIL');
                    $this->setError($message);
                        UserHelper::showMessage(ERROR, $message);
			return false;
		}

		return true;
	}
}