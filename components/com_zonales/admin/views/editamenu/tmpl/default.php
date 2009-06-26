<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.formvalidation');
?>

<!-- Validacion -->
<script language="javascript" type="text/javascript">
<!--
function submitbutton(pressbutton) {
    var form = document.adminForm;

    if (pressbutton == 'cancelMenu') {
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
    if (frm.title.hasClass('invalid')) {
        alert("<?php echo JText::_( 'MENU_TITLE_WARNING', true ); ?>");
    }
    return false;
}

window.addEvent("domready", function(){
		$("field_id").addEvent("change", function() {
			var url="index.php?option=com_zonales&format=raw&task=listValues&fieldId="+this.getValue();
			new Ajax(url, {
				method: 'get',
				update:$("value_id_container")
			}).request();
		});
	});
//-->
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">

	<div class="col width-60">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'Details' ); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key">
						<label for="value_id"><?php echo JText::_( 'Value' ); ?>:</label>
					</td>
					<td colspan="2">
						<?php echo $this->lists['field_lst']; ?>
					</td>
					<td id="value_id_container">
							<?php if ($this->menu->id) echo $this->lists['value_lst']; ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="menu_id"><?php echo JText::_( 'Item del Menú' ); ?>:</label>
					</td>
					<td colspan="2">
						<?php echo $this->lists['item_lst']; ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="title"><?php echo JText::_( 'Title' ); ?>:</label>
					</td>
					<td colspan="2">
						<input class="text_area required" type="text" name="title" id="title" value="<?php echo $this->menu->title; ?>" size="50" maxlength="255" title="<?php echo JText::_( 'MENU_TIPTITLE' ); ?>" />
					</td>
				</tr>
			</table>
		</fieldset>
	</div>

	<div class="clr"></div>

	<input type="hidden" name="id" value="<?php echo $this->menu->id; ?>" />
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="task" value=""/>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>