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
 * NameForm.fx - Contains MyTextBox.
 *
 * Developed 2009 by Carl Dea
 * as a JavaFX Script SDK 1.2 to demonstrate an fxforms framework.
 * Created on Jun 14, 2009, 4:41:02 PM
 */
package reservasjavafx.domain.forms;


import javafx.geometry.HPos;
import javafx.scene.Node;
import javafx.scene.control.Label;
import javafx.scene.control.TextBox;
import javafx.scene.effect.DropShadow;
import javafx.scene.layout.HBox;
import javafx.scene.layout.LayoutInfo;
import javafx.scene.layout.Panel;
import javafx.scene.layout.VBox;
import javafx.scene.paint.Color;
import javafx.scene.text.Font;
import javafx.scene.text.Text;
import javafx.scene.CustomNode;


/**
 * @author cdea
 */
public class NameForm extends fxforms.ui.form.Form {
   

//    0 full name panel
//        +-1------------------------+ // VBox with 3 things
//        ! +-2--------------------+ ! // HBox with 2 things
//        ! ! [ 3 ] [ 4           ]! ! // Label(section) and Label(title)
//        ! +----------------------+ !
//        ! +-5--------------------+ ! // HBox with 2 things
//        ! ! [ 6 ] [ 7  ]         ! ! // Label(spacer} and Text(instructions)
//        ! !                      ! ! // wrapping text abilities
//        ! +----------------------+ !
//        ! +-8--------------------+ ! // HBox with 2 things
//        ! ! +-9---+ +-10-------+ ! ! // VBox_9(labels) Vbox_10(textbox)
//        ! ! ![11] ! ! [15]     ! ! ! // Label(lastname) TextBox()
//        ! ! ![12] ! ! [16]     ! ! ! // Label(firstName) TextBox()
//        ! ! ![13] ! ! [17]     ! ! ! // Label(mi)  TextBox()
//        ! ! ![14] ! ! [18]     ! ! ! // Label(suffix)  TextBox()
//        ! ! +-----+ +----------+ ! !
//        ! +----------------------+ !
//        +--------------------------+
//
    public override function create():Node{
        
        // 0 main panel
        var mainPanel:Panel = Panel{
            content:[]
        }
        //var hScrollpane:ScrollView = ScrollView{};
        // 1 main container see 1
        var vBox_1:VBox = VBox{
            content: []
            spacing:5
        }
        //hScrollpane.node = vBox_1;
        // 2 section and instructions container
        var hBox_2:HBox = HBox{
            content: []
            spacing:5
        }

        // 3 Label section number
        var sectionLabel:Label = Label {
            text: ""
            font : Font {
                    size: 24
            }
            layoutInfo: LayoutInfo { minWidth: 30 width: 30 maxWidth: 30 }
        };
        // 4 Text instructions.
        var title:Label = Label {
                text: "Reserva"
                font : Font {
                    size: 24
                }
                effect:DropShadow {
                        offsetX: 4
                        offsetY: 4
                        color: Color.BLACK
                        radius: 10
                }


                layoutInfo: LayoutInfo { minWidth: 200 width: 200 }
        };
        insert sectionLabel into hBox_2.content;
        insert title into hBox_2.content;
        insert hBox_2 into vBox_1.content;
        insert vBox_1 into mainPanel.content;

//        ! +-5--------------------+ ! // HBox with 2 things
//        ! ! [ 6 ] [ 7  ]         ! ! // Label(spacer} and Text(instructions)
//        ! !                      ! ! // wrapping text abilities
//        ! +----------------------+ !
//
        // 5 section and instructions container
        var hBox_5:HBox = HBox{
            spacing:5
            content: []
        }

        // 6 Label spacer
        var instructionLabel:Label = Label {
            text: ""
            font : Font {
                    size: 24
            }
            textWrap:true
            layoutInfo: LayoutInfo { minWidth: 30 width: 30 }
        };
        def instructions =
        "Ha solicitado una reserva con los datos que figuran a continuacion.\n"
        ;
        var instructionsText:Text = Text {
                font : Font {
                    size: 12
                }
                x: 10, y: 30
                content: instructions
                layoutInfo: LayoutInfo { minWidth: 30 width: 30 maxWidth: 30 }
        }
        insert instructionLabel into hBox_5.content;
        insert instructionsText into hBox_5.content;
        insert hBox_5 into vBox_1.content;


//        ! +-8--------------------+ ! // HBox with 2 things
//        ! ! +-9---+ +-10-------+ ! ! // VBox_9(labels) Vbox_10(textbox)
//        ! ! ![11] ! ! [15]     ! ! ! // Label(lastname) TextBox()
//        ! ! ![12] ! ! [16]     ! ! ! // Label(firstName) TextBox()
//        ! ! ![13] ! ! [17]     ! ! ! // Label(mi)  TextBox()
//        ! ! ![14] ! ! [18]     ! ! ! // Label(suffix)  TextBox()
//        ! ! +-----+ +----------+ ! !
//        ! +----------------------+ !
        var hBox_8:HBox = HBox{
            spacing:5
            content: []
        }
        var vBox_9:VBox = VBox{
            spacing:5
            hpos:HPos.LEFT
            content:[]
        }
        var vBox_10:VBox = VBox{
            spacing:5
            content:[]
        }
        var recursoLabel:Label = Label {
            text: "Recurso"
            hpos:HPos.RIGHT
            font : Font {
                size: 18
            }
            layoutInfo: LayoutInfo { minWidth: 100 width: 150 maxWidth: 200 }
        };
        var fechaLabel:Label = Label {
            text: "Fecha"
            hpos:HPos.RIGHT
            font : Font {
                size: 18
            }
            layoutInfo: LayoutInfo { minWidth: 100 width: 150 maxWidth: 200 }
        };
        var horaLabel:Label = Label {
            text: "Hora"
            hpos:HPos.RIGHT
            font : Font {
                size: 18
            }
            layoutInfo: LayoutInfo { minWidth: 100 width: 150 maxWidth: 200 }
        };
        var usuarioLabel:Label = Label {
            text: "Usuario"
            hpos:HPos.RIGHT
            font : Font {
                size: 18
            }
            layoutInfo: LayoutInfo { minWidth: 100 width: 150 maxWidth: 200 }
        };

        insert recursoLabel into vBox_9.content;
        insert fechaLabel into vBox_9.content;
        insert horaLabel into vBox_9.content;
        insert usuarioLabel into vBox_9.content;

        insert vBox_9 into hBox_8.content;

        var recurso:TextBox = fxforms.ui.controls.MyTextBox {
            id:"recurso"
            columns:20
        };
        var fecha:TextBox = fxforms.ui.controls.MyTextBox {
            id:"fecha"
            columns:20
        };
        var hora:TextBox = fxforms.ui.controls.MyTextBox {
            id:"hora"
            columns:20
        };
        var usuario:TextBox = fxforms.ui.controls.MyTextBox {
            id:"usuario"
            columns:5
        };

        insert recurso into vBox_10.content;
        insert fecha into vBox_10.content;
        insert hora into vBox_10.content;
        insert usuario into vBox_10.content;

        insert vBox_10 into hBox_8.content;
        insert hBox_8 into vBox_1.content;

        guiFields = [recurso, fecha, hora, usuario];
        presentationModel.addGuiFields(guiFields);

        return mainPanel;
    }

}