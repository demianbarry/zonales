<?php
/**
* JWF HTML Helper class
*
* @version		$Id: jwf.php 1480 2009-08-24 15:11:16Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.helpers
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

/**
 * JWF HTML Helper class
 *
 * @package    Joomla
 * @subpackage JWF.helpers
 */
class JHTMLJwf
{		

	
	/**
	 * Mimics JToolBarHelper::title for frontend
	 * 
	 * @access	public
	 * @param string $title Text to be display
	 * @param string $cssClass CSS Class name
	 * @return string HTML output
	 */
	 function title($title, $cssClass){
		echo "<h1 class='$cssClass'>$title</h1>";
	}
	
	/**
	 * Mimics JToolBarHelper::back for frontend
	 * 
	 * @access	public
	 * @return string HTML output for backbutton
	 */
	function backButton(){
		echo '<input type="button" onclick="javascript:history.back()" class="back-button" value="'.JText::_('<< Back').'" />';
	}
	
	
	/**
	 * Renders History entries that were created by the core
	 * 
	 * @access	public
	 * @see JWFModelHistory
	 * @see JWFModelHistory::add
	 * @param string $type Type of event (e.g. move, delete)
	 * @param object $workflow Workflow object that this history entry belongs to
	 * @param object $entry History entry to be rendered
	 * @return string HTML output of the history entry
	 */
	function renderCoreHistoryEntry( $type, $workflow, $entry ){
		
		$historyObject = $entry->value;

		switch($type){
			case 'move':
			{
				$destinationStation = unserialize(base64_decode($historyObject->destination));
				
				if( $historyObject->direction == 'forward' )
					return JText::_('Moved forward to station').' ('.$destinationStation->title.')';

				if( $historyObject->direction == 'backward' )
					return JText::_('Moved backward to station').' ('.$destinationStation->title.')';
				
				return '';
			}
			default:
				return '';
		}
		
		
	}
	
	/**
	 * Unloads Joomla's MooTools and loads JWF's [Nasty Hack]
	 * 
	 * @access	public
	 * @return void
	 */
	function reloadMootools(){
		//Nasty hack warning [Unload Mootools 1.11 and load 1.2]
		JHTML::_('script', 'dummy.js', 'media/com_jwf/scripts/');
		$doc = &JFactory::getDocument();
		$newScriptArray = array();
		$foundMootools = false;
		foreach( $doc->_scripts as $k => $v )
			if( strpos($k, 'media/system/js/mootools') === false )$newScriptArray[$k] = $v;
			else $foundMootools=true;
		if( $foundMootools )$newScriptArray[JURI::root().'media/com_jwf/scripts/mootools.js'] = 'text/javascript' ;
		$doc->_scripts = $newScriptArray;
		//End of nasty hack
	}
	
	/**
	 * Creates a hidden pane to fix a bug that renders the first pane inaccessible
	 * 
	 * @access	public
	 * @return string HTML output for the hidden pane
	 */
	function hiddenPane(){
		
		$pane	=& JPane::getInstance('sliders');
		$output  = '<div style="display:none">';
		$output .= $pane->startPane('xyz');
		$output .= $pane->startPanel( 'xyz-p', 'xyz-p' );
		$output .= $pane->endPanel();
		$output .= $pane->endPane();
		$output .= '</div>';
		return $output;
	}
	
	/**
	 * A debug dump method
	 * 
	 * @access	public
	 * @param mixed $v Variable to be dumped
	 * @return void
	 */
	function d($v){
		if(JWF_DEBUG_STATE>0){
			echo "<pre>";var_dump($v);echo "</pre>";
		}
	}
	
