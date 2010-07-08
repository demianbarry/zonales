<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

$cid = $this->cid;

?>


<div id="addTagsDiv<?php echo $cid?>" style="display: block; background: none repeat scroll 0% 0% #F8F8F1; position: relative; margin: 5px; padding: 10px; border: 1px outset grey;">
      <div id="tagsSearchHeader<?php echo $cid?>">
        <?php echo JText::_('Search tags:') ?>
        <input id="tagsSearchField<?php echo $cid?>" onkeyup="searchTags(event, <?php echo $cid ?> )">
                 <a style="float: right; cursor: pointer; padding-top: 5px; padding-left: 20px;" onclick="showAddTagsDiv(<?php echo $cid?>)">X</a>
        </div>
    <form id="addTagsForm<?php echo $cid?>" method="post" action="index.php?option=com_customproperties&task=addTags" name="adminForm">
        <input type="hidden" name="ce_name" value="content" />
        <input type="hidden" name="cid" value="<?php echo $cid?>" />
        <div id="tagsSearchResults<?php echo $cid?>" style="padding: 10px;"></div>
        <div id="selectedTags<?php echo $cid?>" style="padding: 10px; display: none;">
             <span class="article_separator"/>
            <b><u>Seleccionados</u></b>
        </div>
        <input type="button" id="addTagsButton<?php echo $cid?>" onclick="addTags(event,<?php echo $cid?>)" value="<?php echo JText::_('Add tags')?>" style="float: left; border:1px solid #CCCCCC; color:#666666; font-family: trebuchet MS; font-size: 11px; margin: 0; padding: 0;"/>
    </form>
</div>