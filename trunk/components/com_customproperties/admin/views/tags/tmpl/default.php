<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

$cid = $this->cid;
$ce = $this->ce;
$gid = $this->gid;
?>
<div id="addTagsDiv<?php echo $cid?>" style="display: block; background: none repeat scroll 0% 0% #F8F8F1; position: relative; margin: 5px; padding: 10px; border: 1px outset gray;">
    <div id="tagsList" style="margin-bottom: 20px;">
        <?php echo JText::_('Assigned tags:') ?>
        <?php foreach($this->tags as $key => $tag): ?>
        <span id="<?php echo $tag->vid."_".$tag->fid."_".$ce->table."_".$cid ?>" class="cp_tag cp_tag_<?php echo $tag->vid ?>" <?php if($key!=0):?>style="border-left : 1px solid silver; margin-left: 5px; padding-left: 5px;"<?php endif;?>>
                <?php echo $tag->label?>
                <?php if($gid >= $tag->ag):?> <!-- Si el grupo del usuario es mayor o igual al grupo de acceso del tag...-->
                <img id="tag_img_<?php echo $tag->vid ?>"
                 onclick="<?php echo 'deleteTag('.$tag->vid.','.$tag->fid.',\''.$ce->table.'\','.$cid.')'; ?>"
                 style="cursor: pointer; vertical-align: middle;"
                 src="<?php echo !$this->app->isAdmin()? 'administrator/':''?>components/com_customproperties/images/eliminar.gif"
                 alt="eliminar">
                <?php endif;?>
        </span>
        <?php endforeach; ?>
    </div>
    <div id="tagsSearchHeader<?php echo $cid?>">
        <?php echo JText::_('Search tags:') ?>
        <input id="tagsSearchField<?php echo $cid?>" onkeyup="searchTags(event, <?php echo $cid ?> )">
    </div>
    <form id="addTagsForm<?php echo $cid?>" method="post" action="index.php?option=com_customproperties&task=addTags" name="adminForm">
        <input type="hidden" name="ce_name" value="content" />
        <input type="hidden" name="cid" value="<?php echo $cid?>" />
        <div id="tagsSearchResults<?php echo $cid?>" style="padding: 10px;"></div>
        <div id="selectedTags<?php echo $cid?>" style="padding: 10px; display: none;">
            <span class="article_separator"/>
            <b><u>Seleccionados</u></b>
        </div>
        <input type="button" id="addTagsButton<?php echo $cid?>" onclick="addTags(event,<?php echo $cid?>)" value="<?php echo JText::_('Add tags')?>" style="border:1px solid #CCCCCC; color:#666666; font-family: trebuchet MS; font-size: 11px; margin: 0; padding: 0;"/>
    </form>
</div>
