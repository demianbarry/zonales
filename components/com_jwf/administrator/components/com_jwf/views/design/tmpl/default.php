<?php
/**
* Default layout for design view
*
* @version		$Id: default.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
* @package		Joomla
* @subpackage	Workflows
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');

?>


<div id='container'>
	<div id='side-bar'>
		<div id='btn-general'    class='tab-button active-tab-button' onclick='javascript:enableTab("general");'><?php echo JText::_('General') ?></div>
		<div id='btn-acl'        class='tab-button' onclick='javascript:enableTab("acl")' ><?php echo JText::_('Groups') ?></div>
		<div id='btn-properties' class='tab-button' onclick='javascript:enableTab("properties")'><?php echo JText::_('Station...')?></div>
		<br clear='all' />
		
		<div class='tab-body' style='display:block' id='tab-general'>
		<form action="index.php" method="post" name="adminForm" id='adminForm'>
			<?php echo $this->generalForm ?>
			<input type="hidden" name="params[id]" value="<?php echo $this->workflow?$this->workflow->id:'' ?>"  />
			<input type="hidden" name="params[workflowData]" value="" id="workflowData" />
			<input type="hidden" name="option" value="com_jwf" />
			<input type="hidden" name="task" value="" />
			<?php echo JHTML::_( 'form.token' ); ?>	
		</form>
		</div>
		
		<div class='tab-body' id='tab-acl'>
			<?php echo $this->groupsForm ?>
		</div>
		
		<div class='tab-body' id='tab-properties'>
			<?php echo $this->propertiesForm ?>
		</div>
		
	</div>
	<div id='workarea' class='workarea'>
		<ul id='stations'></ul>
	</div>
	
