<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted Access');
?>

<script type="text/javascript" defer="defer">
    // <![CDATA[
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
		<li id="s0"><a href="/index.php?option=com_zonales&task=zonal&view=portada"><?php echo JText::_('Portada'); ?></a></li>
                <?php if (isset ($eq->fields)): ?>
		<?php foreach ($eq->fields as $field): ?>
		<li id="s<?php echo $field->id; ?>"><a href="<?php echo($field->link ? $field->link : '#'); ?>"><?php echo $field->label; ?></a></li>
		<?php endforeach; ?>
                <?php endif;?>
                <li id="s30"><a href="/index.php?option=com_zonales&task=zonal&view=enlared"><?php echo JText::_('En la Red'); ?></a></li>
                <li id="s45"><a href="/index.php?option=com_zonales&task=zonal&view=noticiasenlared"><?php echo JText::_('Noticias en la Red'); ?></a></li>
                <li id="s50"><a href="/index.php?option=com_zonales&task=zonal&view=geoActivos"><?php echo JText::_('Mapa'); ?></a></li>
	</ul>
</div>
<div id="sublinks">
    <ul id="s0_m"></ul>
    <?php if (isset($eq->fields)): ?>
        <?php foreach ($eq->fields as $field): ?>
            <ul id="s<?php echo $field->id; ?>_m">
                <?php foreach ($field->bands as $band): ?>
                    <li><a href="<?php echo $band->link; ?>"><?php echo $band->band_label; ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    <?php endif; ?>
    <ul id="s30_m">
        <li><a href="/index.php?option=com_zonales&task=zonal&view=enlared">Nuevos</a></li>
        <li><a href="/index.php?option=com_zonales&task=zonal&view=relevantes">Relevantes</a></li>
    </ul>

        <ul id="s45_m">
            <li><a href="/index.php?option=com_zonales&task=zonal&view=noticiasenlared">Nuevos</a></li>
            <li><a href="/index.php?option=com_zonales&task=zonal&view=noticiasenlaredrelevantes">Relevantes</a></li>
        </ul>
        <!--<ul id="s50_m">
            <li><a href="/index.php?option=com_zonales&task=zonal&view=geoActivos">Zonales Activos</a></li>
            <li><a href="">Contenido Relevante</a></li>
            <li><a href="">Contenido Nuevo</a></li>
            <li><a href="">Contenido Cercano</a></li>
        </ul>-->
        
</div>
<!-- glassmenu -->
