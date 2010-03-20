<?php
defined ('_JEXEC') or die ('Restricted Access');
class HTML_classifieds
{
	function showcss($file, $option)
	{
		$file = stripslashes($file);
        $f = fopen($file, "r");
        $content = fread($f, filesize($file));
        $content = htmlspecialchars($content);
        ?>
        <form action = "index.php" method = "post" name = "adminForm" class = "adminForm" id = "adminForm">
        <table cellpadding = "4" cellspacing = "0" border = "0" width = "100%" class = "adminform">
                    <tr>
                <td>
                    <textarea cols = "100" rows = "20" name = "csscontent"><?php echo $content; ?></textarea>
                </td>
            </tr>
            </table>
               <input type = "hidden" name = "file" value = "<?php echo $file; ?>"/>

        <input type = "hidden" name = "option" value = "<?php echo $option; ?>"> <input type = "hidden" name = "task" value = ""> <input type = "hidden" name = "boxchecked" value = "0">
    </form><?php 
	}
	function config($row, $lists, $option)
	{
	?>
	<form action="index.php" method="post" name="adminForm" id="adminForm">
		<fieldset class="adminForm">
			<legend>Configuration</legend>
			<table class="adminTable">
			<tr>
				<td width="200" align="right" class="key">
				Number of days to show ads: 
				</td>
				<td>
				<input class="text_area" type="text" name="days_shown" id="days_shown" size ="10" maxlength="3" value="<?php echo $row->days_shown; ?>" />
				</td>
			</tr>
			<tr>
				<td width="200" align="right" class="key">
				Prune Time (in days): 
				</td>
				<td>
				<input class="text_area" type="text" name="prune" id="prune" size ="10" maxlength="3" value="<?php echo $row->prune; ?>" />
				</td>
			</tr>
			<tr>
				<td width="200" align="right" class="key">
				Ad Summary font colour (Hex code): 
				</td>
				<td>
				<input style="text-transform: uppercase;" class="text_area" type="text" name="font_color" id="font_color" size ="10" maxlength="7" value="<?php echo $row->font_color; ?>" />
				 <i>e.g. #000000</i></td>
			</tr>
				<tr>
				<td width="200" align="right" class="key">
				Ad State font colour (Hex code): 
				</td>
				<td>
				<input style="text-transform: uppercase;" class="text_area" type="text" name="ad_state_font" id="ad_state_font" size ="10" maxlength="7" value="<?php echo $row->ad_state_font; ?>" />
				 <i>e.g. #000000</i></td>
			</tr>
				<tr>
				<td width="200" align="right" class="key">
				Ad Details font colour (Hex code): 
				</td>
				<td>
				<input style="text-transform: uppercase;" class="text_area" type="text" name="ad_detail_font" id="ad_detail_font" size ="10" maxlength="7" value="<?php echo $row->ad_detail_font; ?>" />
				 <i>e.g. #000000</i></td>
			</tr>
			<tr>
				<td width="200" align="right" class="key">
				Currency Symbol: 
				</td>
				<td>
				<?php 
					echo $lists['currency'];
					?></td>
			</tr>

			<tr>
				<td width="200" align="right" class="key">
				Minimum Access Level to view ads: 
				</td>
				<td>
				<?php 
					echo $lists['access'];
					?></td>
			</tr>
			<tr>
				<td width="200" align="right" class="key">
				Distance Finder: 
				</td>
				<td>
				<?php 
					echo $lists['distance'];
					?> <i>(Required for Google Maps to work)</i></td>
			</tr>			
			<tr>	<td width="200" align="right" class="key">Google Map Directions: </td>
			<td><?php echo $lists['map']; ?> <i><a target="_blank" href="http://code.google.com/apis/maps/signup.html">(Google map API Key Required)</a></i></td>
			</tr>
			<tr> <td width="200" align="right" class="key">
			Google Map API Key: </td><td><input class="text_area" type="text" name="googapi" id="googapi" size="100" value="<?php echo $row->googapi; ?>" />
			</td>
			</tr>
			<tr>
				<td width="200" align="right" class="key">
				Email users 1 day before ad expires: 
				</td>
				<td>
				<?php 
					echo $lists['emailusers'];
					?> <i>(Use email Settings to configure options)</i></td>
			</tr>
			</table>
		</fieldset>	
		<input type="hidden" name="id" value="1" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
	<?php 
	}
	function email($row, $option)
	{
		$editor =& JFactory::getEditor();
		?>
				<form action="index.php" method="post" name="adminForm" id="adminForm">
			<fieldset class="adminForm">
				<legend>Email Settings</legend>
				<table class="admintable">
				<tr>
					<td width="100" align="right" class="key">
					Email Subject: 
					</td>
					<td>
					<input class="text_area" type="text" name="subject" id="subject" size="100" maxlength="250" value="<?php echo $row->subject; ?>" />
					</td>
				</tr><tr>
					<td width="100" align="right" class="key">
					From name: 
					</td>
					<td>
					<input class="text_area" type="text" name="fromname" id="fromname" size="100" maxlength="250" value="<?php echo $row->fromname; ?>" />
					</td>
				</tr><tr>
					<td width="100" align="right" class="key">
					From email address:
					</td>
					<td>
					<input class="text_area" type="text" name="fromemail" id="fromemail" size="100" maxlength="250" value="<?php echo $row->fromemail; ?>" />
					</td>
				</tr>
				</table>
			</fieldset>
		
			
			<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="" />
			
		</form>
		<?php 
	}
	function editClassified($row, $lists, $option, $currency)
	{
		$editor =& JFactory::getEditor();
		?>
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >
			<fieldset class="adminForm">
				<legend>Ad Details</legend>
				<table class="admintable">
				<tr>
					<td width="100" align="right" class="key">
					Ad Title: 
					</td>
					<td>
					<input class="text_area" type="text" name="ad_name" id="ad_name" size="100" maxlength="250" value="<?php echo $row->ad_name; ?>" />
					</td>
				</tr><tr>
					<td width="100" align="right" class="key">
					Category: 
					</td>
					<td>
					<?php 
					echo $lists['cat_name'];
					?>
					</td>
				</tr><tr>
					<td width="100" align="right" class="key">
					Description: 
					</td>
					<td>
					<?php 
					echo $editor->display( 'ad_desc', $row->ad_desc , '100%', '150', '40', '5');
					?>
					</td>
				</tr><tr>
					<td width="100" align="right" class="key">
					Item Asking Price:
					</td>
					<td>
					<?php echo $currency; ?><input class="text_area" type="text" name="ad_price" id="ad_price" size="7" maxlength="10" value="<?php echo $row->ad_price; ?>" />
					</td>
				</tr><tr>
					<td width="100" align="right" class="key">
					For Sale or Wanted?
					</td>
					<td>
					<?php echo $lists['ad_state']; ?>
					</td>
				</tr><tr>
					<td width="100" align="right" class="key">
					Method Of Transport: 
					</td>
					<td>
					<?php echo $lists['ad_delivery']; ?>
					</td>
				</tr><tr>
					<td width="100" align="right" class="key">
					Location of Item: </td>
					<td>
					<input class="text_area" type="text" name="ad_location" id="ad_location" size="30" maxlength="100" value="<?php echo $row->ad_location; ?>" />
					</td>
				</tr><tr>
					<td width="100" align="right" class="key">
					Item Postcode: </td>
					<td>
					<input class="text_area" type="text" name="ad_post" id="ad_post" size="10" maxlength="10" value="<?php echo $row->ad_post; ?>" /> <i>Format : XX11 1XX or XX1 1XX (UK Only)</i>
					</td>
				</tr><tr>
					<td width="100" align="right" class="key">
					Contact Name: 
					</td>
					<td>
					<input class="text_area" type="text" name="contact_name" id="contact_name" size="50" maxlength="250" value="<?php echo $row->contact_name; ?>" />
					</td>
				</tr><tr>
					<td width="100" align="right" class="key">
					Contact Telephone No:
					</td>				
					<td>
					<input class="text_area" type="text" name="contact_tel" id="contact_tel" size="30" maxlength="50" value="<?php echo $row->contact_tel; ?>" />
					</td>
				</tr><tr>
					<td width="100" align="right" class="key">
					Contact Email Address: 
					</td>
					<td>
					<input class="text_area" type="text" name="contact_email" id="contact_email" size="65" maxlength="255" value="<?php echo $row->contact_email; ?>" />
					</td>
				</tr><tr>
					<td width="153" align="right" class="key">
					Images:
					</td>
					<td>
					<input size="25" name="img1" type="file" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10pt" class="box"/>
			  		<input size="25" name="img2" type="file" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10pt" class="box"/><br />If resubmitting but not changing images leave fields blank
					</td>
				</tr><tr>
					<td width="100" align="right" class="key">
					Published: 
					</td><td>
					<?php echo $lists['published']; ?>
					</td>
				</tr>
				</table>
			</fieldset>
			<?php $date =& JFactory::getDate(); ?>
			
			<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="" />
			<?php 
			$user =& JFactory::getUser();
			$user_id = $user->get( 'id' );
			?>
			<input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
		</form>
<?php 
	}
	function showClassifieds($option, &$lists, &$rows, &$pageNav)
	{ ?>
	<script language="javascript" type="text/javascript">
function tableOrdering( order, dir, task )
{
        var form = document.adminForm;
 
        form.filter_order.value = order;
        form.filter_order_Dir.value = dir;
        document.adminForm.submit( task );
}
</script>
	
	<form action="index.php" method="post" name="adminForm">
	<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" />
				</th>
				<th class="title"><?php echo JHTML::_('grid.sort',   'Name', 'ad_name', $lists['order_Dir'], $lists['order'] ); ?></th>
				<th width="15%"><?php echo JHTML::_('grid.sort',   'Category', 'cat_name', $lists['order_Dir'], $lists['order'] ); ?></th>
				<th width="10%"><?php echo JHTML::_('grid.sort',   'State', 'ad_state', $lists['order_Dir'], $lists['order'] ); ?></th>
				<th width="10%"><?php echo JHTML::_('grid.sort',   'Contact Name', 'contact_name', $lists['order_Dir'], $lists['order'] ); ?></th>
				<th width="10%"><?php echo JHTML::_('grid.sort',   'Date Created', 'date_created', $lists['order_Dir'], $lists['order'] ); ?></th>
				<th width="10%">User Name</th>
			</tr>
		</thead>
		<?php 
		jimport('joomla.filter.output');
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++)
		{
			$row = &$rows[$i];
			$checked = JHTML::_('grid.id', $i, $row->id );
			$published = JHTML::_('grid.published', $row, $i );
			$link = JFilterOutput::ampReplace( 'index.php?option=' . $option . '&task=edit&cid[]=' . $row->id);
			$db =& JFactory::getDBO();
			$query = "SELECT name FROM #__users WHERE id = '" . $row->user_id . "'";
			$db->setQuery($query);
			$name = $db->loadResult();
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $checked; ?>
				</td>
				<td>
					<a href="<?php echo $link; ?>">
					<?php echo $row->ad_name; ?></a>
				</td>
				<td>
					<?php echo $row->cat_id; ?>
				</td>
				<td>
					<?php echo $row->ad_state; ?>
				</td>
				<td>
					<?php echo $row->contact_name; ?>
				</td>
				<td>
					<?php echo $row->date_created; ?>
				</td>
				<td align="center">
					<?php echo $name; ?>
				</td>
			</tr>
			<?php 
			$k = 1 - $k;
		}
		?>
	<tr>
		<td colspan="7"><center><?php echo $pageNav->getListFooter(); ?></center></td>
	</tr>
	</table>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
		
