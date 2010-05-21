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

$priority = array();
$priority[] = JHTML::_('select.option', '0', '0');
$priority[] = JHTML::_('select.option', '1', '1');
$priority[] = JHTML::_('select.option', '2', '2');

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

                    <?php if($this->pid != 0):?>
                    <tr>
                        <td><?php echo JText::_( 'Parent' )?>:</td>
                        <td><?php echo JHTML::_('select.genericlist', $this->items, 'newpid', '','id', 'label', $value->parent_id) ?></td>
                    </tr>
                    <?php endif;?>

                    <?php if($this->pid == 0):?>
                    <tr>
                        <td><?php echo JText::_( 'Field' )?>:</td>
                        <td><?php echo JHTML::_('select.genericlist', $this->fields, 'field', '','id', 'label', $value->field_id) ?></td>
                    </tr>
                    <?php endif;?>
                </table>
            </td>

            <td width="50%" valign="top">
                <table width="100%">
                    <tr>
                        <th><?php echo JText::_( 'Childs' )?></th>
                    </tr>
                    <tr>
                        <td>
                            <?php

                            if ($value->id != 0) {

                                switch($cp_config['script_position']) {
                                    case 'head':
                                        linkAddRowJavascript();
                                        break;
                                    case 'embed':
                                        embedAddRowJavascript();
                                        break;
                                    case 'auto':
                                    default:
                                        if(phpversion() < '5') 	embedAddRowJavascript();
                                        else linkAddRowJavascript();
                                }

                                $count = count($this->childs);
                                // Create the pagination object
                                jimport('joomla.html.pagination');
                                $pageNav = new JPagination($count, '', '');
                                ?>
                            <table class="adminlist">
                                <tbody id="valueslist">
                                    <tr>
                                        <td colspan="11">
                                            <input type="button" name="addRow" value="<?php echo JText::_( 'Add value' )?>" onclick="return insertRow();"/>
                                            <input type="button" name="deleteRow" value="<?php echo JText::_( 'Delete value(s)' )?>" onclick="if (document.adminForm.boxchecked.value == 0){ alert('Please make a selection from the list to delete'); } else if(confirm('Delete selected value and all its occurencies?')){return submitbutton('deleteValue')};"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="5">#</th>
                                        <th width="5"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo $count?>);" /></th>
                                        <th class="title"><?php echo JText::_('Name/Value')?></th>
                                        <th align="left"><?php echo JText::_('Label')?></th>
                                        <th align="left" title="Priority"><?php echo JText::_('Pri') ?></th>
                                        <th width="5%"><?php echo JText::_( 'Default')?></th>
                                        <th width="5%">ID</th>
                                        <th colspan="2" align="center" width="5%"><?php echo JText::_('Reorder')?></th>
                                        <th width="1%"><?php echo JText::_('Order')?></th>
                                        <th width="10">&nbsp;</th>
                                    </tr>
                                        <?php

                                        $index = 0;
                                        if ($count > 0) {
                                            $k = 0;
                                            foreach ($this->childs as $row) {
                                                $is_default = $row->default == 1 ? "checked=\"checked\"" : "";
                                                ?>
                                    <tr class="row<?php echo $k?>">
                                        <td>
                                                        <?php echo $pageNav->getRowOffset( $index ) ?>
                                        </td>
                                        <td align="center">
                                                        <?php echo JHTML::_('grid.id', $index, $row->id, null,'val_id' ); ?>
                                        </td>
                                        <td>
                                            <input type="text" name="value_name[]" value="<?php echo $row->name;?>"/>
                                        </td>
                                        <td>
                                            <input type="text" name="value_label[]" value="<?php echo $row->label;?>"/>
                                        </td>
                                        <td>
                                                        <?php //TODO controlal volore del campo priority ?>
                                                        <?php echo JHTML::_('select.genericlist', $priority, 'value_priority[]', 'class="inputbox" size="1"', 'value', 'text', $row->priority ? $row->priority : 0 ); ?>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="value_default[<?php echo $index; ?>]" value="1" <?php echo $is_default?>/>
                                        </td>
                                        <td align="center">
                                            <input type="text" name="value_id[]" value="<?php echo $row->id?>" size="2" readonly="readonly"/>
                                        </td>
                                        <td align="right">
                                                        <?php echo $pageNav->orderUpIcon($index, ($index !== 0), "ordvalup", JText::_('Move Up') ); ?>
                                        </td>
                                        <td align="left">
                                                        <?php echo $pageNav->orderDownIcon($index, $count, ($index != $count), "ordvaldn", JText::_('Move Down') ); ?>
                                        </td>
                                        <td align="center">
                                            <input type="text" name="value_order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
                                        </td>
                                        <td align="center">
                                            <a href="javascript: void(0);" onclick="if(confirm('<?php echo JText::_('WARNINGDELVALUE')?>')){ return listItemTask('cb<?php echo $index ?>','deleteValue')}">
                                                <img src="images/publish_x.png" width="16" height="16" border="0" alt="Delete Value" />
                                            </a>
                                        </td>
                                    </tr>
                                                <?php

                                                $k = 1 - $k;
                                                $index++;
                                            }
                                        }
                                        ?>
                                </tbody>
                            </table>
                                <?php

                            } else {
                                echo JText::_('Save field to add values');
                            }
                            ?>
                        </td>
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
