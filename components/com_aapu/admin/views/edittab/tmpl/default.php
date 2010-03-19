<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.formvalidation');
?>

<!-- Validacion -->
<script language="javascript" type="text/javascript">
    <!--

    function submitbutton(pressbutton) {
        var form = document.adminForm;

        if (pressbutton == 'cancelTab') {
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
                        <input class="text_area required" type="text" name="name" id="name" value="<?php echo $this->tab->name; ?>" size="50" maxlength="90" title="<?php echo JText::_( 'QUOTA_TIPTITLE' ); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="label"><?php echo JText::_( 'Label' ); ?>:</label>
                    </td>
                    <td colspan="2">
                        <input class="text_area required" type="text" name="label" id="label" value="<?php echo $this->tab->label; ?>" size="50" maxlength="90" title="<?php echo JText::_( 'QUOTA_TIPTITLE' ); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="description"><?php echo JText::_( 'Description' ); ?>:</label>
                    </td>                   
                    <td colspan="2">
                        <textarea class="inputbox" cols="100" rows="3" name="description" id="description"><?php echo $this->tab->description; ?></textarea>
                        <!--<input class="text_area required" type="text" name="description" id="description" value="<?php echo $this->tab->description; ?>" size="100" maxlength="255" multiple="true" rows="3" title="<?php echo JText::_( 'QUOTA_TIPTITLE' ); ?>" />-->
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>

    <div class="clr"></div>

    <input type="hidden" name="id" value="<?php echo $this->tab->id; ?>" />
    <input type="hidden" name="option" value="<?php echo $option;?>" />
    <input type="hidden" name="task" value=""/>
    <?php echo JHTML::_( 'form.token' ); ?>
</form>