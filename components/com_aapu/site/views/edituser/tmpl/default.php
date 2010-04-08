<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.formvalidation');
?>

<LINK REL=StyleSheet HREF="administrator/templates/khepri/css/general.css" TYPE="text/css" MEDIA=screen>

<!-- Validacion -->
<script language="javascript" type="text/javascript">
    <!--

    function submitbutton(pressbutton) {
        var form = document.adminForm;

        if (pressbutton == 'cancelUser') {
            submitform( pressbutton );
            return;
        }

        if (document.formvalidator.isValid(form) == false) {
            return validateForm(form);
        }

        submitform( pressbutton );
        return true;
    }

    function validateForm(frm) {
        if (frm.tipo.hasClass('invalid')) {
            alert("<?php echo JText::_( 'TIPO_WARNING', true ); ?>");
        }
        return false;
    }

    //-->
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">

    <?php
    $panel =& JPANE::getInstance('Tabs');

    echo $panel->startPane('panel');
    echo $panel->startPanel(JText::_( 'Personal Data' ),'personal');?>

    <fieldset class="adminform">
        <!--<legend><?php echo JText::_( 'Details' ); ?></legend>-->
        <table class="admintable">
            <tr>
                <td class="key">
                    <label for="name"><?php echo JText::_( 'Name' ); ?>:</label>
                </td>
                <td colspan="2">
                    <input class="text_area required" type="text" name="name" id="name" value="<?php echo $this->user->name; ?>" size="40" maxlength="90" title="<?php echo JText::_( 'QUOTA_TIPTITLE' ); ?>" />
                </td>
            </tr>
            <tr>
                <td class="key">
                    <label for="username"><?php echo JText::_( 'Username' ); ?>:</label>
                </td>
                <td colspan="2">
                    <input class="text_area required" type="text" name="username" id="username" value="<?php echo $this->user->username; ?>" size="40" maxlength="90" title="<?php echo JText::_( 'QUOTA_TIPTITLE' ); ?>" />
                </td>
            </tr>
            <tr>
                <td class="key">
                    <label for="email"><?php echo JText::_( 'Email' ); ?>:</label>
                </td>
                <td colspan="2">
                    <input class="text_area required" type="text" name="email" id="email" value="<?php echo $this->user->email; ?>" size="40" maxlength="90" title="<?php echo JText::_( 'QUOTA_TIPTITLE' ); ?>" />
                </td>
            </tr>
            <tr>
                <td class="key">
                    <label for="registerDate"><?php echo JText::_( 'Register Date' ); ?>:</label>
                </td>
                <td class="key">
                    <label><?php echo $this->user->registerDate; ?>:</label>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <label for="lastvisitDate"><?php echo JText::_( 'Last Visit Date' ); ?>:</label>
                </td>
                <td class="key">
                    <label><?php echo $this->user->lastvisitDate; ?>:</label>
                </td>
            </tr>
        </table>
    </fieldset>

    <?php
    echo $panel->endPanel();

    foreach ($this->types as $type) {
        echo $panel->startPanel(JText::_( $type->label ),$type->name);?>

        <fieldset class="adminform">
            <legend><?php echo JText::_( 'Details' ); ?></legend>
            <table class="admintable">

                <?php
                    foreach ($type->attributes as $attr) {
                ?>
                    <tr>

                        <!--ACA DEBERIA TOMAR EL CODIGO DE RENDER DEL ARCHIVO INDICADO EN LA BASE DE DATOS-->
                        <?php
                            require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'plugins'.DS.'renders'.DS.$attr->datatype->render );
                            $classname = substr($attr->datatype->render, 0, -4);
                            $aept = call_user_func(array($classname,'getPrimitiveType'));
                            echo call_user_func_array( array( $classname, 'render' ), array($attr->id, $attr->value[0]->$aept, $attr->label, $attr->name) );
                        ?>
                        <!-- HASTA ACA -->

                    </tr>
                    
                    <input type="hidden" name="aeid_<?php echo $attr->id; ?>" id="aten_<?php echo $attr->id; ?>" value="<?php echo $attr->value[0]->id; ?>" />
                    <input type="hidden" name="aept_<?php echo $attr->id; ?>" id="aten_<?php echo $attr->id; ?>" value="<?php echo $aept; ?>" />
                <?php
                    }
                ?>

            </table>
        </fieldset>

    <?php echo $panel->endPanel();
      }

      echo $panel->endPane();

    ?>

    <div class="clr"></div>

    <input type="hidden" name="id" value="<?php echo $this->user->id; ?>" />
    <input type="hidden" name="option" value="<?php echo $option;?>" />
    <input type="hidden" name="aapu_task" value=""/>
    <input type="button" class="button" value="<?php echo JText::_('Save'); ?>" onclick="this.form.aapu_task.value='saveUser';this.form.submit();"/>
    <input type="button" class="button" value="<?php echo JText::_('Cancel'); ?>" onclick="this.form.aapu_task.value='cancelUser';this.form.submit();" />
    <?php echo JHTML::_( 'form.token' ); ?>
</form>