<script language="javascript" type="text/javascript">
    <!--

    window.addEvent('unload', function() {
        var url = "/index.php?option=com_zonales&task=setFilters";

        $('filtersDiv').getElements('input[id^=chk]').each(function(element) {
            url += "&filters[" + element.id.substring(3) + "]=" + element.checked;
        });

        new Ajax(url, {
            method: 'get'
            //update: 'alias_progress',
        }).request();
    });

    //-->
</script>

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

        <?php
          if($pestaña == 'enlared' || $pestaña == 'relevantes') {
              $enLaRed = "inline";
              $noticiasEnLaRed = "none";
              if($pestaña == 'enlared') {
                  $temporalidad = "none";
              } else {
                  $temporalidad = "inline";
              }
          }

          if($pestaña == 'noticiasenlared' || $pestaña == 'noticiasenlaredrelevantes') {
              $enLaRed = "none";
              $noticiasEnLaRed = "inline";
              if($pestaña == 'noticiasenlared') {
                  $temporalidad = "none";
              } else {
                  $temporalidad = "inline";
              }
          }
         ?>
        <div id="filtersDiv">
            <table id="enLaRed" style="display: <?php echo $enLaRed ?>">
                <?php
                    $session = JFactory::getSession();
                    $filters = $session->get('filters');
                    if (isset($filters)) {
                        foreach ($filters as $key => $value) {
                            if ($key == 'Facebook' || $key == 'Twitter') {
                                echo '<tr><td>';
                                echo '<input id="chk' . $key . '" type="checkbox" checked=' . $value . ' value="' . $key . '" onclick: "filtrar(this.value, this.checked);"';
                                echo '</td><td>' . $key . '</td>';
                                echo '</td></tr>';
                            }
                        }
                    }
                ?>
            </table>
            <table id="noticiasEnLaRed" style="display: <?php echo $noticiasEnLaRed ?>">
                <?php
                    $session = JFactory::getSession();
                    $filters = $session->get('filters');
                    if (isset($filters)) {
                        foreach ($filters as $key => $value) {
                            if ($key != 'Facebook' && $key != 'Twitter') {
                                echo '<tr><td>';
                                echo '<input id="chk' . $key . '" type="checkbox" checked=' . $value . ' value="' . $key . '" onclick: "filtrar(this.value, this.checked);"';
                                echo '</td><td>' . $key . '</td>';
                                echo '</td></tr>';
                            }
                        }
                    }
                ?>
            </table>
            <table id="temporalidad" style="display: <?php echo $temporalidad ?>">
                    <tr>
                        <td>
                            <select id="tempoSelect" class="tempoclass">
                                <option value="24HOURS">Hoy</option>
                                <option value="7DAYS">Ultima Semana</option>
                                <option value="30DAYS">Ultimo Mes</option>
                                <option value="0">Historico</option>
                            </select>
                        </td>
                    </tr>
            </table>
        </div>

    </div>

</div>
<!-- end #moduletable_formVecinos -->
<!-- form -->
