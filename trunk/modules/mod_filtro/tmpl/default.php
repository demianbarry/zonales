<?php
/**
 * @version	$Id$
 * @package	Zonales
 * @copyright	Copyright (C) 2009 Mediabit. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 *
 * Zonales is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted Access');
JHTML::_('behavior.formvalidation');
?>
<!-- form -->
<div class="moduletable_formEq">
    <h1 id="title_eq"><?php echo $use_module_title ? $module->title : $title; ?></h1>
    <div id="mod_eq_main_div" class="moduletable_formEq_bodyDiv">
        <p><?php echo $description; ?></p>
        <div class="splitter"></div>

        <div>
            <table>
                <tbody id="chkFilter">
                    <?php if($pestaña == 'relevantes'):?>
                    <tr>
                        <td>
                            <select id="tempoSelect" class="tempoclass" onchange="$('postsContainer').empty(); $('newPostsContainer').empty(); lastIndexTime = null; loadPost(true);">
                                <option value="24HOURS">Hoy</option>
                                <option value="7DAYS">Ultima Semana</option>
                                <option value="30DAYS">Ultimo Mes</option>
                                <option value="0">Historico</option>
                            </select>
                        </td>
                    </tr>
                    <?php endif;?>
                    <?php if($pestaña == 'enlared'):?>
                        <tr>
                            <td>
                                <input type="checkbox" id="chkFacebook" checked="true" value="Facebook" onclick="filtrar(this.value, this.checked);">
                            </td>
                            <td>
                                Facebook
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" id="chkTwitter" checked="true" value="Twitter" onclick="filtrar(this.value, this.checked);">
                            </td>
                            <td>
                                Twitter
                            </td>
                        </tr>   
                    <?php endif;?>
                   
                    <?php if($pestaña == 'noticiasenlared'):?>
                    
                    <?php endif;?>
                    
                    <?php if($pestaña == 'noticiasenlaredrelevantes'):?>
                    <tr>
                        <td>
                            <select id="tempoSelect" class="tempoclass" onchange="$('postsContainer').empty(); $('newPostsContainer').empty(); lastIndexTime = null; loadPost(true);">
                                <option value="24HOURS">Hoy</option>
                                <option value="7DAYS">Ultima Semana</option>
                                <option value="30DAYS">Ultimo Mes</option>
                                <option value="0">Historico</option>
                            </select>
                        </td>
                    </tr>
                    <?php endif;?>

                </tbody>
            </table>
        </div>

    </div>

</div><!-- end #moduletable_formVecinos -->
<!-- form -->