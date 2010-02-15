<?php
/**
* Default layout for list view
*
* @version		$Id: default.php 1321 2009-08-03 20:48:26Z mostafa.muhammad $
* @package		Joomla
* @subpackage	Workflows
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');

$contentType = $this->workflow->component;
$wid 		 = $this->workflow->id;

list($firstItem) =  array_values($this->items); 

$iid         = $firstItem->iid;
$title       = $firstItem->title;

//Disabled for the time being
$version     = 0;


$pManager =& getPluginManager();
$pManager->loadPlugins('component');
$response = $pManager->invokeMethod( 'component', 'getItemRevision',  array($contentType), array( $iid, 'head' ));
$currentRevisionData = $response[$contentType];

$response = $pManager->invokeMethod( 'component', 'getEditLink',  array($contentType), array( $iid ));
$editLink = $response[$contentType];

$moveForwardLink  = JRoute::_("index.php?option=com_jwf&controller=item&task=moveForward&wid=$wid&iid=$iid&version=$version"); 
$moveBackwardLink = JRoute::_("index.php?option=com_jwf&controller=item&task=moveBackward&wid=$wid&iid=$iid&version=$version"); 

		

?>
<h1><?php echo stripslashes($this->currentStation->title) ?></h1>
<h2><?php echo JText::_('Task') ?></h2>
<p><?php echo $this->currentStation->task; ?></p>

<hr />
<h1><?php echo JText::_('Content Item') ?></h1>

<div id='item-container'>
	<h1><?php echo $currentRevisionData->title; ?></h1>
	<p><?php echo $currentRevisionData->body; ?></p>
	<?php if(isset($currentRevisionData->additional)){ ?>
	<hr />
	<h3><?php echo JText::_('Additional information') ?></h3>
	<p><?php echo $currentRevisionData->additional; ?></p>
	<?php } ?>
</div>
<hr />
<h1><?php echo JText::_('Stations') ?></h1>

<div id='stations'>
	<div id='backward-station'>
	<?php
		if( $this->previousStation == null ){
			echo "<div class='stop'>".JText::_('None')."</div>";
		} else {
			echo "<div class='hasTip' title='{$this->previousStation->task}'><a href='$moveBackwardLink' class='move'>".JText::_('Move to').' '.stripslashes($this->previousStation->title)."</a></div>";
		}		
	?>
	</div>
	<div id='current-station'>
	<?php
		echo "<div class='hasTip' title='{$this->currentStation->task}'>".stripslashes($this->currentStation->title)."</div>";
		echo "<a href='$editLink' class='edit' target='_blank'>".JText::_('Edit'). " (" . $title .")</a>";
		
	?>
	</div>
	<div id='next-station'>
	<?php
		if( $this->nextStation == null ){
			echo "<div class='stop'>".JText::_('None')."</div>";
		} else {
			echo "<div class='hasTip' title='{$this->nextStation->task}'><a href='$moveForwardLink' class='move'>".JText::_('Move to').' '.stripslashes($this->nextStation->title)."</a></div>";
		}		
	?>
	</div>
	<br clear='all' />
</div>


<div id='field-container'>
<?php
$output = '';
/*
$pane	=& JPane::getInstance('sliders');
//Very dirty hack to fix the annoying issue with sliders where a slider is open, but too short to show its contents, now they all show up closed
$output .= '<div style="display:none">';
$output .= $pane->startPane('xyz');	
$output .= $pane->startPanel( 'xyz-p', 'xyz-p' );
$output .= $pane->endPanel();
$output .= $pane->endPane();
$output .= '</div>';
//End of dirty hack
$output .= $pane->startPane("field-pane");
*/
foreach($this->fields as $name => $html ){
	$title   = $name;
	//$output .= $pane->startPanel( $title, $title."-page" );
	$output .= $html;
	//$output .= $pane->endPanel();
}	
//$output .= $pane->endPane();
echo $output;

?>
</div>
<form action="index.php" method="post" name="adminForm" id='adminForm'>
	<input type="hidden" name="data[id]" value="<?php echo $this->workflow?$this->workflow->id:'' ?>"  />
	<input type="hidden" name="params[workflowData]" value="" id="workflowData" />
	<input type="hidden" name="option" value="com_jwf" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>	
</form>
