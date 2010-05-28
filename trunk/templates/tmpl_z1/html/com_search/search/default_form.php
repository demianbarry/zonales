<?php defined('_JEXEC') or die('Restricted access'); ?>

<form id="searchForm" action="<?php echo JRoute::_( 'index.php?option=com_search' );?>" method="post" name="searchForm">
	<table class="contentpaneopen<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		<tr>
			<td nowrap="nowrap">
				<label for="search_searchword">
					<?php echo JText::_( 'Search Keyword' ); ?>:
				</label>
			</td>
			<td nowrap="nowrap">
				<input type="text" name="searchword" id="search_searchword" size="30" maxlength="20" value="<?php echo $this->escape($this->searchword); ?>" class="inputbox" />
			</td>
			<td width="100%" nowrap="nowrap">
				<button name="Search" onclick="this.form.submit()" class="button"><?php echo JText::_( 'Search' );?></button>
			</td>
		</tr>
	</table>

<br/>
<br/>
<input type="hidden" name="task"   value="search" />
</form>