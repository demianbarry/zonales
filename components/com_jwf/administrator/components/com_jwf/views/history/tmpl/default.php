<?php
/**
* Default layout for History view
*
* @version		$Id: default.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
* @package		Joomla
* @subpackage	Workflows
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

?>
<table class="adminlist">
	<thead>
		<tr>
			<th>
				<?php echo JText::_( 'Action' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Date' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'By' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Station' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Details' ); ?>
			</th>
		</tr>		
	</thead>
	<tbody>
	<?php
	foreach($this->history as $entry){
		echo '<tr>';
		
		$pManager =& getPluginManager();
		list($plugin_type, $plugin_name) = explode('.', $entry->type);
		
		$translatedDetails = '';
		if( $plugin_type != 'core' ){
			$pManager->loadPlugins($plugin_type);
			$response  = $pManager->invokeMethod( $plugin_type, 'renderHistoryEntry',  array($plugin_name),array($this->workflow, $entry) );
			$translatedDetails = $response[$plugin_name];
		} else {
			$translatedDetails = JHTML::_('JWF.renderCoreHistoryEntry',$plugin_name, $this->workflow,$entry);
		}

		echo "<td>$entry->title</td>";
		echo "<td>$entry->date</td>";
		echo "<td>$entry->author</td>";
		
		$stationName = $entry->station->title;
		echo "<td>$stationName</td>";
	
	
		echo "<td>$translatedDetails</td>";
		
		echo '</tr>';
		
	}
	?>
	</tbody>
</table>
