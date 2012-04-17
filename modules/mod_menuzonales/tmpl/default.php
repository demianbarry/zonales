<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted Access');
?>

<script type="text/javascript" defer="defer">

    // <![CDATA[
    function setZTab(newTab){
            tab = newTab;
            zTab.zCtx.zcSetTab(newTab);
            setActiveTab(newTab);            
    }

    function setActiveTab(newTab) {        
        if (newTab == 'portada' || newTab == 'portadarelevantes') {
            $('portada_anchor').addClass('active');
            $('enlared_anchor').removeClass('active');
            $('noticias_anchor').removeClass('active');
        }

        if (newTab == 'enlared' || newTab == 'relevantes') {
            $('portada_anchor').removeClass('active');
            $('enlared_anchor').addClass('active');
            $('noticias_anchor').removeClass('active');
        }

        if (newTab == 'noticiasenlared' || newTab == 'noticiasenlaredrelevantes') {
            $('portada_anchor').removeClass('active');
            $('enlared_anchor').removeClass('active');
            $('noticias_anchor').addClass('active');
        }
        
        if (newTab.indexOf('relevantes') != -1) {
            $('relevantesOrder').addClass('active');
            $('recientesOrder').removeClass('active');
        } else {
            $('relevantesOrder').removeClass('active');
            $('recientesOrder').addClass('active');
        }        
    }    
</script>

<div>
    <div id="mainMenu"><p><a id="portada_anchor" href="#" onclick="setZTab(zTab.zCtx.zcGetZTab().indexOf('relevantes') != -1 ? 'portadarelevantes' : 'portada');">Portada</a> | <a id="enlared_anchor" href="#" onclick="setZTab(zTab.zCtx.zcGetZTab().indexOf('relevantes') != -1 ? 'relevantes' : 'enlared');" class="active">En la red</a> | <a id="noticias_anchor" href="#" onclick="setZTab(zTab.zCtx.zcGetZTab().indexOf('relevantes') != -1 ? 'noticiasenlaredrelevantes' : 'noticiasenlared');">Noticas</a></p></div>
    <div id="ordenar"><p>Ordenar por <img src="templates/<?php echo $template; ?>/img/arrow_right.gif" /> <a id="relevantesOrder" onclick="console.log(zTab.zCtx.zcGetZTab());if(zTab.zCtx.zcGetZTab().indexOf('relevantes') == -1)setZTab(zTab.zCtx.zcGetZTab() == 'noticiasenlared' ? 'noticiasenlaredrelevantes' : zTab.zCtx.zcGetZTab() == 'portada' ? 'portadarelevantes' : 'relevantes')">MÃ¡s relevantes</a> | <a id="recientesOrder" onclick="if(zTab.zCtx.zcGetZTab().indexOf('relevantes') != -1)setZTab(zTab.zCtx.zcGetZTab() == 'noticiasenlaredrelevantes' ? 'noticiasenlared' : zTab.zCtx.zcGetZTab() == 'portadarelevantes' ? 'portada' : 'enlared')">MÃ¡s recientes</a></p></div>
</div>


<!-- glassmenu
<div id="navigation">
    <ul id="mymenu">
        <li id="s0"><a id="menuPortada" onclick="setZTab('portada');" href="#"><?php echo JText::_('Portada'); ?></a></li>
        <li id="s30"><a id="menuEnLaRed" onclick="setZTab('enlared');" href="#"><?php echo JText::_('En la Red'); ?></a></li>
        <li id="s45"><a id="menuNoticias" onclick="setZTab('noticiasenlared');" href="#"><?php echo JText::_('Noticias'); ?></a></li>
    </ul>
</div>
<div id="sublinks">
    <ul id="s0_m"></ul>
    <ul id="s30_m">
        <li><a id="submenuEnLaRedNuevos" onclick="setZTab('enlared');" href="#">Posts nuevos</a></li>
        <li><a id="submenuEnLaRedRelevantes" onclick="setZTab('relevantes');" href="#">Post relevantes</a></li>
    </ul>

    <ul id="s45_m">
        <li><a id="submenuNoticiasNuevas" onclick="setZTab('noticiasenlared');" href="#">Noticias nuevas</a></li>
        <li><a id="submenuNoticiasRelevantes" onclick="setZTab('noticiasenlaredrelevantes');" href="#">Noticias relevantes</a></li>
    </ul>

</div>
-->
