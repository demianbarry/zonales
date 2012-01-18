<script language="javascript" type="text/javascript" src="components/com_zonales/vistas.js"></script>


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
<div class="moduletable_formEq" style="width:250px;height:200px;overflow:scroll;" >    <!--//en este div.-->
    <h1 id="title_eq"><?php echo "Filtros "; ?></h1>
    <div id="mod_eq_main_div" class="moduletable_formEq_bodyDiv">
        <p><?php echo "Filtro usado para filtar
las fuentes de informacion mostradas"; ?></p>
        
        <div class="splitter"></div>

        <?php
            if ($pestana == 'enlared' || $pestana == 'relevantes') {
                $enLaRed = "inline";
                $noticiasEnLaRed = "none";
                if ($pestana == 'enlared') {
                    $temporalidad = "none";
                } else {
                    $temporalidad = "inline";
                }
            }

            if ($pestana == 'noticiasenlared' || $pestana == 'noticiasenlaredrelevantes') {
                $enLaRed = "none";
                $noticiasEnLaRed = "inline";
                if ($pestana == 'noticiasenlared') {
                    $temporalidad = "none";
                } else {
                    $temporalidad = "inline";
                }
            }
            if ($pestana == 'zonales') {
                $enLaRed = "none";
                $noticiasEnLaRed = "none";
                $temporalidad = "none";
            }
        ?>
            <div id="filtersDiv">
                <p>FUENTES</p>
                <table id="enLaRed" style="display: <?php echo $enLaRed ?>">

            </table>
            <table id="noticiasEnLaRed" style="display: <?php echo $noticiasEnLaRed ?>">

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
        <div id="filterTags">
            <p>TAGS</p>
            <table id="tagsFilterTable">

            </table>
        </div>

    </div>

</div>
<!-- end #moduletable_formVecinos -->
<!-- form -->
