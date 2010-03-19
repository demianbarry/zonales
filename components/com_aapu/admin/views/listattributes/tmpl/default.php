<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
?>
<form action="index.php?option=com_aapu" method="post" name="adminForm" id="adminForm">
	<table>
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
		</tr>
	</table>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%"><?php echo JText::_( 'NUM' ); ?></th>
				<th width="2%"><input type="checkbox" name="toggle" value=""
					onclick="checkAll(<?php echo count( $this->attributes ); ?>);" />
				</th>
				<th width="25%" class="title">
					<?php echo JHTML::_('grid.sort',   'LABEL', 'a.label', @$this->lists['order_Dir'], @$this->lists['order'], 'listAttributes' ); ?>
				</th>
                                <th width="25%"><?php echo JText::_( 'Name' ); ?></th>
                                <th width="46%"><?php echo JText::_( 'Description' ); ?></th>
				<th width="1%"><?php echo JText::_( 'ID' ); ?></th>
			</tr>
		</thead>
		<?php
		$k = 0; $i = 0;
		foreach ($this->attributes as $attribute) {
			$checked = JHTML::_('grid.id', $i, $attribute->id);

			?>
			<tr class="<?php echo "row$k"; ?>">
				<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Attribute Edition' );?>::<?php echo $attribute->label; ?>">
						<b><a href="<?php echo $attribute->link; ?>"> <?php echo $attribute->label; ?> </a></b>
					</span>
				</td>
                                <td>
					<?php echo $attribute->name; ?>
				</td>
                                <td>
					<?php echo $attribute->description; ?>
				</td>
				<td>
					<?php echo $attribute->id; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
			$i++;
		}
		?>
		<tfoot>
			<tr>
				<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
	</table>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="task" value="listAttributes" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>