	</form>	
	<?php	
	}
	function showCategories($option, &$rows, &$pageNav)
	{
		?>
		<form action="index.php" method="post" name="adminForm">
		<table class="adminlist">
			<thead>
				<tr>
					<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" />
					</th>
					<th class="title">Category Name</th>
					<th width="50%">Category Description</th>
					<th>Order</th>
				</tr>
			</thead>
			<?php 
			$k = 0;
			for ($i=0, $n=count($rows); $i < $n; $i++) {
				$row = &$rows[$i];
				$checked = JHTML::_('grid.id', $i, $row->id );
				$link = JFilterOutput::ampReplace('index.php?option=' . $option . '&task=editcategory&cid[]=' . $row->id);
			?>
				<tr class="<?php echo"row$k"; ?>">
					<td><?php echo $checked; ?></td>
					<td><a href="<?php echo $link; ?>"><?php echo $row->cat_name; ?></a></td>
					<td><?php echo $row->cat_desc; ?></td>
					<td><?php echo $row->ordering?></td>
				</tr>
				<?php $k = 1 - $k;
			} ?>
			<tfoot>
			<tr><td colspan="4"><?php echo $pageNav->getListFooter(); ?></td></tr>
			</tfoot>
			</table>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="categories" />
			<input type="hidden" name="boxchecked" value="0" /></form>	
	<?php	
	}
	function editCategory ($row, $option)
	{
		?>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
			<fieldset class="adminForm">
				<legend>Category</legend>
				<table>
				<tr>
					<td width="100" align="right" class="key">
					Name:
					</td><td>
					<input class="text_area" type="text" name="cat_name" id="cat_name" size="50" maxlength="100"
					value="<?php echo $row->cat_name; ?>" /></td>
				</tr><tr>
					<td width="100" align="right" class="key">
					Category Description:
					</td><td>
					<textarea class="text_area" cols="20" rows="4" name="cat_desc" id="cat_desc" style="width:500px; text-align:left"><?php echo $row->cat_desc; ?></textarea></td>
				</tr>
		<tr>
					<td width="100" align="right" class="key">
					Category Image Source:
					</td><td>
					<input class="text_area" type="text" name="cat_img" id="cat_img" size="50" maxlength="100" 
					value="<?php echo $row->cat_img; ?>" /> E.g. images/category1.png. Will be scaled down to 100 x 100px</td>
			</tr>
			<tr>
					<td width="100" align="right" class="key">
					Order:
					</td><td>
					<input class="text_area" type="text" name="ordering" id="ordering" size="50" maxlength="100" 
					value="<?php echo $row->ordering; ?>" /> Categories ordered by order number then by name</td>
			</tr>
		
				</table><br><br>
			</fieldset>
			<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="published" value="1" />
		</form>
<?php 
	}		
}
?>