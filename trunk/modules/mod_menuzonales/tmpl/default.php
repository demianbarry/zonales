<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted Access');
?>

<script type="text/javascript" defer="defer">
    // <![CDATA[
    function setTab(newTab){
        tab = newTab;        
        zcSetTab(newTab);
        initVista(zcGetContext());
    }
    
    window.addEvent('domready', function(){
            
        // show by default the first submenu
        $('s0_m').setStyle('display', 'block');

        $$('#mymenu li').each(function(el){
            el.getElement('a').addEvent('mouseover', function(subLinkId){
                var layer = subLinkId+"_m";
                $('sublinks').getElements('ul').setStyle('display', 'none');
                if($(layer)){
                    if($(layer).getChildren('li').length > 0){
                        $(layer).getParent().getParent().setStyle('height','42px');
                    } else {
                        $(layer).getParent().getParent().setStyle('height','18px');
                    }
                    $(layer).setStyle('display', 'block');
                }
            }.pass(el.id));
        });
        
        $('menuPortada').addEvent('click', function(){
            setTab('portada');            
        });
        $('menuEnLaRed').addEvent('click', function(){
            setTab('enlared');
        });
        $('menuNoticias').addEvent('click', function(){
            setTab('noticiasenlared');
        });
        $('submenuEnLaRedNuevos').addEvent('click', function(){
            setTab('enlared');
        });
        $('submenuEnLaRedRelevantes').addEvent('click', function(){
            setTab('relevantes');
        });
        $('submenuNoticiasNuevas').addEvent('click', function(){
            setTab('noticiasenlared');
        });
        $('submenuNoticiasRelevantes').addEvent('click', function(){
            setTab('noticiasenlaredrelevantes');
        });
    });
    // ]]>
</script>

<!-- glassmenu -->
<div id="navigation">
    <ul id="mymenu">
        <li id="s0"><a id="menuPortada" href="#"><?php echo JText::_('Portada'); ?></a></li>
        <li id="s30"><a id="menuEnLaRed" href="#"><?php echo JText::_('En la Red'); ?></a></li>
        <li id="s45"><a id="menuNoticias" href="#"><?php echo JText::_('Noticias'); ?></a></li>
        <li id="s50"><a id="menuMapa" href="/index.php?option=com_zonales&task=zonal&view=geoActivos"><?php echo JText::_('Mapa'); ?></a></li>
    </ul>
</div>
<div id="sublinks">
    <ul id="s0_m"></ul>
    <ul id="s30_m">
        <li><a id="submenuEnLaRedNuevos" href="#">Posts nuevos</a></li>
        <li><a id="submenuEnLaRedRelevantes" href="#">Post relevantes</a></li>
    </ul>

    <ul id="s45_m">
        <li><a id="submenuNoticiasNuevas" href="#">Noticias nuevas</a></li>
        <li><a id="submenuNoticiasRelevantes" href="#">Noticias relevantes</a></li>
    </ul>
    <!--<ul id="s50_m">
        <li><a href="/index.php?option=com_zonales&task=zonal&view=geoActivos">Zonales Activos</a></li>
        <li><a href="">Contenido Relevante</a></li>
        <li><a href="">Contenido Nuevo</a></li>
        <li><a href="">Contenido Cercano</a></li>
    </ul>-->

</div>
<!-- glassmenu -->
