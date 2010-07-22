<?php
/**
 * Default layout for design view
 *
 * @version		$Id: default.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
 * @package		Joomla
 * @subpackage	Workflows
 * @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
 * @license		GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
$cid = $this->cid?$this->cid[0]:0;
$ce = $this->ce;
?>


<div id='container'>
    <table>
        <tr>
            <td>
                <div style="display: inline; float: none">
                    <div id='side-bar' style="float: none">
                        <div id='btn-general'    class='tab-button active-tab-button' onclick='javascript:enableTab("general");'><?php echo JText::_('General') ?></div>
                        <div id='btn-acl'        class='tab-button' onclick='javascript:enableTab("acl")' ><?php echo JText::_('Groups') ?></div>
                        <div id='btn-properties' class='tab-button' onclick='javascript:enableTab("properties")'><?php echo JText::_('Station...')?></div>
                        <br clear='all' />

                        <div class='tab-body' style='display:block' id='tab-general'>
                            <form action="index.php" method="post" name="adminForm" id='adminForm'>
                                <?php echo $this->generalForm ?>
                                <input type="hidden" name="params[id]" value="<?php echo $this->workflow?$this->workflow->id:'' ?>"  />
                                <input type="hidden" name="params[workflowData]" value="" id="workflowData" />
                                <input type="hidden" name="option" value="com_jwf" />
                                <input type="hidden" name="task" value="" />
                                <?php echo JHTML::_( 'form.token' ); ?>
                            </form>
                        </div>

                        <div class='tab-body' id='tab-acl'>
                            <?php echo $this->groupsForm ?>
                        </div>

                        <div class='tab-body' id='tab-properties'>
                            <?php echo $this->propertiesForm ?>
                        </div>

                    </div>
                    <?php if($cid > 0) :?>
                    <div id="addTagsDiv<?php echo $cid?>" style="display: block; background: none repeat scroll 0% 0% #F8F8F1; margin: 5px; padding: 10px; border: 1px outset gray; margin-top: 20px;">
                        <div id="tagsList" style="margin-bottom: 20px;">
                            <?php echo JText::_('Assigned tags:') ?>
                            <?php foreach($this->tags as $key => $tag): ?>
                            <span id="<?php echo $tag->vid."_".$tag->fid."_".$ce->table."_".$cid ?>" class="cp_tag cp_tag_<?php echo $tag->vid ?>" <?php if($key!=0):?>style="border-left : 1px solid silver; margin-left: 5px; padding-left: 5px;"<?php endif;?>>
                                    <?php echo $tag->label?>
                                <img id="tag_img_<?php echo $tag->vid ?>" onclick="<?php echo 'deleteTag('.$tag->vid.','.$tag->fid.',\''.$ce->table.'\','.$cid.')'; ?>" style="cursor: pointer; vertical-align: middle;" src="components/com_customproperties/images/eliminar.gif" alt="eliminar">
                            </span>
                            <?php endforeach; ?>
                        </div>
                        <div id="tagsSearchHeader<?php echo $cid?>">
                            <?php echo JText::_('Search tags:') ?>
                            <input id="tagsSearchField<?php echo $cid?>" onkeyup="searchTags(event, <?php echo $cid ?> )">
                        </div>
                        <form id="addTagsForm<?php echo $cid?>" method="post" action="index.php?option=com_customproperties&task=addTags" name="addTagsForm">
                            <input type="hidden" name="ce_name" value="<?php echo $ce->name?>" />
                            <input type="hidden" name="cid" value="<?php echo $cid?>" />
                            <div id="tagsSearchResults<?php echo $cid?>" style="padding: 10px;"></div>
                            <div id="selectedTags<?php echo $cid?>" style="padding: 10px; display: none;">
                                <span class="article_separator"/>
                                <b><u>Seleccionados</u></b>
                            </div>
                            <input type="button" id="addTagsButton<?php echo $cid?>" onclick="addTags(event,<?php echo $cid?>)" value="<?php echo JText::_('Add tags')?>" style="border:1px solid #CCCCCC; color:#666666; font-family: trebuchet MS; font-size: 11px; margin: 0; padding: 0;"/>
                        </form>
                    </div>
                    <?php endif;?>
                </div>
            </td>
            <td>
                <div id='workarea' class='workarea'>
                    <ul id='stations'></ul>
                </div>
            </td>
        </tr>
    </table>

