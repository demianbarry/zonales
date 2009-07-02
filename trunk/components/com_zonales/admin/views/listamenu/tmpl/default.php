<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
?>
<form action="index.php?option=com_zonales" method="post" name="adminForm" id="adminForm">
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
					onclick="checkAll(<?php echo count( $this->menus); ?>);" />
				</th>
				<th widht="15%" class="title">
					<?php echo JHTML::_('grid.sort',   'ID', 'm.id', @$this->lists['order_Dir'], @$this->lists['order'], 'listMenu' ); ?>
				</th>
				<th width="20%"><?php echo JText::_( 'Ìtem del Menu' ); ?></th>
				<th width="20%"><?php echo JText::_( 'Field Value' ); ?></th>
				<th width="20%"><?php echo JText::_( 'Modulo' ); ?></th>.
			</tr>
		</thead>
		<?php
		$k = 0; $i = 0;
		foreach ($this->menus as $menu) {
			$checked = JHTML::_('grid.id', $i, $menu->id);

			?>
			<tr class="<?php echo "row$k"; ?>">
				<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDITAR_MENU' );?>::<?php echo $menu->id; ?>">
						<b><a href="<?php echo $menu->link; ?>"> <?php echo $menu->id; ?> </a></b>
					</span>
				</td>
				<td>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDITAR_MENU' );?>::<?php echo $menu->jname; ?>">
						<b><a href="<?php echo $menu->jmenu_edit_link; ?>"> <?php echo $menu->jname; ?> </a></b>
					</span>
				</td>
				<td>
					<?php echo $menu->flabel; ?>
					<?php if ($menu->vlabel) {
						echo "»&nbsp;" . $menu->vlabel;
					} ?>
				</td>
				<td>
					<?php echo $menu->title; ?>
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
	<input type="hidden" name="task" value="listMenu" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>