<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<script language="javascript" type="text/javascript">
    <!--

    window.addEvent('domready', function() {
        /*var mySoyCorresponsalSlider = new Fx.Slide('mod_zcloud_main_div', {fps: 20});
        // lo oculto por defecto
        mySoyCorresponsalSlider.hide();

        // clickeando en el título muestro u oculto el formulario
        $('title_zcloud').addEvent('click', function(e) {
            mySoyCorresponsalSlider.toggle();
        });*/
        $('mod_zcloud_main_div').setStyle('display','none');

        // clickeando en el título muestro u oculto el formulario
        $('title_zcloud').addEvent('click', function(e) {
            $('mod_zcloud_main_div').setStyle('display',$('mod_zcloud_main_div').getStyle('display') == 'none' ? 'block':'none')
        });
    });
</script>

<div class="moduletable_formZcloud" style="margin-bottom: 10px;">
    <h1 id="title_zcloud"><?php echo $module->title?></h1>
    <div id="mod_zcloud_main_div" class="jcloud_tags" style="text-align: center; background-color: #F8F8F1; padding: 10px;">
        <?php foreach( $relevances as $tag => $count ):
            $size = ( ( $maxsize * 0.65 ) * ( $count - $min ) / $max ) + ( $maxsize * 0.35 );
            $tagLabel = $labels[$tag];
            ?>
        <span class="zcloud_tag">
                <?php modCloudHelper::showHtmlTag($tag,$tagLabel,$size) ?>
        </span>
        <span style="font-size:1px;">&nbsp;</span>
        <?php endforeach; ?>
    </div>
</div>
