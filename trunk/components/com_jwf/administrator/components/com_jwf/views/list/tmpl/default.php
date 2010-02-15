<?php
/**
* Default layout for list view
*
* @version		$Id: default.php 137 2009-05-23 11:28:39Z mostafa.muhammad $
* @package		Joomla
* @subpackage	Workflows
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');

?>

<script type="text/javascript">
//<![CDATA[
	function submitbutton(task){
	
		var form = $('adminForm');
		var checkedCount = 0;
		var firstCheckedItem  = -1;
		
		var checkboxGroup = form.elements['cid[]'];
		if(checkboxGroup){
			if(checkboxGroup && checkboxGroup.length == undefined){
				checkboxGroup    = new Array();
				if( form.elements['cid[]'] != undefined )
					checkboxGroup[0] = form.elements['cid[]'];
			}
			for( i=0; i<checkboxGroup.length; i++ ){
				if(checkboxGroup[i].checked){
					checkedCount++;
					if( firstCheckedItem == -1 )firstCheckedItem = i;
				}
			}
		} else {
			checkedCount = 0;
		}

		if( task == 'edit' ){
			if(checkedCount>1){
				alert('<?php echo JText::_("You may not select more than one item for this function")?>');
				return;
			}
		}
		submitform(task);	
	}
//]]>
</script>
<form action="index.php" method="get" name="adminForm" id='adminForm'>
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'Id' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->workflows ); ?>);" />
			</th>			
			<th>
				<?php echo JText::_( 'Title' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Component' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Categories' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Publish Information' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Author' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Date' ); ?>
			</th>
		</tr>		
	</thead>
	<tbody>
	<?php
	$k = 0;
	$config	=& JFactory::getConfig();
	$db     =& JFactory::getDBO();
	$now	=& JFactory::getDate();
	$nullDate = $db->getNullDate();
	for ($i=0, $n=count( $this->workflows ); $i < $n; $i++){
	
		$row = &$this->workflows[$i];
		$checked 	= JHTML::_('grid.checkedout',   $row, $i );
		$link 		= JRoute::_( 'index.php?option=com_jwf&task=edit&cid[]='. $row->id );
		
		/* Publish icon stuff , faithfully copied "with minor modifications" from com_content */
		$date		= JHTML::_('date',  $row->created, JText::_('DATE_FORMAT_LC4') );
		$publish_up =& JFactory::getDate($row->publish_up);
		$publish_down =& JFactory::getDate($row->publish_down);
		$publish_up->setOffset($config->getValue('config.offset'));
		$publish_down->setOffset($config->getValue('config.offset'));
		if ( $now->toUnix() <= $publish_up->toUnix() && $row->state == 1 ) {
				$img = 'publish_y.png';
				$alt = JText::_( 'Published' );
		} else if ( ( $now->toUnix() <= $publish_down->toUnix() ||
					  $row->publish_down == $nullDate ) && $row->state == 1 ) {
			$img = 'publish_g.png';
			$alt = JText::_( 'Published' );
		} else if ( $now->toUnix() > $publish_down->toUnix() && $row->state == 1 ) {
			$img = 'publish_r.png';
			$alt = JText::_( 'Expired' );
		} else if ( $row->state == 0 ) {
			$img = 'publish_x.png';
			$alt = JText::_( 'Unpublished' );
		}	
		$times = '';
		if (isset($row->publish_up)) {
			if ($row->publish_up == $nullDate) {
				$times .= JText::_( 'Start: Always' );
			} else {
				$times .= JText::_( 'Start' ) .": ". $publish_up->toFormat();
			}
		}
		if (isset($row->publish_down)) {
			if ($row->publish_down == $nullDate) {
				$times .= "<br />". JText::_( 'Finish: No Expiry' );
			} else {
				$times .= "<br />". JText::_( 'Finish' ) .": ". $publish_down->toFormat();
			}
		}
		/* End of publish icon stuff */
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $row->id; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
			</td>			
			<td align="center">
				<?php echo $row->component; ?>
			</td>			
			<td>
				<?php echo $row->category; ?>
			</td>						
			<td align="center">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'Publish Information' );?>::<?php echo $times; ?>">
				<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->state ? 'unpublish' : 'publish' ?>')">
					<img src="images/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" />
				</a>
			</span>
			</td>
			<td align="center">
				<?php echo $row->author; ?>
			</td>
			<td align="center">
				<?php echo JHTML::_('date',  $row->created, JText::_('DATE_FORMAT_LC4') ); ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="15">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	</table>
	<?php JHTML::_('jwf.legend'); ?>

</div>

<input type="hidden" name="option" value="com_jwf" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<?php echo JHTML::_( 'form.token' ); ?>	
</form>
