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
					onclick="checkAll(<?php echo count( $this->users ); ?>);" />
				</th>
				<th width="20%" class="title">
					<?php echo JHTML::_('grid.sort',   'NAME', 'a.name', @$this->lists['order_Dir'], @$this->lists['order'], 'listUsers' ); ?>
				</th>
                                <th width="15%"><?php echo JText::_( 'Username' ); ?></th>
                                <th width="18%"><?php echo JText::_( 'Group' ); ?></th>
                                <th width="20%"><?php echo JText::_( 'Email' ); ?></th>
                                <th width="18%"><?php echo JText::_( 'Last Visit' ); ?></th>
                                <th width="5%"><?php echo JText::_( 'Active' ); ?></th>
				<th width="1%"><?php echo JText::_( 'ID' ); ?></th>
			</tr>
		</thead>
		<?php
		$k = 0; $i = 0;
                foreach ($this->users as $user) {
			$checked = JHTML::_('grid.id', $i, $user->id);
                        $img = $user->block == 1 ? 'images/publish_x.png' : 'images/tick.png';
                        $alt = $user->block == 1 ? JText::_('Block') : JText::_('Active');

			?>
			<tr class="<?php echo "row$k"; ?>">
				<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'User Edition' );?>::<?php echo $user->name; ?>">
						<b><a href="<?php echo $user->link; ?>"> <?php echo $user->name; ?> </a></b>
					</span>
				</td>
                                <td>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'User Edition' );?>::<?php echo $user->username; ?>">
						<b><a href="<?php echo $user->link; ?>"> <?php echo $user->username; ?> </a></b>
					</span>
				</td>
                                <td>
					<?php echo $user->usertype; ?>
				</td>
                                <td>
					<?php echo $user->email; ?>
				</td>
                                <td>
					<?php echo $user->lastvisitDate; ?>
				</td>
                                <td align="center">
                                    <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $user->block ? 'unblock' : 'block' ?>')">
                                        <img src="<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" />
                                    </a>
                                </td>
				<td>
					<?php echo $user->id; ?>
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
	<input type="hidden" name="task" value="listUsers" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>