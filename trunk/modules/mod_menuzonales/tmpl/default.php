<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted Access');
?>

<script type="text/javascript" defer="defer">

    // <![CDATA[
    function setZTab(newTab){
            tab = newTab;
            zcSetTab(newTab);
            setActiveTab(newTab);
            if(!$('postsContainer'))
                window.location.href='/#';
            else{
                console.log('setTab antes de initVista');
                initVista(zcGetContext());
            }
    }

    function setActiveTab(newTab) {
        if (newTab == 'portada') {
            $('portada_anchor').addClass('active');
            $('enlared_anchor').removeClass('active');
            $('noticias_anchor').removeClass('active');
        }

        if (newTab == 'enlared') {
            $('portada_anchor').removeClass('active');
            $('enlared_anchor').addClass('active');
            $('noticias_anchor').removeClass('active');
        }

        if (newTab == 'noticiasenlared') {
            $('portada_anchor').removeClass('active');
            $('enlared_anchor').removeClass('active');
            $('noticias_anchor').addClass('active');
        }

    }
</script>

<div>
    <div id="mainMenu"><p><a id="portada_anchor" href="#" onclick="setZTab('portada');">Portada</a> | <a id="enlared_anchor" href="#" onclick="setZTab('enlared');" class="active">En la red</a> | <a id="noticias_anchor" href="#" onclick="setZTab('noticiasenlared');">Noticas</a></p></div>
    <div id="ordenar"><p>Ordenar por <img src="templates/<?php echo $template; ?>/img/arrow_right.gif" /> <a href="#">Más relevantes</a> | <a href="#">Más recientes</a></p></div>
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
