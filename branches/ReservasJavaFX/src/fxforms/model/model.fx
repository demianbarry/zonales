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
 * model.fx - Contains FieldSetter, ValueModel, PresentationModel.
 *
 * Developed 2009 by Carl Dea
 * as a JavaFX Script SDK 1.2 to demonstrate an fxforms framework.
 */
package fxforms.model;

import javafx.scene.control.TextBox;
import fxforms.utils.BeanUtil;
import java.lang.Object;
import javafx.lang.FX;
import javafx.scene.Scene;
import javafx.scene.control.Control;
import fxforms.model.DomainModel;
import java.beans.PropertyChangeEvent;
import java.beans.PropertyChangeListener;
import java.lang.String;
import java.lang.Void;
import java.util.Collection;
import java.util.HashMap;
import java.util.Map;
import javafx.scene.image.Image;
import fxforms.model.validation.Validator;

/**
 * This is mixin class to be used for simple gui text field, combobox,
 * spinner, text area type controls.
 */
public mixin class FieldSetter {
    /** The reference to the value model as an adapter to connect to GUI control. */
    public var valueModel:ValueModel on replace oldValueModel{
        // when value model is different then remove gui references connected to
        // value model
        if (FX.isInitialized(valueModel) and not FX.isSameObject(oldValueModel, valueModel)) {
            delete this from oldValueModel.guiControls;
        }

        // add Gui control for value model to notify.
        if (valueModel != null){
            insert this into valueModel.guiControls;
        }
    };
}

/**
 * Class that represents a connector to the bean property value and to the gui
 * controls observers. It contains zero to many validators to validate fields
 * and property.
 *
 */
public class ValueModel {
    public-init var propertyName:String;
    public-init var presentationModel:PresentationModel;
    public var guiControls: FieldSetter[] = [];
    public var validators:Validator[] = [];
    public var boundValue:Object;
    public var jBean:Object on replace oldJBean{
        if (jBean == null) {
            value = null;
        } else {
            // assign value from jBean
            if (jBean != oldJBean)
            {
                value = BeanUtil.getValue(jBean, propertyName);
            } else {
                var strVal = String.valueOf(jBean.getClass());
                println("ValueModel.jBean.getClass(){strVal}");
            }
        }
    }

    /**
     * The value bound to the boundValue when JavaBean or Gui control value
     * changes this value model will be notified.
     */
    public var value:Object = bind boundValue with inverse on replace oldValue{
        println("ValueModel setting value = {value}");
        BeanUtil.setValue(jBean, propertyName, value);
        for (control in guiControls) {
            if (control instanceof TextBox) {
                var txtControl:TextBox = control as TextBox;
                txtControl.text = String.valueOf(value);
            }
        }
        // alert PresModel
        presentationModel.validateAll = true;

    };

    public function valueAsString():String{
        return String.valueOf(value);
    }

    /**
     * Add multiple validators for this value model.
     * @param validator - a validator for this value model
     */
    public function addValidator(validator:Validator) {
        insert validator into validators;
    }

   /**
    * Remove validator from this value model.
    * @param validator - a validator for this value model
    */
    public function removeValidator(validator:Validator) {
        delete validator from validators;
    }

   /**
    * Returns a validation result after all validators are validated with their
    * repective validation results.
    */
    public function validateAll():fxforms.model.validation.ValidationResult{
        var vResultMain:fxforms.model.validation.ValidationResult = fxforms.model.validation.ValidationResult{};
        for (vResult:Validator in validators){
            vResultMain.addResults(vResult.validate(value));
        }
        return vResultMain;
    }

}


/**
 * This represents a mixie class to provide bean to form binding.
 * While this is a JavaFX class it also is a {@link PropertyChangeListener}.
 * A PresentationModel is composed of:
 * <ul>
 *   <li>A reference to a form:fxforms.ui.form.Form.
 *   <li>Zero to Many validators (fxforms.model)
 *   <li>A {@link Map} containing all properties to value models.
 *   <li>A {@link fxforms.model.validation.ValidationResult}
 * </ul>
 * A presentation model can contain zero to many validators.
 */
