<?php
/**
 * Custom Properties for Joomla! 1.5.x
 * @package Custom Properties
 * @subpackage Component
 * @version 1.98
 * @revision $Revision: 1.2 $
 * @author Andrea Forghieri
 * @copyright (C) Andrea Forghieri, www.solidsystem.it
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
 */

defined('_JEXEC') or die('Restricted access');

$item_title 		= $this->item_title;
$content_id			= $this->content_id;
$properties			= $this->properties;
$ce_name			= $this->ce_name;
$content_element	= $this->content_element;
$jsCode			= $this->jsCode;
$divs			= $this->divs;

// incluye javascript para objeto flash
JHTML::script('core/lang/Bs_Misc.lib.js');
JHTML::script('core/lang/Bs_Array.class.js');
JHTML::script('checkbox/Bs_Checkbox.class.js');
JHTML::script('tree/Bs_Tree.class.js');
JHTML::script('tree/Bs_TreeElement.class.js');
?>

<script type="text/javascript">
    /*
     * array which generates tree
     */
<?php
echo $jsCode;
?>

</script>
<div class="header icon-48-assign"><?php echo JText::_('Assign tags to').': ['.htmlspecialchars($item_title).']'; ?></div>
<div class="cp_info"><?php
    echo JText::_('Content type').': <b>' . $properties['content_element'].'</b> | '.
            JText::_('Section').': <b>' . $properties['section'].'</b> | '.
            JText::_('Category').': <b>' . $properties['category'] . '</b>';
    ?>
</div>
<div class="cp_add_tag">
    <form method="post" action="index.php" name="adminForm">            
        <div class="cp_navbar">
            <input type="hidden" name="option" value="com_customproperties"/>
            <input type="hidden" name="controller" value="hierarchictagging"/>
            <input type="hidden" name="view" value="hierarchictagging"/>
            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="ce_name" value="<?php echo $ce_name; ?>" />
            <input type="hidden" name="cid" value="<?php echo $content_id; ?>" />
            <input type="button" class="button" value="<?php echo JText::_('Add'); ?>" onclick="this.form.task.value='add';this.form.submit();"/>
            <input type="button" class="button" value="<?php echo JText::_('Replace'); ?>" onclick="this.form.task.value='replace';this.form.submit();" />
            <input type="button" class="button" value="<?php echo JText::_('Close'); ?>" onclick="window.parent.document.getElementById('sbox-window').close();"/>
        </div>        
        <?php
        echo $divs;
        ?>

    </form>
</div>


