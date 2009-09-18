package reservasjavafx.domain.model;

import java.lang.Boolean;
import java.lang.String;
import java.util.regex.Pattern;


import java.lang.Object;


import fxforms.model.validation.*;
import java.lang.Object;
import reservasjavafx.domain.model.ResourceBean;

/**
 * This represents a simple edit presentation model for form
 * with validations that demonstrate the
 * Error, Warning and Info icon for validation of the form
 * @author Carl Dea
 */
public class ResourcePresentationModel extends fxforms.model.model.PresentationModel {

        /** Validate the first name field */
        var validateFecha:Validator =  Validator {
            id: ResourceBean.FECHA;
            public override function validate(value:Object){
                return validateF(value, ResourceBean.FECHA, "Warning");
            }
        };

        /** Validate the last name field */
        var validateHora:Validator =  Validator {
            id: ResourceBean.HORA
            public override function validate(value:Object){
                return validateH(value, ResourceBean.HORA, "Error");
            }
        };

        
        postinit {
            addValidator(validateFecha);
            addValidator(validateHora);
        }

}

/**
 * Using regular expression allow d(d)/m(m)/yyyy date format
 */
function validateF(value:Object, propName:String, messageType:String){ // use friendly names, short names, etc.
    var results = ValidationResult{};
    var strValue:String = value as String;
    var found:Boolean = Pattern.matches("^[0-9]\{1,2\}/[0-9]\{1,2\}/[0-9]\{4\}$", strValue);
    if (not found) {
        var message: FieldMessage = FieldMessage{
            id: propName
            messageType: messageType
            errorId: "123"
            errorMessage: "No symbols in names except - or ' (apostrophe)"
        }
        results.addMessage(message);
    }
    return results;
}

/**
 *
 * Using regular expression allow d(d)/m(m)/yyyy date format
 */
function validateH(value:Object, propName:String, messageType:String){ // use friendly names, short names, etc.
    var results = ValidationResult{};
    var strValue:String = value as String;
    var found:Boolean = Pattern.matches("^(0[1-9]|1[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$", strValue);
    if (not found) {
        var message:FieldMessage = FieldMessage{
            id:propName
            messageType:messageType
            errorId:"123"
            errorMessage:"No symbols in names except - or ' (apostrophe)"
        }
        results.addMessage(message);
    }
    return results;
}