public mixin class PresentationModel extends PropertyChangeListener {
    public var mainScene:Scene;
    public var form:fxforms.ui.form.Form;
    public var validators:Validator[] = [];
    protected var propertyValues:Map = new HashMap();
    protected var validationResult:fxforms.model.validation.ValidationResult = fxforms.model.validation.ValidationResult{};

    /** When set to true the trigger revalidates form */
    public var validateAll:Boolean = false on replace oldValidateAll {
        if (not oldValidateAll) {

            validationResult.clear();

            // clear the form of all error, warn and info icon on panel
            form.clearValidationIconPanel();

            // generate all messages.
            var valueModels:Collection = propertyValues.values();
            var valueModelArray: nativearray of Object = valueModels.toArray();
            var size = propertyValues.size() - 1 ;
            for (i in [0..size]){
                var valueObject:Object = valueModelArray[i] as Object;
                var valueModel:ValueModel = valueObject as ValueModel;
                validationResult.addResults(valueModel.validateAll());
            }

            // update the panel with the error, warn and info icons onto form panel
            form.updateValidationIconPanel(validationResult);
            validateAll = false;
        }
    }

    /** When a new JavaBean is to be referenced a trigger
     *  detaches references from the old bean and connects
     *  to the new JavaBean.
     */
    public var jBean:DomainModel on replace oldJBean{
        if (jBean != oldJBean){
            jBean.addPropertyChangeListener(this);
            oldJBean.removePropertyChangeListener(this);

            //var keyValuePairs:Iterator = propertyValues.entrySet().iterator();
            var valueModels:Collection = propertyValues.values();
            var valueModelArray: nativearray of Object = valueModels.toArray();
            var size = propertyValues.size() - 1 ;
            for (i in [0..size]){

                // refresh form and then
                // connect value models to
                // the associated bean properties
                var valueObject:Object = valueModelArray[i] as Object;
                var valueModel:ValueModel = valueObject as ValueModel;
                valueModel.jBean = jBean;
                var numControls = sizeof valueModel.guiControls;

                // repopulate GUI controls.
                for (j in [0..numControls-1]){
                    var guiControl = valueModel.guiControls[j] as FieldSetter;
                    println("PresentationModel valueModel.guiControl[{j}] {guiControl}");

                    // @TODO Refactor just use for demo.
                    if (guiControl instanceof TextBox) {
                        var txtControl:TextBox = guiControl as TextBox;
                        txtControl.text = String.valueOf(BeanUtil.getValue(jBean, valueModel.propertyName));
                        valueModel.value = txtControl.text;
                    }
                    valueModel.boundValue = BeanUtil.getValue(jBean, valueModel.propertyName);
                }
            }

        }
    };

    /** Not used was for JavaFX objects associated with forms.*/
    public var jfxBean:Object;

    /** Returns the given ValueModel based on the bean property name.
     * @param propertyName - The JavaBean spec. getter/setter property.
     * @return ValueModel - connector to value and gui controls.
     */
    public function getModel(propertyName:String):ValueModel{
        if (propertyValues.get(propertyName) == null){
            var valueModelHandle:ValueModel = ValueModel{
                propertyName: propertyName
                presentationModel:this
                jBean:jBean
            };
            propertyValues.put(propertyName, valueModelHandle);
        }
        return propertyValues.get(propertyName) as ValueModel;
    }

    // This is called when the JavaBean's setter is called.
    /**
     * When the property changes on the Java side this method will be notified.
     * @param chEvent - Property change event fired.
     */
    public override function propertyChange(chEvent:PropertyChangeEvent):Void {
        //println("PresentationModel. JavaBean setter occured. {chEvent}");
        FX.deferAction(function() : Void {
            if (getModel(chEvent.getPropertyName()).jBean == chEvent.getSource()) {
                println("PresentationModel. JavaBean setter occured: {chEvent} - value = {chEvent.getNewValue()}");
                jBean.removePropertyChangeListener(this);
                getModel(chEvent.getPropertyName()).value = chEvent.getNewValue();
                jBean.addPropertyChangeListener(this);
            }
        });
    }

    /**
     * All references to all GUI fields or controls to use.
     * NOTE: This is temporary until Scene.lookup(id) is working
     * recursivly on a custom node with nested nodes with unique 'id'.
     */
    public function addGuiFields(fields:Control[]){
        for (field in fields) {
            println("addGuiFields -- {field.id}");
            if (field instanceof FieldSetter) {
                var fieldSetter:FieldSetter = field as FieldSetter;
                fieldSetter.valueModel = getModel(field.id);
                addGuiField(field);
                if (field.id != null){
                    form.guiControls.put(field.id, field);
                }
            }
        }
    }

    /**
     * All references to all GUI field or control to use.
     * NOTE: This is temporary until Scene.lookup(id) is working
     * recursivly on a custom node with nested nodes with unique 'id'.
     */
    public function addGuiField(field:Control){
        insert field as FieldSetter into getModel(field.id).guiControls;
    }

    /**
     * Remove a reference to a GUI field or control to use.
     * NOTE: This is temporary until Scene.lookup(id) is working
     * recursivly on a custom node with nested nodes with unique 'id'.
     */
    public function removeGuiField(field:Control){
        delete field as FieldSetter from getModel(field.id).guiControls;
    }

    /**
     * Remove references to many GUI fields or controls from the form.
     * NOTE: This is temporary until Scene.lookup(id) is working
     * recursivly on a custom node with nested nodes with unique 'id'.
     */
    public function removeGuiFields(fields:Control[]){
        for (field in fields) {
            println("removeGuiFields -- {field.id}");
            var fieldSetter:FieldSetter = field as FieldSetter;
            fieldSetter.valueModel = getModel(field.id);
            removeGuiField(field);
            if (field.id != null){
                form.guiControls.remove(field.id);
            }
        }
    }

    /**
     * Add a validator for a particular field or a validator at the form scope 
     * (global type) when validating the form.
     * @param validator - Validator object for validation against field or form.
     */
    public function addValidator(validator:Validator):Void {
        if (not FX.isInitialized(validator.id) or validator.id == null ){
            insert validator into validators;
            return;
        }
        getModel(validator.id).addValidator(validator);
    }

    /**
     * Removes an individual validator from the form.
     * @param validator - Validator object for validation against field or form.
     */
    public function removeValidator(validator:Validator):Void {
        if (not FX.isInitialized(validator.id) or validator.id == null ){
            delete validator from validators;
            return;
        }
        getModel(validator.id).removeValidator(validator);
    }

}
