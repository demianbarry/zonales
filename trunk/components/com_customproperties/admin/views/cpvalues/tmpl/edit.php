<?php
/**
* Custom Properties for Joomla! 1.5.x
* @package Custom Properties
* @subpackage Component
* @version 1.98
* @revision $Revision: 1.5 $
* @author Andrea Forghieri
* @copyright (C) Andrea Forghieri, www.solidsystem.it
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

defined('_JEXEC') or die('Restricted access');
$isEdit = JRequest::getCmd('task') == 'edit' ? true : false;
$value = $this->value;
//$field_values = $this->values;

// configuration file
$cp_config_path = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_customproperties' . DS;
$cp_config_file = 'cp_config.php';
require_once ($cp_config_path . $cp_config_file);
if (empty ($cp_config)) {
	JError::raiseError(500, JText::_("CP_NON_CONFIG"));
}

?>

<form action="index.php" method="post" name="adminForm">
<?php

$text = $isEdit ? 'Edit' : 'New';
$text_name = $isEdit ? "<small><small>[ $value->name ]</small></small>" : "";

JToolBarHelper::title(JText::_('Custom Property Value') . ": <small>$text</small> $text_name ", 'field-add.png');
JToolBarHelper::apply();
JToolBarHelper::save();
JToolBarHelper::cancel();
?>
  <table width="100%" class="adminform">
    <tr>
      <td width="50%" valign="top">
        <table width="100%">
        <tr>
          <th colspan="2"><?php echo JText::_( 'Value Details' ) ?></th>
        </tr>
        <tr>
          <td><?php echo JText::_( 'Name' )?>:</td>
          <td><input class="inputbox" type="text" name="name" size="30" maxlength="50" value="<?php echo  $value->name ?>"/></td>
        </tr>
        <tr>
          <td><?php echo JText::_( 'Label' )?>:</td>
          <td><input class="inputbox" type="text" name="label" id="title_alias" value="<?php echo  $value->label?>" size="30" maxlength="255" /></td>
        </tr>
        </table>
      </td>
    </tr>
  </table>

<input type="hidden" name="hidemainmenu" value="0"/>
<input type="hidden" name="option" value="com_customproperties" />
<input type="hidden" name="controller" value="values" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="cid" value="<?php echo $this->value->id; ?>" />
<input type="hidden" name="pid" value="<?php echo $this->pid; ?>" />
<input type="hidden" name="ordering" value="<?php echo $this->value->ordering; ?>" />

</form>
