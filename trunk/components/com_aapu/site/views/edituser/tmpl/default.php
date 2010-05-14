<?php
defined('_JEXEC') or die('Restricted access');
//JHTML::_('behavior.formvalidation');
JHTML::stylesheet('aapu.css', 'administrator/components/com_aapu/css/');
?>

<!-- Validacion -->
<script language="javascript" type="text/javascript">
    <!--
    var ret;
    var isValid = true;
    var pressButton;

    function submitbutton(pressbutton) {

	pressButton = pressbutton;

        if (pressbutton == 'cancelUser') {
            submitform( pressbutton );
            return;
        }

	ret = <?php echo $this->user->attrCount; ?>;

        $each([<?php echo $this->user->validString ?>], function(id, index){
            validate_attr($(id));
        });

        return;
    }

    function validate_attr(attr) {

        if (attr.id == 'name' || attr.id == 'username' || attr.id == 'email') {
            ret--;
            if (attr.hasClass('required')) {
                if (!attr.getValue()) {
                        $('valid_'+attr.id).innerHTML = "Campo requerido";
                        return false;
                } else {
                    $('valid_'+attr.id).innerHTML = "";
                }
            }
            if(!Validate_Email_Address($('email').value)) {
                $('valid_email').innerHTML = "Email invalido";
                return false;
            } else {
                $('valid_email').innerHTML = "";
            }
        } else {

            // If the field is required make sure it has a value
            if ($(attr).hasClass('required')) {
                    if (!($(attr).getValue())) {
                            $('valid_'+attr.id.substring(5)).innerHTML = "Campo requerido";
                            return false;
                    }
            }

            var url="administrator/index.php?option=com_aapu&format=raw&task=validateAttr&attrId="+attr.id+"&attrValue="+attr.value;

            new Ajax(url, {
                method: 'get',
                update: $('valid_'+attr.id.substring(5)),
                onComplete: function(response) {
                    if (response != '') {
                            isValid = false;
                    }
                    ret--;
                    if (ret == 0) {
                         if (isValid) {
                            submitform( pressButton );
                         } else {
                            isValid = true;
                            ret = <?php echo $this->user->attrCount; ?>;
                         }
                    }
                }
            }).request();
        }
    }

    function Validate_String(string, return_invalid_chars)
         {
         valid_chars = '1234567890-_.^~abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
         invalid_chars = '';

         if(string == null || string == '')
            return(true);

         //For every character on the string.
         for(index = 0; index < string.length; index++)
            {
            un_char = string.substr(index, 1);

            //Is it a valid character?
            if(valid_chars.indexOf(un_char) == -1)
              {
              //If not, is it already on the list of invalid characters?
              if(invalid_chars.indexOf(un_char) == -1)
                {
                //If it's not, add it.
                if(invalid_chars == '')
                   invalid_chars += un_char;
                else
                   invalid_chars += ', ' + un_char;
                }
              }
            }

         //If the string does not contain invalid characters, the function will return true.
         //If it does, it will either return false or a list of the invalid characters used
         //in the string, depending on the value of the second parameter.
         if(return_invalid_chars == true && invalid_chars != '')
           {
           last_comma = invalid_chars.lastIndexOf(',');

           if(last_comma != -1)
              invalid_chars = invalid_chars.substr(0, $last_comma) +
              ' and ' + invalid_chars.substr(last_comma + 1, invalid_chars.length);

           return(invalid_chars);
           }
         else
           return(invalid_chars == '');
         }


    function Validate_Email_Address(email_address)
         {
         //Assumes that valid email addresses consist of user_name@domain.tld
         at = email_address.indexOf('@');
         dot = email_address.indexOf('.');

         if(at == -1 ||
            dot == -1 ||
            dot <= at + 1 ||
            dot == 0 ||
            dot == email_address.length - 1)
            return(false);

         user_name = email_address.substr(0, at);
         domain_name = email_address.substr(at + 1, email_address.length);

         if(Validate_String(user_name) === false ||
            Validate_String(domain_name) === false)
            return(false);

         return(true);
         }


    //-->
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" >

    <?php
    $panel =& JPANE::getInstance('Tabs');

    echo $panel->startPane('panel');
    echo $panel->startPanel(JText::_( 'Personal Data' ),'personal');?>

    <fieldset class="adminform">
        <!--<legend><?php echo JText::_( 'Details' ); ?></legend>-->
        <table class="admintable">
            <tr>
                <td class="key">
                    <label for="name"><?php echo JText::_( 'Name' ); ?> *:</label>
                </td>
                <td colspan="2">
                    <input class="text_area required" type="text" name="name" id="name"
                           value="<?php echo $this->user->name; ?>" size="40" maxlength="90"
                           title="<?php echo JText::_( 'QUOTA_TIPTITLE' ); ?>"
                           onblur="validate_attr($('name'))"/>
                    <div id="valid_name" style="float: right; color: red"></div>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <label for="username"><?php echo JText::_( 'Username' ); ?> *:</label>
                </td>
                <td colspan="2">
                     <input class="text_area required" type="text" name="username" id="username"
                           value="<?php echo $this->user->username; ?>" size="40" maxlength="90"
                           title="<?php echo JText::_( 'QUOTA_TIPTITLE' ); ?>"
                           onblur="validate_attr($('username'))"/>
                    <div id="valid_username" style="float: right; color: red"></div>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <label for="email"><?php echo JText::_( 'Email' ); ?> *:</label>
                </td>
                <td colspan="2">
                    <input class="text_area required" type="text" name="email" id="email"
                           value="<?php echo $this->user->email; ?>" size="40" maxlength="90"
                           title="<?php echo JText::_( 'QUOTA_TIPTITLE' ); ?>"
                           onblur="validate_attr($('email'))"/>
                    <div id="valid_email" style="float: right; color: red"></div>
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

            <?php foreach ($this->user->attributes as $attr) { ?>
                <tr>

                    <!--CODIGO DE RENDER DEL ARCHIVO INDICADO EN LA BASE DE DATOS-->
                    <?php
                        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'plugins'.DS.'renders'.DS.$attr->datatype->render );
                        $classname = substr($attr->datatype->render, 0, -4);
                        $aept = call_user_func(array($classname,'getPrimitiveType'));
                        echo call_user_func_array( array( $classname, 'render' ), array($attr->id, $attr->value[0]->$aept, $attr->label, $attr->required, array('name' => $attr->name, 'values_list' => $attr->values_list)) );
                    ?>
                    <!-- HASTA ACA -->

                </tr>
                <input type="hidden" name="aeid_<?php echo $attr->id; ?>" id="aten_<?php echo $attr->id; ?>" value="<?php echo $attr->value[0]->id; ?>" />
                <input type="hidden" name="aept_<?php echo $attr->id; ?>" id="aten_<?php echo $attr->id; ?>" value="<?php echo $aept; ?>" />
            <?php } ?>

        </table>
    </fieldset>

    <?php
    echo $panel->endPanel();

    foreach ($this->types as $type) {
        echo $panel->startPanel(JText::_( $type->label ),$type->name);?>

        <fieldset class="adminform">
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
                            echo call_user_func_array( array( $classname, 'render' ), array($attr->id, $attr->value[0]->$aept, $attr->label, $attr->required, array('name' => $attr->name, 'values_list' => $attr->values_list)) );
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
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="aapu_task" value=""/>
    <input type="button" class="button" value="<?php echo JText::_('Save'); ?>" onclick="submitbutton('saveUser');"/>
    <input type="button" class="button" value="<?php echo JText::_('Cancel'); ?>" onclick="submitbutton('cancelUser');" />
    <?php echo JHTML::_( 'form.token' ); ?>
</form>
<div class="obligatorio">
<br>
* <?php echo JText::_( 'required field' ); ?>
</div>