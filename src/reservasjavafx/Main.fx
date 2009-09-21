package reservasjavafx;

import javafx.scene.*;
import javafx.scene.image.*;
import javafx.scene.input.*;
import javafx.scene.paint.*;
import javafx.scene.text.*;
import javafx.scene.shape.*;
import javafx.stage.*;

import reservasjavafx.menu.*;

import javafx.io.http.HttpRequest;

import javafx.animation.Interpolator;
import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.scene.control.Button;
import javafx.scene.input.MouseEvent;
import java.lang.Void;

import reservasjavafx.domain.forms.NameForm;
import reservasjavafx.domain.model.ResourceBean;
import reservasjavafx.domain.model.ResourcePresentationModel;


var image = Image{
    url:"{__DIR__}puzzle_picture.jpg"};

var maxCol:Integer = ((image.width as Integer) / 100) - 1;
var maxRow:Integer = ((image.height as Integer) / 100) - 1;

var gapX = 10;
var gapY = 40;
var TEXT_COLOR = Color.WHITE;

var stageDragInitialX:Number;
var stageDragInitialY:Number;

var imagen:ImageView = ImageView {
            translateX: gapX
            translateY: gapY
            opacity: bind imageOpacity
            image: Image { url: "{__DIR__}images/restaurante2.jpg" }
            
            onMouseClicked: function(e:MouseEvent):Void {
                coords = "{e.x}-{e.y}";
            }
};

var imageOpacity:Float = 1;

// Primer menu flotante
var popupMenu:popupMenu = popupMenu {
    animate:true
    corner:20
    padding:8
    borderWidth:4
    opacity: 0.9
    stroke: Color.web("#FF9900");
    fill: Color.WHITE;
    containerWidth: bind imagen.image.width
    containerHeight: bind imagen.image.height
};

var coords :String;

var g:Group = Group {
    content: [

        // the background and top header
        Rectangle {
            fill: Color.web("#FF9900")
            width: imagen.image.width+gapX*2
            height: 400 },
        Text {
            x: gapX+0
            y:17 content: bind coords
            fill: TEXT_COLOR },
        Line {
            stroke: TEXT_COLOR
            startX: 0
            endX: imagen.image.width+gapX*2
            startY: 30
            endY: 30 },

        // the actual image to be adjusted
        imagen,
        Polygon {
            cursor: Cursor.HAND
            points: [   x(0),y(190),
                        x(71),y(172),
                        x(110),y(178),
                        x(113),y(223),
                        x(0),y(266)     ]
            fill: Color.TRANSPARENT
            onMouseClicked:function(e) {
                mousePressed(e);
            }
        },
        Polygon {
            cursor: Cursor.HAND
            points: [   x(128),y(161),
                        x(165),y(147),
                        x(213),y(151),
                        x(211),y(161),
                        x(171),y(172),
                        x(128),y(171) ]
            fill: Color.TRANSPARENT
            onMouseClicked:function(e) {
                mousePressed(e);
            }
        },
        Polygon {
            cursor: Cursor.HAND
            points: [   x(210),y(141),
                        x(246),y(131),
                        x(279),y(135),
                        x(246),y(151),
                        x(210),y(147)     ]
            fill: Color.TRANSPARENT
            onMouseClicked:function(e) {
                mousePressed(e);
            }
        },
        Polygon {
            cursor: Cursor.HAND
            points: [   x(342),y(135),
                        x(355),y(130),
                        x(399),y(128),
                        x(394),y(138) ]
            fill: Color.TRANSPARENT
            onMouseClicked:function(e) {
                mousePressed(e);
            }
        },
        Polygon {

            cursor: Cursor.HAND
            points: [   x(271),y(127),
                        x(280),y(123),
                        x(310),y(124),
                        x(312),y(131),
                        x(299),y(135)   ]
            fill: Color.TRANSPARENT
            onMouseClicked:function(e) {
                mousePressed(e);
            }
        },
        Polygon {
            cursor: Cursor.HAND
            points: [   x(450),y(137),
                        x(450),y(130),
                        x(507),y(128),
                        x(526),y(136)   ]
            fill: Color.TRANSPARENT
            onMouseClicked:function(e) {
                mousePressed(e);
            }
        },
        Ellipse {
            cursor: Cursor.HAND
            centerX: x(377)
            centerY: y(172)
            radiusX: 90
            radiusY: 20
            fill: Color.TRANSPARENT
            onMouseClicked:function(e) {
                mousePressed(e);
            }
        }

        popupMenu
    ]
};

function x(x:Integer):Integer {
    return x + gapX;
}

function y(y:Integer):Integer {
    return y + gapY;
}

function clickMenuItem(Void):Void {
    java.lang.System.out.println("CLICK!");
}

var getRequest: HttpRequest;

