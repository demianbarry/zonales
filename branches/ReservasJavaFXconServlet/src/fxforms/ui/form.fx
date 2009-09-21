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
 * form.fx - Contains Form.
 *
 * Developed 2009 by Carl Dea
 * as a JavaFX Script SDK 1.2 to demonstrate an fxforms framework.
 * Created on Aug 13, 2009, 1:32:42 AM
 */
package fxforms.ui;

import javafx.scene.control.Control;
import javafx.scene.image.Image;
import javafx.scene.Node;
import java.util.HashMap;
import java.util.Map;
import javafx.scene.image.ImageView;
import javafx.scene.CustomNode;
import fxforms.model.validation.ValidationResult;

/**
 * @author Carl Dea
 */
public var ERROR_IMAGE:Image = Image {
    url: "{__DIR__}error.png"
};
public var WARN_IMAGE:Image = Image {
    url: "{__DIR__}warn.png"
};
public var INFO_IMAGE:Image = Image {
    url: "{__DIR__}info.png"
};

// ------------------- Form --------------------------------------
// -- An abstract class for implementors to extend for form
// -- building.
// ---------------------------------------------------------------
public abstract class Form extends CustomNode {
    public var errWarnNodes:Node[] = [];    // err, warn, info ImageViews
    protected var errWarnNodesOn:Float = 1; // opacity changer off / on
    public var guiFields:Control[] = [];    // each field used
    public var guiControls:Map = new HashMap(); // any controls to find
                                                // lookup(id) bug in jfx 1.2

    // Presentation model to be swapped for a form.
    public var presentationModel:fxforms.model.model.PresentationModel on replace oldPresentationModel {
        if (FX.isInitialized(presentationModel) and not FX.isSameObject(oldPresentationModel, presentationModel)) {
            oldPresentationModel.removeGuiFields(guiFields);
            oldPresentationModel.form = null;
            presentationModel.form = this;
        }

        if (presentationModel != null){
            presentationModel.addGuiFields(guiFields);
        }
    };

    // Clears all icons on the form panel
    public var clearValidationIconPanel = function():Void {
        for(errWarn:Node in errWarnNodes) {
            println("deleting errWarn Node: {errWarn.id}");
            delete errWarn from scene.content;
        }
        delete errWarnNodes;
    }

    // Add error, warn or info icon on the form panel
    protected var addErrWarnInfoIcon = function(img:Image, x:Number, y:Number):Void{
            var errorImage = ImageView {
                  opacity: bind errWarnNodesOn
                  image: img
                  x:x
                  y:y
            };
            insert errorImage into scene.content;
            insert errorImage into errWarnNodes;
    }

    // Add all validation icons of error, warn and info icons onto the form panel
    public var updateValidationIconPanel = function(validationResult:ValidationResult):Void {
           for (msg:fxforms.model.validation.GenericMessage in validationResult.messages){
                if (msg instanceof fxforms.model.validation.FieldMessage){

                    var fMsg = msg as fxforms.model.validation.FieldMessage;
                    println("{msg.messageType} field:{fMsg.id} errorId:{msg.errorId} errormsg:{msg.errorMessage}");
                    var node:Node = guiControls.get(fMsg.id) as Node;
                    println("**** Error or Warn on field called {node.id}");
                    println("node={node}, node.parent={node.parent}");

                    var x = node.parent.boundsInParent.maxX + 3;
                    var y = node.parent.boundsInParent.maxY + node.boundsInParent.minY - ((node.layoutBounds.height + 16)/2);
                    if (msg.messageType.startsWith("E")) {
                        addErrWarnInfoIcon(ERROR_IMAGE,x,y);
                    } else if (msg.messageType.startsWith("W")) {
                        addErrWarnInfoIcon(WARN_IMAGE,x,y);
                    } else if (msg.messageType.startsWith("I")) {
                        addErrWarnInfoIcon(INFO_IMAGE,x,y);
                    }
                }else{
                    // @TODO add global error messages.
                    println("{msg.messageType} errorId:{msg.errorId} errormsg:{msg.errorMessage}");
                }
            }
    }

    // set visibility of the error, warn and info icons
    public var setVisibleErrWarnNodes = function(visible:Boolean):Void {
        if (visible) {
            errWarnNodesOn = 1.0; // opacity level
        } else {
            errWarnNodesOn = 0.0; 
        }
    }


}
