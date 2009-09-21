/*
 * Copyright (c) 2009, Carl Dea
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 * 3. Neither the name of JFXtras nor the names of its contributors may be used
 *    to endorse or promote products derived from this software without
 *    specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * personpresentationmodel.fx - Contains EditPersonPM.
 *
 * Developed 2009 by Carl Dea
 * as a JavaFX Script SDK 1.2 to demonstrate an fxforms framework.
 * Created on Jun 14, 2009, 4:41:02 PM
 */
package domain.model;

import java.lang.Boolean;
import java.lang.String;
import java.util.regex.Pattern;
import domain.model.PersonBean;
import fxforms.model.validation.FieldMessage;
import fxforms.model.validation.ValidationResult;
import fxforms.model.validation.Validator;
import java.lang.Object;

/**
 * This represents a simple edit presentation model for form
 * with validations that demonstrate the
 * Error, Warning and Info icon for validation of the form
 * @author Carl Dea
 */
public class EditPersonPM extends fxforms.model.model.PresentationModel {

        /** Validate the first name field */
        var validateFirstName =  Validator{
            id:PersonBean.FIRST_NAME_PROPERTY
            public override function validate(value:Object){
                return validateName(value, PersonBean.FIRST_NAME_PROPERTY, "Warning");
            }
        };

        /** Validate the last name field */
        var validateLastName =  Validator{

            id:PersonBean.LAST_NAME_PROPERTY
            public override function validate(value:Object){
                return validateName(value, PersonBean.LAST_NAME_PROPERTY, "Error");
            }
        };

        /** Validate the middle name field */
        var validateMiddleName =  Validator{
            id:PersonBean.MIDDLE_NAME_PROPERTY
            public override function validate(value:Object){
                return validateName(value, PersonBean.MIDDLE_NAME_PROPERTY, "Info");
            }
        };
        
        postinit {
            addValidator(validateLastName);
            addValidator(validateFirstName);
            addValidator(validateMiddleName);
        }

}

/**
 * Using regular expression allow letters, apostrophe, hyphen
 */
function validateName(value:Object, propName:String, messageType:String){ // use friendly names, short names, etc.
    var results = ValidationResult{};
    var strValue:String = value as String;
    var found:Boolean = Pattern.matches("^[a-zA-Z,'.\\-\\s]*$", strValue);
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
