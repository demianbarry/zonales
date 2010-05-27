<?php
defined('_JEXEC') or die('Restricted access');

$eqId = $data['eqId'];
$jsCode = $data['jsCode'];
$divs = $data['divs'];

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
            this.send({
                onSuccess: function(response) {
                    var resp = Json.evaluate(response);
                    if (resp.status == 'SUCCESS') {
                        for (i=0;i < $('adminForm').elements.length;i++){
                            var subString = $('adminForm').elements[i].name.substring(3,0);
                            if ((subString == 'cp_')_&& ($('adminForm').elements[i].value == 2)){

                            }
                        }
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
            <input type="button" class="button" value="<?php echo JText::_('Add'); ?>" onclick="this.form.submit();"/>
            <input type="button" class="button" value="<?php echo JText::_('Close'); ?>" onclick="window.parent.document.getElementById('sbox-window').close();"/>
        </div>
        <?php
        echo $divs;
        ?>

    </form>
</div>