<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted Access');
?>

<script type="text/javascript" defer="defer">

    // <![CDATA[
    function setZTab(newTab){
            tab = newTab;
            zcSetTab(newTab);
            if(!$('postsContainer'))
                window.location.href='/#';
            else{
                console.log('setTab antes de initVista');
                initVista(zcGetContext());
            }
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
    });
    // ]]>
</script>

<!-- glassmenu -->
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
    <!--<ul id="s50_m">
        <li><a href="/index.php?option=com_zonales&task=zonal&view=geoActivos">Zonales Activos</a></li>
        <li><a href="">Contenido Relevante</a></li>
        <li><a href="">Contenido Nuevo</a></li>
        <li><a href="">Contenido Cercano</a></li>
    </ul>-->

</div>
<!-- glassmenu -->
