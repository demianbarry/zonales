<?php
defined('_JEXEC') or die('Restricted access');

if (isset ($data['message'])){
    echo $data['message'];
    die();
}

$eqId = $data['eqId'];
$jsCode = $data['jsCode'];
$divs = $data['divs'];
$_cplist = $data['_cplist'];

// incluye javascript para objeto flash
JHTML::script('Bs_Misc.lib.js',JRoute::_('media/system/js/core/lang/'),false);
JHTML::script('Bs_Array.class.js',JRoute::_('media/system/js/core/lang/'),false);
JHTML::script('Bs_Checkbox.class.js',JRoute::_('media/system/js/checkbox/'),false);
JHTML::script('Bs_Tree.class.js',JRoute::_('media/system/js/tree/'),false);
JHTML::script('Bs_TreeElement.class.js',JRoute::_('media/system/js/tree/'),false);

?>
<script type="text/javascript">
    /*
     * array which generates tree
     */
<?php
echo $jsCode;
?>
    </script>
<script type="text/javascript">
function indexOf(array, element){
    var i;
    for (i = 0; i < array.length;i++){
        if (array[i].valueId == element){
            return i;
        }
    }
    return -1;
}

function prepareData(operation,currentBands,form,key){
    // obtengo los datos (el value)
                            var fieldValue = key.substr(3);
                            var separatorIndex = fieldValue.indexOf('_');
                            //var field = fieldValue.substr(0,separatorIndex);
                            var value = fieldValue.substr(separatorIndex + 1);
                            var bandIndex = indexOf(currentBands, value);

                            // si era una banda existente no la agrego nada
                            if (bandIndex != -1 && operation == 'ADD') return;
                            // si se quiere eliminar una banda que no estaba, no se hace nada
                            if (bandIndex == -1 && operation == 'REM') return;

                            var bandId = (operation == 'REM') ? currentBands[bandIndex].id : '';

                            // creo los elementos slider
                            // eso espera la funcion en el servidor
                            var slider = document.createElement('input');
                            slider.setAttribute("type", "hidden");
                            // el id del elemento es IDBanda-IDValue-Peso
                            // no se especifica IDBanda porque hay que agregar la banda
                            slider.setAttribute("id","-" + value + "-50");
                            slider.setAttribute("name", "slider[]");
                            slider.setAttribute("value", bandId + "-" + value + "-50-" + operation);
                            form.appendChild(slider);
}

    function submitForm() {
        var currentBands = new Array();
        var i;
        var form = $('adminForm');
        var band;

        <?php
        foreach ($_cplist as $selectedBand) {
            $bandId = $selectedBand->id;
            $valueId = $selectedBand->cp_value_id;
            echo "band = new Object();";
            echo "band.id='$bandId';";
            echo "band.valueId='$valueId';";
            echo "currentBands.push(band);";
        }
        ?>

                    for (i=0;i < form.elements.length;i++){
                        var key = form.elements[i].name;
                        var beg = key.substr(0,3);


                        // si el elemento corresponde a un tag seleccionado
                        if (beg == 'cp_' && form.elements[i].value == 2){
                            prepareData('ADD', currentBands, form, key);
                        }
                        else {
                            if (beg == 'cp_' && form.elements[i].value == 0){
                                prepareData('REM', currentBands, form, key);
                            }
                        }
                    }
                    var url = 'index.php';
                    new Ajax(url, {
                method: 'post',
                data: form
            }).request();


                };

//window.addEvent('domready', function() {
//    $('btnadd').addEvent('click', clickme);
//            });
</script>
<!-- genero id-value_id-peso llamados slider-->
<div class="cp_add_tag">
    <form method="post" action="index.php" name="adminForm" id="adminForm">
        <div class="cp_navbar">
            <input type="hidden" name="option" value="com_eqzonales"/>
            <input type="hidden" name="task" value="band.modifyBandAjax"/>
            <input type="hidden" name="eqid" value="<?php echo $eqId ?>" />
            <input type="hidden" name="format" value="raw" />
            <input type="button" class="button" value="<?php echo JText::_('SYSTEM_EQ_BAND_ADD'); ?>" id="btnadd" onclick="submitForm();"/>
            <input type="button" class="button" value="<?php echo JText::_('SYSTEM_WINDOW_CLOSE'); ?>" onclick="window.parent.document.getElementById('sbox-window').close();"/>
        </div>
        <?php
        echo $divs;
        ?>

    </form>
</div>
