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
 * validation.fx - Contains Validator, GenericMessage, FieldMessage,
 *                 ValidationResult.
 *
 * Developed 2009 by Carl Dea
 * as a JavaFX Script SDK 1.2 to demonstrate an fxforms framework.
 */

package fxforms.model;

/**
 * Validator validates a field.
 * 
 */
public class Validator {
    public var id:String; // propertyName

    /**
     * Returns a  ValidationResult object to assist to the caller
     * @param value - the property value to validate against.
     */
    public function validate(value: Object):ValidationResult{
        return ValidationResult{};
    }
}


/**
 * After a validation occurs a generic or global message is created
 * Contains a message type of error, warning or info.
 * A possible error id code (user error code)
 * The error message to assist and display to user.
 */
public class GenericMessage {
    public var messageType:String = "Error";
    public var errorId:String;
    public var errorMessage:String;
}

/**
 * After a validation occurs a field message is created. Many
 * messages can point to a particular field. Example TextBox first name
 */
public class FieldMessage extends GenericMessage{
    public var id:String; // propertyName
}

/**
 * After a validation validation messages are created either generic or
 * field messages.
 */
public class ValidationResult {
    /** all messages for this validation result*/
    public var messages:GenericMessage[]=[];

    /** Clears all error messages for this result.*/
    public function clear():Void{
        delete messages;
        messages = [];
    }

    /**
     * Add a message to this validation result.
     */
    public function addMessage(msg:GenericMessage):Void{
        insert msg into messages;
    }

    /**
     * Add other result messages to this validation result
     */
    public function addResults(results:ValidationResult):Void{
        for (msg:GenericMessage in results.messages) {
            insert msg into messages;
        }
    }

}
