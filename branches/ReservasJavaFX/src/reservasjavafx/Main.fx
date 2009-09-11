/* 
 * DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS FILE HEADER
 * Copyright 2009 Sun Microsystems, Inc. All rights reserved. Use is subject to license terms. 
 * 
 * This file is available and licensed under the following license:
 * 
 * Redistribution and use in source and binary forms, with or without 
 * modification, are permitted provided that the following conditions are met:
 *
 *   * Redistributions of source code must retain the above copyright notice, 
 *     this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright notice,
 *     this list of conditions and the following disclaimer in the documentation
 *     and/or other materials provided with the distribution.
 *
 *   * Neither the name of Sun Microsystems nor the names of its contributors 
 *     may be used to endorse or promote products derived from this software 
 *     without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
package reservasjavafx;

import javafx.scene.*;
import javafx.scene.image.*;
import javafx.scene.input.*;
import javafx.scene.paint.*;
import javafx.scene.text.*;
import javafx.scene.shape.*;
import javafx.stage.*;

import reservasjavafx.menu.*;


var image = Image{
    url:"{__DIR__}puzzle_picture.jpg"};

var maxCol:Integer = ((image.width as Integer) / 100) - 1;
var maxRow:Integer = ((image.height as Integer) / 100) - 1;

var gapX = 10;
var gapY = 40;
var TEXT_COLOR = Color.WHITE;

var stageDragInitialX:Number;
var stageDragInitialY:Number;

/*var applet: Applet = FX.getArgument("javafx.applet") as Applet;
var jsObject = new JavaScriptUtil(applet);

var inBrowser = "true".equals(FX.getArgument("isApplet") as String);
var draggable = AppletStageExtension.appletDragSupported;
var dragRect:Rectangle = Rectangle { x: 0 y: 0 width: 520 height: 25 fill: Color.TRANSPARENT
    onMousePressed: function(e) {
        stageDragInitialX = e.screenX - stage.x;
        stageDragInitialY = e.screenY - stage.y;
    }
     onMouseDragged: function(e) {
        stage.x = e.screenX - stageDragInitialX;
        stage.y = e.screenY - stageDragInitialY;
     }

};
var dragTextVisible = bind inBrowser and draggable and dragRect.hover;*/

var imagen:ImageView = ImageView {
            translateX: gapX
            translateY: gapY
            image: Image { url: "{__DIR__}images/restaurante2.jpg" }
            onMouseClicked: function(e:MouseEvent):Void {
                coords = "{e.x}-{e.y}";
            }

};

/*var dragControl:Group = Group {
    content:[
        Text { x: (imagen.image.width - 5) y: 17 content: "Drag out of Browser" fill: TEXT_COLOR visible: bind dragTextVisible},
        ImageView { x: (imagen.image.width - 5) y: 8 image: Image { url: "{__DIR__}images/close_rollover.png" }
            visible: bind not inBrowser,
            onMouseClicked: function(e:MouseEvent):Void { stage.close(); }
        },
        ImageView { x: (imagen.image.width - 5) y: 8 image: Image { url: "{__DIR__}images/dragOut_rollover.png" }
            visible: bind inBrowser and draggable
        }
        ]
};*/

// Primer menu flotante
var popupMenu=popupMenu {
    animate:false
    corner:20
    padding:8
    borderWidth:4
    opacity: 0.9
    content: [
            menuItem {
                call: clickMenuItem;
                text: "CLICK AQUI"
                pos: 1
            }
    ]
};

var coords :String;

var g:Group = Group {
    content: [

        // the background and top header
        Rectangle { fill: Color.BLACK width: imagen.image.width+gapX*2 height: 400 },
        Text { x: gapX+0  y:17 content: bind coords fill: TEXT_COLOR },
        //dragControl,
        Line { stroke: TEXT_COLOR startX: 0 endX: imagen.image.width+gapX*2 startY: 30 endY: 30 },

        // the actual image to be adjusted
        imagen,
        Polygon {
            points: [   x(0),y(190),
                        x(71),y(172),
                        x(110),y(178),
                        x(113),y(223),
                        x(0),y(266)     ]
            fill: Color.TRANSPARENT
            stroke: Color.RED
            onMouseClicked:function(e) {
                popupMenu.event=e;
            }

        },
        Polygon {
            points: [   x(128),y(161),
                        x(165),y(147),
                        x(213),y(151),
                        x(227),y(177),
                        x(131),y(192)     ]
            fill: Color.TRANSPARENT
            stroke: Color.RED
            onMouseClicked:function(e) {
                popupMenu.event=e;
            }
        },
        Polygon {
            points: [   x(210),y(141),
                        x(246),y(131),
                        x(279),y(135),
                        x(246),y(151),
                        x(210),y(147)     ]
            fill: Color.TRANSPARENT
            stroke: Color.RED
            onMouseClicked:function(e) {
                popupMenu.event=e;
            }
        },
        Ellipse {
            centerX: x(377)
            centerY: y(172)
            radiusX: 90
            radiusY: 20
            stroke: Color.RED
            fill: Color.TRANSPARENT
            onMouseClicked:function(e) {
                popupMenu.event=e;
            }
        }

        popupMenu
    ]
};

// show it all on screen
var stage:Stage = Stage {
    style: StageStyle.UNDECORATED
    visible: true
    scene: Scene {
        content: g
    }/*
    extensions: [
        AppletStageExtension {
            shouldDragStart: function(e): Boolean {
                return inBrowser and e.primaryButtonDown and dragRect.hover;
            }
            onDragStarted: function() {
                inBrowser = false;
            }
            onAppletRestored: function() {
                inBrowser = true;
            }
            useDefaultClose: false
        }
    ]*/
}

/*public function showMessage(message: String) {
    popupWindow.translateX = 200;
    popupWindow.translateY = 200;
    popupWindow.content = message;
    popupWindow.visible = true;  

};*/

function x(x:Integer):Integer {
    return x + gapX;
}

function y(y:Integer):Integer {
    return y + gapY;
}

function clickMenuItem(Void):Void {
    java.lang.System.out.println("CLICK!");
}
