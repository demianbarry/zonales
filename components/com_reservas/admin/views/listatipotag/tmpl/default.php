<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
?>
<form action="index.php?option=com_reservas" method="post" name="adminForm" id="adminForm">
	<table>
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_( 'Filtro' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
		</tr>
	</table>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="5"><?php echo JText::_( 'NUM' ); ?></th>
				<th width="20"><input type="checkbox" name="toggle" value=""
					onclick="checkAll(<?php echo count( $this->tipos ); ?>);" />
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort',   'TITLE', 't.tipo', @$this->lists['order_Dir'], @$this->lists['order'], 'listTipoTag' ); ?>
				</th>
				<th width="5"><?php echo JText::_( 'ID' ); ?></th>
			</tr>
		</thead>
		<?php
		$k = 0; $i = 0;
		foreach ($this->tipos as $tipo) {
			$checked = JHTML::_('grid.id', $i, $tipo->id);

			?>
			<tr class="<?php echo "row$k"; ?>">
				<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDITAR_TIPO_TAG' );?>::<?php echo $tipo->tipo; ?>">
						<b><a href="<?php echo $tipo->link; ?>"> <?php echo $tipo->tipo; ?> </a></b>
					</span>
				</td>
				<td>
					<?php echo $tipo->id; ?>
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
	<input type="hidden" name="task" value="listTipos" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>