	/**
	 * Calculates difference between "Now" and a given date (Copied from php.net)
	 * 
	 * @access	public
	 * @link  http://www.php.net/manual/en/function.time.php#89415
  	 * @param string $date The date to compare against
	 * @return string Time difference "e.g 2 days, 1 hour"
	 */
	function timeDifference($date){

		if(empty($date)) {
			return "";
		}
		$periods         = array(JText::_("second(s)"), 
								 JText::_("minute(s)"), 
								 JText::_("hour(s)"), 
								 JText::_("day(s)"), 
								 JText::_("week(s)"),  
								 JText::_("month(s)"),  
								 JText::_("year(s)"),  
								 JText::_("decade(s)"));
		$lengths         = array("60","60","24","7","4.35","12","10");
		
		$jnow =& JFactory::getDate();
		$now = strtotime($jnow->toMySQL());
			
		$unix_date       = strtotime($date);
        // check validity of date
		if(empty($unix_date)) {   
			return "Bad date";
		}
		
		if($now > $unix_date) {   
			$difference     = $now - $unix_date;
		} else {
			$difference     = $unix_date - $now;
        }
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}
		$difference = round($difference);
	    return "$difference $periods[$j]";

	}
	
	function trimComma( $text ){
		$text[strlen($text)-2] = ' ';
		return $text;
	}
	
	/**
	 * Renders a colored progress bar, color changes based on the value of the bar
	 * 
	 * @access	public
	 * @param int $percentComplete The value in percent of the progress bar
	 * @param string $tooltip The tooltip to display when mouse hovers over the progress bar
	 * @param bool $greenTowardsEnd whether or not the progress bar will turn green if it is close to 100%
	 * @return string HTML output of the progress bar
	 */
	 function progressBar( $percentComplete, $tooltip, $greenTowardsEnd=false ){
		
		if( $percentComplete >= 0 && $percentComplete < 50 ){
			$class = $greenTowardsEnd?'red':'green';
		}
		if( $percentComplete >= 50 && $percentComplete < 80 ){
			$class = 'yellow';
		}
		if( $percentComplete >= 80 && $percentComplete <= 100 ){
			$class = $greenTowardsEnd?'green':'red';
		}
		
		return "<div class='hasTip progress' title='$tooltip'><div class='done $class' style='width:$percentComplete%;'>&nbsp;</div></div>";	
	}
	
	/**
	 * Returns indented code , used to make output HTML and Javascript more easy to read
	 * 
	 * @access	public
	 * @param string $text The text to be indented
	 * @param int $indention The level of indention
	 * @return string Indented text
	 */
	 function indentedLine($text, $indention){
		$tabs = str_repeat( "\t" , $indention );
		return $tabs.$text."\n";
	}
	
	/**
	 * Starts an indented Javascript Block 
	 * 
	 * @access	public
	 * @param int $indention The level of indention
	 * @return string Indented Javascript start code
	 */
	function startJSBlock( $indention ){
		$output  = JHTMLJwf::indentedLine('<script type="text/javascript">',$indention);
		$output .= JHTMLJwf::indentedLine('// <![CDATA[',$indention);
		return $output;
	}
	
	/**
	 * Ends an indented Javascript Block 
	 * 
	 * @access	public
	 * @param int $indention The level of indention
	 * @return string Indented Javascript end code
	 */
	function endJSBlock( $indention ){
		$output  = JHTMLJwf::indentedLine('// ]]>',$indention);
		$output .= JHTMLJwf::indentedLine('</script>',$indention);
		return $output;
	}
	
	
	/**
	 * Outputs Legend at the bottom of the workflow main view 
	 * 
	 * @access	public
	 * @return void
	 */
	function legend()
	{

		?>
		<table cellspacing="0" cellpadding="4" border="0" align="center">
		<tr align="center">
			<td>
			<img src="images/publish_y.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Pending' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'Published, but is' ); ?> <u><?php echo JText::_( 'Pending' ); ?></u> |
			</td>
			<td>
			<img src="images/publish_g.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Visible' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'Published and is' ); ?> <u><?php echo JText::_( 'Current' ); ?></u> |
			</td>
			<td>
			<img src="images/publish_r.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Finished' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'Published, but has' ); ?> <u><?php echo JText::_( 'Expired' ); ?></u> |
			</td>
			<td>
			<img src="images/publish_x.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Finished' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'Not Published' ); ?>
			</td>


		</tr>
		<tr>
			<td colspan="10" align="center">
			<?php echo JText::_( 'Click on icon to toggle state.' ); ?>
			</td>
		</tr>
		</table>
		<?php
	}
}