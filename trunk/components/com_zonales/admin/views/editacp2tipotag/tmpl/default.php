<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.formvalidation');
?>

<!-- Validacion -->
<script language="javascript" type="text/javascript">
<!--

function submitbutton(pressbutton) {
    var form = document.adminForm;

    if (pressbutton == 'cancelTipoTag') {
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
    if (frm.field_id.hasClass('invalid')) {
        alert("<?php echo JText::_( 'TIPO_WARNING', true ); ?>");
    }
    else if (frm.tipo_id.hasClass('invalid')) {
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
						<label for="title"><?php echo JText::_( 'Tipo de Tag' ); ?>:</label>
					</td>
					<td colspan="2">
						<?echo $this->lists['tipo_lst']; ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="title"><?php echo JText::_( 'Field' ); ?>:</label>
					</td>
					<td colspan="2">
						<?echo $this->lists['field_lst']; ?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>

	<div class="clr"></div>

	<input type="hidden" name="id" value="<?php echo $this->cp2tipotag->id; ?>" />
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="task" value=""/>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>