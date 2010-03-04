<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<script type="text/javascript" defer="defer">
    // <![CDATA[
    window.addEvent('domready', function() {
        Shadowbox.init({
            skipSetup: true,
            text: {
                cancel:     'Cancelar',
                loading:    'Cargando...',
                close:      '<span class="shortcut">C</span>errar',
                next:       '<span class="shortcut">S</span>iguiente',
                prev:       '<span class="shortcut">A</span>nterior',
                errors:     {
                    single: 'Usted debe instalar el plugin <a href="{0}">{1}</a> para poder ver el contenido.',
                    shared: 'Usted debe instalar <a href="{0}">{1}</a> y <a href="{2}">{3}</a> para poder visualizar el contenido.',
                    either: 'Usted debe instalar <a href="{0}">{1}</a> o <a href="{2}">{3}</a> para poder visualizar el contenido.'
                }
            }
        });

        Shadowbox.setup($('zonal'), {
            onClose:function() { window.location.reload(true); }
        });
    });

    // ]]>
</script>

<div id="moduletable_zMap">
    <?php if($showLabel): ?>
    <p>
            <?php echo $labelText; ?>
    </p>
    <?php endif; ?>
    <p>
        <a id="zonal" rel="lightbox;width=<?php echo $width; ?>;height=<?php echo $height; ?>" title="<?php echo $titleText; ?>" href="index.php?option=com_zonales&view=mapa&ajax=true&tmpl=component_only">
            <img src="templates/<?php echo $template; ?>/images/<?php echo $mainColor; ?>/bot_zonales.gif" />
        </a>
    </p>
</div>