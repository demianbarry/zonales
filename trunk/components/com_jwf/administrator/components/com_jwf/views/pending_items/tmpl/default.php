<?php
/**
* Default layout for list view
*
* @version		$Id: default.php 1480 2009-08-24 15:11:16Z mostafa.muhammad $
* @package		Joomla
* @subpackage	Workflows
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');?>
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'Id' ); ?>
			</th>		
			<th>
				<?php echo JText::_( 'Title' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Current Station' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Waiting time' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Content Type' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'History' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Workflow Title' ); ?>
			</th>
		</tr>		
	</thead>
	<tbody>
	<?php
	
	$pManager =& getPluginManager();
	$pManager->loadPlugins('acl');
		
		
	$k = 0;
	$config	=& JFactory::getConfig();
	$db     =& JFactory::getDBO();
	$now	=& JFactory::getDate();
	$nullDate = $db->getNullDate();
	for ($i=0, $n=count( $this->items ); $i < $n; $i++){
	
		$row = &$this->items[$i];
		
		//Only allow administrating groups to view item history
		$canViewHistory = false;
		if( canManageWorkflows() ){
			$canViewHistory = true;
		} else {
			list($adminAclSystem, $adminAclGroup) = explode('.', $row->administratingGroup);
			foreach($this->aclPairs as $system => $gid){
				if( $system == $adminAclSystem && in_array($adminAclGroup, array_keys($gid) ) ){
					$canViewHistory = true;
				}
			}
		}
		


		$link 		 = JRoute::_( 'index.php?option=com_jwf&controller=item&task=edit&wid='. $row->wid.'&iid='.$row->iid );
		$historyLink = JRoute::_( 'index.php?option=com_jwf&controller=item&task=history&wid='. $row->wid.'&iid='.$row->iid );
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $row->id; ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
			</td>			
			
			
			<td align="center">
				<?php echo '<div style="color:blue;text-decoration:underline" class="hasTip editlinktip"  title="'.$row->currentTask.'"><strong>('. ($row->position+1) .')</strong> ' . stripslashes($row->currentStation) . '</div>'; ?>
			</td>			
			<td align="center">
			
				<?php 
				$waitingTime = JHTML::_('jwf.timeDifference',$row->created);
				$jnow =& JFactory::getDate();
				$now   = strtotime($jnow->toMySQL());
				$start = strtotime($row->created);
				$hoursPassed = ($now - $start)/3600;
				if($row->taskTime == 0){
					$tooltip = 
					  "<strong>".JText::_('Waiting time').":</strong> $waitingTime <br />"
					 ."<strong>".JText::_('No deadline')."</strong>";
					echo JHTML::_('jwf.progressBar', 0 , $tooltip, false);
				} else {
					$precentPassed = sprintf('%2.2f', ($hoursPassed / $row->taskTime) * 100 );
					if(intval($precentPassed) > 100)$precentPassed=100;
					
					$tooltip = 
					  "<strong>".JText::_('Waiting time').":</strong> $waitingTime <br />"
					 ."<strong>".JText::_('Allocated time').":</strong> $row->taskTime ".JText::_('Hours')."<br />"
					 ."<strong>".JText::_('Precent passed').":</strong> $precentPassed% <br />";
					echo JHTML::_('jwf.progressBar', $precentPassed, $tooltip, false);
					
				}
				?>
			</strong>
			</td>						
			<td align="center">
				<?php echo $row->contentType; ?>
			</td>
			<td align="center">
			<?php if($canViewHistory){ ?>
				<?php echo "<a href='$historyLink'>".JText::_('Item History')."</a>"; ?>
			<?php } else echo "-"; ?>
			</td>
			<td align="center">
				<?php echo $row->workflowTitle; ?>
			</td>

		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	</table>
</div>