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
						<label for="tipo"><?php echo JText::_( 'Tipo' ); ?>:</label>
					</td>
					<td colspan="2">
						<input class="text_area required" type="text" name="tipo" id="tipo" value="<?php echo $this->tipotag->tipo; ?>" size="50" maxlength="255" title="<?php echo JText::_( 'QUOTA_TIPTITLE' ); ?>" />
					</td>
				</tr>
			</table>
		</fieldset>
	</div>

	<div class="clr"></div>

	<input type="hidden" name="id" value="<?php echo $this->tipotag->id; ?>" />
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="task" value=""/>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>