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
 * Main.fx - Contains main code to run().
 *
 * Developed 2009 by Carl Dea
 * as a JavaFX Script SDK 1.2 to demonstrate an fxforms framework.
 * Created on Aug 13, 2009, 1:32:42 AM
 */

package domain;
import javafx.animation.Interpolator;
import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.scene.Node;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.control.CheckBox;
import javafx.scene.input.MouseEvent;
import javafx.scene.paint.Color;
import javafx.scene.paint.LinearGradient;
import javafx.scene.paint.Stop;
import javafx.stage.Stage;
import java.lang.Float;
import java.lang.String;
import java.lang.Void;
import domain.forms.nameform.NameForm;

/**
 * @author Carl Dea
 */

function run( args : String[] ){

    var personBean1:domain.model.PersonBean = new domain.model.PersonBean();
    var personBean2:domain.model.PersonBean = new domain.model.PersonBean();
    println("1 -----------> personBean1 {personBean1} firstname = {personBean1.getFirstName()}");
    println("2 -----------> personBean2 {personBean2} firstname = {personBean2.getFirstName()}");
    println("====================================================");
    personBean1.setFirstName("SpongeBob");
    personBean1.setLastName("SquarePants");
    personBean1.setMiddleName("Nickelodeon");
    personBean1.setSuffixName("Jr.");

    personBean2.setFirstName("Squidward");
    personBean2.setLastName("Tentacles");
    personBean2.setMiddleName("Nickelodeon");
    personBean2.setSuffixName("Sr.");
    println("3 -----------> personBean1 {personBean1} firstname = {personBean1.getFirstName()}");
    println("4 -----------> personBean2 {personBean2} firstname = {personBean2.getFirstName()}");
    println("====================================================");
    var value:String;
    var slideFormX:Float;
    var personForm:NameForm = NameForm{
        presentationModel:domain.model.personpresentationmodel.EditPersonPM{}
        translateX: bind slideFormX
    };
    personForm.presentationModel.jBean = personBean2;

    println("9 -----------> personBean1 {personBean1} firstname = {personBean1.getFirstName()}");
    println("10 -----------> personBean2 {personBean2} firstname = {personBean2.getFirstName()}");
    println("====================================================");

    var diff:Float = bind personForm.boundsInParent.width;
    var slideLeft:Timeline = Timeline {
            repeatCount: 1
            keyFrames : [
                KeyFrame {
                    time : 0s
                    canSkip : true
                    values: slideFormX => 0
                },
                KeyFrame {
                    time : 350ms
                    canSkip : true
                    values: slideFormX => -diff tween Interpolator.EASEIN
                }
            ]
        };
    var slideRight:Timeline = Timeline {
            repeatCount: 1
            keyFrames : [
                KeyFrame {
                    time : 0s
                    canSkip : true
                    values: slideFormX => -diff
                }
                KeyFrame {
                    time : 350ms
                    canSkip : true
                    values: slideFormX => 0 tween Interpolator.EASEBOTH
                }
            ]
        };



    var nextButton:Button = Button {
            translateX: bind mainScene.width - nextButton.width - 5
            translateY: bind mainScene.height - nextButton.height - 5
            text: " Next >> "
            action: function() {
                personForm.setVisibleErrWarnNodes(false);
                slideRight.stop();
                slideLeft.play();
            }
        };
    var backButton:Button = Button {
            translateX: bind mainScene.width - backButton.width - 5 - nextButton.width - 5
            translateY: bind mainScene.height - backButton.height - 5
            text: " << Back "
            action: function() {
                slideLeft.stop();
                slideRight.play();
                personForm.setVisibleErrWarnNodes(true);
                personForm.toBack();
            }
        };
    var personToSwitchText:String = "Squidward";
    var switchPersonButton:CheckBox = CheckBox {
            text: bind personToSwitchText
            width: 100
            translateX: 5
            translateY: bind mainScene.height - switchPersonButton.height - 5
            allowTriState: false
            selected: false
            override var onMouseReleased = function(e:MouseEvent):Void {
                if (selected){
                   personToSwitchText = "Sponge Bob";
                   personForm.presentationModel.jBean = personBean1;
                } else {
                   personToSwitchText = "Squidward";
                   personForm.presentationModel.jBean = personBean2;
                }
            }
        };

    var mainScene:Scene = Scene {
        fill: LinearGradient {
                    startX: 0
                    startY: 0
                    endX: 0
                    endY: 1
                    stops: [
                        Stop { offset: 0.1 color: Color.ORANGE },
                        Stop { offset: 1.0 color: Color.YELLOW },
                    ]
                }
        content: [personForm, switchPersonButton, backButton, nextButton]
    };

    personForm.presentationModel.mainScene = mainScene;

    //http://forums.sun.com/thread.jspa?threadID=5399952
    //http://javafx-jira.kenai.com/browse/RT-5307
    var something:Node = personForm;
    println(" www {something.lookup("lastName")}");

    var mainScreen  : Stage;
    var mainOpacity : Float = 0.0;

    mainScreen = Stage {
                        title: "iSF86"
                        opacity: bind mainOpacity
                        width: 500
                        height: 300
                        scene: mainScene
                        onClose: function() {
                            java.lang.System.exit(0);
                        }
                   };

    var startTransitionApp = Timeline {
       repeatCount: 1
       keyFrames : [
           KeyFrame {
               time : 0s
               canSkip : true
               values: mainOpacity => 0.0
           }
           KeyFrame {
               time : 4s
               canSkip : true
               values: mainOpacity => 1.0 tween Interpolator.LINEAR
           }
       ]
    }
    startTransitionApp.play();
}