function startRequest(e:MouseEvent) {
    getRequest = HttpRequest {

        location: "http://localhost:8080/pruebasJava/Main?mesa=1";

        onStarted: function() {
            println("onStarted - started performing method: {getRequest.method} on location: {getRequest.location}");
        }

        onConnecting: function() { println("onConnecting") }
        onDoneConnect: function() { println("onDoneConnect") }
        onReadingHeaders: function() { println("onReadingHeaders") }
        onResponseCode: function(code:Integer) { println("onResponseCode - responseCode: {code}") }
        onResponseMessage: function(msg:String) { println("onResponseMessage - responseMessage: {msg}") }

        onResponseHeaders: function(headerNames: String[]) {
            println("onResponseHeaders - there are {headerNames.size()} response headers:");
            for (name in headerNames) {
                println("    {name}: {getRequest.getResponseHeaderValue(name)}");
            }
        }

        onReading: function() { println("onReading") }

        onToRead: function(bytes: Long) {

            if (bytes < 0) {
                println("onToRead - Content length not specified by server; bytes: {bytes}");
            } else {
                println("onToRead - total number of content bytes to read: {bytes}");
            }
        }

        // The onRead callback is called when some more data has been read into
        // the input stream's buffer.  The input stream will not be available until
        // the onInput call back is called, but onRead can be used to show the
        // progress of reading the content from the location.

        onRead: function(bytes: Long) {
            // The toread variable is non negative only if the server provides the content length
            def progress =
                if (getRequest.toread > 0) "({(bytes * 100 / getRequest.toread)}%)" else "";
                println("onRead - bytes read: {bytes} {progress}");
        }

        // The content of a response can be accessed in the onInput callback function.
        // Be sure to close the input sream when finished with it in order to allow
        // the HttpRequest implementation to clean up resources related to this
        // request as promptly as possible.

        onInput: function(is: java.io.InputStream) {
            // use input stream to access content here.
            // can use input.available() to see how many bytes are available.
            try {
                var message = new String();
                var item:menuItem;

                while(is.available() > 0)
                    message += Character.toString(Character.valueOf(is.read()));

                item = menuItem {
                    text: "{message}"
                    call: clickMenuItem
                };

                if(javafx.util.Sequences.indexOf(popupMenu.content, item) == -1) {
                    insert item into popupMenu.content;
                }
                popupMenu.event = e;
            } finally {
                is.close();
            }
        }

        onException: function(ex: java.lang.Exception) {
            println("onException - exception: {ex.getClass()} {ex.getMessage()}");
        }

        onDoneRead: function() {
            println("onDoneRead")
        }

        onDone: function() {
            println("onDone") ;
            getRequest.stop();
        }
    };
    getRequest.start();
}

function mousePressed(e:MouseEvent) {
    if(e.button == MouseButton.SECONDARY) {
        startRequest(e);
    }
}

    var resourceBean:ResourceBean = new ResourceBean();
    resourceBean.setRecurso("Mesa 1");
    resourceBean.setFecha("1/01/2009");
    resourceBean.setHora("13:00");
    resourceBean.setUsuario("Jr.");

    var value:String;

    var resourceForm:NameForm = NameForm{
        presentationModel: ResourcePresentationModel{}
        translateX: bind slideFormX
        translateY: bind gapY
    };
    
    var diff:Float = bind -resourceForm.boundsInParent.width;
    var slideFormX:Float = diff;
    
    resourceForm.presentationModel.jBean = resourceBean;

    var slideRight:Timeline = Timeline {
            repeatCount: 1
            keyFrames : [
                KeyFrame {
                    time : 0s
                    canSkip : true
                    values: [   slideFormX => 0 ]
                },
                KeyFrame {
                    time : 350ms
                    canSkip : true
                    values: [   slideFormX => diff tween Interpolator.EASEBOTH,
                                imageOpacity => 0 ]
                }
                KeyFrame {
                    time : 500ms
                    canSkip : true
                    values: [   imageOpacity => 1 tween Interpolator.EASEBOTH ]
                }
            ]
        };

    var slideLeft:Timeline = Timeline {
            repeatCount: 1
            keyFrames : [
                KeyFrame {
                    time : 0s
                    canSkip : true
                    values: [   imageOpacity => 1 ]
                }
                KeyFrame {
                    time : 150ms
                    canSkip : true
                    values: [   imageOpacity => 0 tween Interpolator.EASEBOTH,
                                slideFormX => diff ]
                }
                KeyFrame {
                    time : 500ms
                    canSkip : true
                    values: [   slideFormX => 0 tween Interpolator.EASEBOTH ]
                }
            ]
        };



    var okButton:Button = Button {
            translateX: bind myScene.width - okButton.width - 5
            translateY: bind myScene.height - okButton.height - 5
            text: " Ok "
            visible: bind (slideFormX == 0)
            action: function() {
                resourceForm.setVisibleErrWarnNodes(false);
                slideRight.stop();
                slideLeft.play();                                
            }
        };

    var cancelButton:Button = Button {
            translateX: bind myScene.width - cancelButton.width - 5 - okButton.width - 5
            translateY: bind myScene.height - cancelButton.height - 5
            text: " Cancel "
            visible: bind (slideFormX == 0)
            action: function() {
                slideLeft.stop();
                slideRight.play();                               
                resourceForm.setVisibleErrWarnNodes(true);
                //resourceForm.toBack();
            }
        };

var myScene:Scene = Scene {
    content: [g, resourceForm, okButton, cancelButton ]
}

// show it all on screen
var stage:Stage = Stage {
    style: StageStyle.UNDECORATED
    visible: true
    scene: myScene
}

resourceForm.presentationModel.mainScene = myScene;