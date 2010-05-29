<?php
defined('_JEXEC') or die('Restricted access');

if (isset ($data['nessage'])){
    echo $data['nessage'];
    die();
}

$eqId = $data['eqId'];
$jsCode = $data['jsCode'];
$divs = $data['divs'];
$_cplist = $data['_cplist'];

// incluye javascript para objeto flash
JHTML::script('core/lang/Bs_Misc.lib.js');
JHTML::script('core/lang/Bs_Array.class.js');
JHTML::script('checkbox/Bs_Checkbox.class.js');
JHTML::script('tree/Bs_Tree.class.js');
JHTML::script('tree/Bs_TreeElement.class.js');

?>
<script type="text/javascript">
    /*
     * array which generates tree
     */
<?php
echo $jsCode;
?>

    window.addEvent('domready', function() {
        $('adminForm').addEvent('submit', function(e) {
            new Event(e).stop();
            for (i=0;i < $('adminForm').elements.length;i++){
                var key = $('adminForm').elements[i].name;
                var beg = key.substr(0,3);
                // si el elemento corresponde a un tag seleccionado
                if (beg == 'cp_' && $('adminForm').elements[i] == 2){
                    // obtengo los datos (el value)
                    var fieldValue = key.substr(3);
                    var separatorIndex = fieldValue.indexOf('_');
                    //var field = fieldValue.substr(0,separatorIndex);
                    var value = fieldValue.substr(separatorIndex + 1);

                    // creo los elementos slider
                    // eso espera la funcion en el servidor
                    var slider = document.createElement('input');
                    slider.setAttribute("type", "hidden");
                    // el id del elemento es IDBanda-IDValue-Peso
                    // no se especifica IDBanda porque hay que agregar la banda
                    slider.setAttribute("id","-" + value + "-50");
                    slider.setAttribute("name", "slider[]");
                    $('adminForm').appendChild(slider);
                }
            }
            this.send({
                onSuccess: function(response) {
                    var resp = Json.evaluate(response);
                    if (resp.status == 'SUCCESS') {

                    }
                    $('respuesta').setHTML(resp.msg);
                }
            });
        });
    }
</script>
<!-- genero id-value_id-peso llamados slider-->
<div class="cp_add_tag">
    <form method="post" action="index.php" name="adminForm" id="adminForm">
        <div class="cp_navbar">
            <input type="hidden" name="option" value="com_eqzonales"/>
            <input type="hidden" name="task" value="band.modifyBandAjax"/>
            <input type="hidden" name="eqid" value="<?php echo $eqId ?>" />
            <input type="hidden" name="format" value="raw" />
            <input type="button" class="button" value="<?php echo JText::_('SYSTEM_EQ_BAND_ADD'); ?>" onclick="this.form.submit();"/>
            <input type="button" class="button" value="<?php echo JText::_('SYSTEM_WINDOW_CLOSE'); ?>" onclick="window.parent.document.getElementById('sbox-window').close();"/>
        </div>
        <?php
        echo $divs;
        ?>

    </form>
</div>