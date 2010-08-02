<?php
/**
 * Custom Properties for Joomla! 1.5.x
 * @package Custom Properties
 * @subpackage Component
 * @version 1.98
 * @revision $Revision: 1.3 $
 * @author Andrea Forghieri
 * @copyright (C) Andrea Forghieri, www.solidsystem.it
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
 */


defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');

$page = $this->page;
$count = count($this->items);

if (isset ($this->back)) {
    $backLink = JRoute::_('index.php?option=' . $option . '&controller=values&cid=' . $this->back);
} else {
    $backLink = JRoute::_('index.php?option=' . $option . '&controller=values&cid=0');
}

$pageTitle = "&nbsp;<a href=". JRoute::_("index.php?option=com_customproperties&controller=values"). ">".JText::_( 'Hierarchic Values Manager' )."</a>";
$text_name = '';

for ($i=0, $n=count( $this->itemsBC ); $i < $n; $i++) {
    $text_name = $text_name."&nbsp;<a href=". JRoute::_($this->itemsBC[$i]->link). "><small><small>".$this->itemsBC[$i]->name."</small></small></a>";
}
JToolBarHelper::title($pageTitle.$text_name, 'field-add.png' );
//JToolBarHelper::publishList();
//JToolBarHelper::unpublishList();
//JToolBarHelper::deleteList(JText::_( 'WARNINGDELPROPERTIES'));
//JToolBarHelper::editListX();
JToolBarHelper::addNewX();
if ($this->root != 0) {
    JToolBarHelper::back(JText::_('BACK'), JRoute::_('index.php?option=' . $option . '&controller=values&cid=' . $this->back));
}

?>

<form action="index.php" method="post" name="adminForm">

    <div id="editcell">

        <?php if ($this->root == 0):?>
        <table>
            <thead>
                <tr>
                    <td colspan="2" nowrap="nowrap">
                            <?php
                            echo JText::_( 'FILTERS' ).": ";
                            echo JText::_('FIELD_TYPE').": ";
                            echo $this->_lists['field_list'];
                            ?>
                    </td>
                </tr>
            </thead>
        </table>
        <?php endif;?>

        <table class="adminlist">
            <thead>
                <tr>
                    <th width="5">#</th>
                    <th width="5"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo $count?>);" /></th>
                    <th class="title"><?php echo JText::_( 'Name' ); ?></th>
                    <th align="left"><?php echo JText::_( 'Label' ); ?></th>
                    <th align="left"><?php echo JText::_( 'Field' ); ?></th>
                    <th>ID</th>
                    <th width="5%"align="center" colspan="2"><?php echo JText::_( 'Del/Edit' ); ?></th>
                    <th colspan="2" align="center" width="5%"><?php echo JText::_( 'Reorder' ); ?></th>
                    <th width="1%"><a href="javascript: saveorder( <?php echo ($count - 1)?> )"><img src="images/filesave.png" border="0" width="16" height="16" alt="<?php echo JText::_('Save Order')?>" /></a></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="11">
                        <?php echo $page->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>
            <?php
            $k = 0;
            for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
                $row =& $this->items[$i];
                $link 	= 'index.php?option=com_customproperties&controller=values&cid[]='. $row->id;
                $linkEditar = 'index.php?option=com_customproperties&controller=values&task=edit&cid[]='. $row->id.'&pid='. $this->cid[0];
                $linkBorrar = 'index.php?option=com_customproperties&controller=values&task=remove&cid[]='. $row->id.'&pid='. $this->cid[0];
                $linkBorrar = 'index.php?option=com_customproperties&controller=values&task=remove&cid[]='. $row->id.'&pid='. $this->cid[0];

                $checked 	= JHTML::_('grid.id',	$i, $row->id );

                ?>
            <tr class="<?php echo "row$k"; ?>">
                <td>
                        <?php echo $page->getRowOffset( $i ) ?>
                </td>
                <td>
                        <?php echo $checked ?>
                </td>
                <td>
                    <a href="<?php echo JRoute::_( $link ); ?>">
                            <?php echo htmlspecialchars($row->name, ENT_QUOTES); ?>
                    </a>
                    <!--
                    &nbsp;(
                    <a href="<?php echo JRoute::_( $linkEditar ); ?>">
							Editar
                    </a>
                    ) &nbsp;(
                    <a href="<?php echo JRoute::_( $linkBorrar ); ?>">
							Borrar
                    </a>
                    )
                    -->
                </td>
                <td>
                        <?php echo $row->label ?>
                </td>
                <td>
                        <?php echo $row->field ?>
                </td>
                <td align="center">
                        <?php echo $row->id ?>
                </td>
                <td align="center">
                    <a href="<?php echo JRoute::_($linkEditar); ?>">
                        <img src="images/addedit.png" width="16" height="16" border="0" alt="Editar" />
                    </a>
                </td>
                <td align="center">
                    <a href="<?php echo JRoute::_($linkBorrar); ?>">
                        <img src="images/publish_x.png" width="16" height="16" border="0" alt="Eliminar" />
                    </a>
                </td>
                <td class="order">
                    <span><?php echo $page->orderUpIcon( $i, ($i !== 0), 'orderup', JText::_('Move Up')); ?></span>
                </td>
                <td class="order">
                    <span><?php echo $page->orderDownIcon( $i, $n, ($i != $n), 'orderdown', JText::_('Move Down') ); ?></span>
                </td>
                <td class="order">
                    <input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
                </td>
            </tr>
                <?php
                $k = 1 - $k;
            }
            ?>
        </table>
    </div>
    <input type="hidden" name="hidemainmenu" value="0"/>
    <input type="hidden" name="option" value="com_customproperties" />
    <input type="hidden" name="controller" value="values" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="cid" value="<?php echo $this->cid[0];?>" />
    <input type="hidden" name="pid" value="<?php echo $this->cid[0];?>" />
    <input type="hidden" name="ag" value="<?php echo $this->cid[0];?>" />

</form>
