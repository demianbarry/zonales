<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.formvalidation');
?>

<!-- Validacion -->
<script language="javascript" type="text/javascript">
    <!--

    function submitbutton(pressbutton) {
        var form = document.adminForm;

        if (pressbutton == 'cancelAttribute') {
            submitform( pressbutton );
            return;
        }

        if (document.formvalidator.isValid(form) == false) {
            return validateForm(form);
        }

        submitform( pressbutton );
        return true;
    }

    function validateForm(frm) {
        if (frm.tipo.hasClass('invalid')) {
            alert("<?php echo JText::_( 'TIPO_WARNING', true ); ?>");
        }
        return false;
    }

    //-->
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">

    <div class="col width-60">
        <fieldset class="adminform">
            <legend><?php echo JText::_( 'Details' ); ?></legend>
            <table class="admintable">
                <tr>
                    <td class="key">
                        <label for="name"><?php echo JText::_( 'Name' ); ?>:</label>
                    </td>
                    <td colspan="2">
                        <input class="text_area required" type="text" name="name" id="name" value="<?php echo $this->attribute->name; ?>" size="50" maxlength="90" title="<?php echo JText::_( 'QUOTA_TIPTITLE' ); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="label"><?php echo JText::_( 'Label' ); ?>:</label>
                    </td>
                    <td colspan="2">
                        <input class="text_area required" type="text" name="label" id="label" value="<?php echo $this->attribute->label; ?>" size="50" maxlength="90" title="<?php echo JText::_( 'QUOTA_TIPTITLE' ); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="description"><?php echo JText::_( 'Description' ); ?>:</label>
                    </td>                   
                    <td colspan="2">
                        <textarea class="inputbox" cols="100" rows="3" name="description" id="description"><?php echo $this->attribute->description; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="comments"><?php echo JText::_( 'Comments' ); ?>:</label>
                    </td>
                    <td colspan="2">
                        <textarea class="inputbox" cols="100" rows="3" name="comments" id="comments"><?php echo $this->attribute->comments; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="from"><?php echo JText::_( 'From' ); ?>:</label>
                    </td>
                    <td colspan="2">
                        <?php echo JHTML::calendar($this->attribute->from, 'from', 'from','%Y-%m-%d'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="to"><?php echo JText::_( 'To' ); ?>:</label>
                    </td>
                    <td colspan="2">
                        <?php echo JHTML::calendar($this->attribute->to, 'to', 'to','%Y-%m-%d'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="required"><?php echo JText::_( 'Required?' ); ?>:</label>
                    </td>
                    <td colspan="2">
                        <?php echo  JHTML::_('select.booleanlist', 'required', '', $this->attribute->required ) ?>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="attribute_class_id"><?php echo JText::_( 'Tab' ); ?>:</label>
                    </td>
                    <td colspan="2">
                        <?php echo JHTML::_('select.genericlist', $this->types, 'attribute_class_id', '','id', 'label', $this->attribute->attribute_class_id ) ?>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="data_type_id"><?php echo JText::_( 'Data Type' ); ?>:</label>
                    </td>
                    <td colspan="2">
                        <?php echo JHTML::_('select.genericlist', $this->dataTypes, 'data_type_id', '','id', 'label', $this->attribute->data_type_id ) ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>

    <div class="clr"></div>

    <input type="hidden" name="id" value="<?php echo $this->attribute->id; ?>" />
    <input type="hidden" name="option" value="<?php echo $option;?>" />
    <input type="hidden" name="task" value=""/>
    <?php echo JHTML::_( 'form.token' ); ?>
</form>