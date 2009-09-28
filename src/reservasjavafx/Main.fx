package reservasjavafx;

import javafx.scene.*;
import javafx.scene.image.*;
import javafx.scene.input.*;
import javafx.scene.paint.*;
import javafx.scene.text.*;
import javafx.scene.shape.*;
import javafx.io.http.HttpRequest;
import java.util.StringTokenizer;
import javafx.animation.Interpolator;
import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.scene.control.Button;
import javafx.scene.input.MouseEvent;
import java.lang.Void;
import reservasjavafx.domain.forms.NameForm;
import reservasjavafx.domain.model.ResourceBean;
import reservasjavafx.domain.model.ResourcePresentationModel;
import reservasjavafx.CustomResource;
import calendarpicker.CalendarPicker;
import javafx.stage.Stage;
import javafx.stage.StageStyle;
import reservasjavafx.menu.popupMenu;
//import reservasjavafx.menu.menuItem;
import javafx.scene.layout.VBox;

import javafx.scene.layout.HBox;
import java.util.Calendar;

import calendarpicker.ComboBox;

var image = Image{
    url:"{__DIR__}puzzle_picture.jpg"};

var maxCol:Integer = ((image.width as Integer) / 100) - 1;
var maxRow:Integer = ((image.height as Integer) / 100) - 1;

var gapX = 10;
var gapY = 40;
var TEXT_COLOR = Color.BLACK;

var mesa: Integer = 0;
var selected: String;

var stageDragInitialX:Number;
var stageDragInitialY:Number;

var coords :String;
var panelWidth:Number = 0.42;

var imageOpacity:Float = 1;

var imagen:ImageView = ImageView {
            opacity: bind imageOpacity
            image: Image { url: "{__DIR__}images/restaurante2.jpg" }

            onMouseClicked: function(e:MouseEvent):Void {
                coords = "{e.x}-{e.y}";
            }
};

// Primer menu flotante
var myPopupMenu:popupMenu = popupMenu {
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

// the picker
var calendarPicker:CalendarPicker = CalendarPicker {
        mode: CalendarPicker.MODE_SINGLE
        
};

var currentCalendar = bind calendarPicker.calendar on replace {
        startConsultaRequest(null, "http://localhost:8080/pruebasJava/Main?accion=consulta&locacion=resto&nroMesa=1");
};


var layout:Group = Group {
    content: [
        // the actual image
        imagen,
        CustomResource {
            points: [   x(0),y(190),
                        x(71),y(172),
                        x(110),y(178),
                        x(113),y(223),
                        x(0),y(266)     ]
            fill: Color.TRANSPARENT
            stroke: Color.TRANSPARENT
            nroRecurso: 1
            onMouseClicked:function(e) {
                mousePressed(e);
            }
        },
        CustomResource {
            points: [   x(128),y(161),
                        x(165),y(147),
                        x(213),y(151),
                        x(211),y(161),
                        x(171),y(172),
                        x(128),y(171) ]
            fill: Color.TRANSPARENT
            stroke: Color.TRANSPARENT
            nroRecurso: 2
            onMouseClicked:function(e) {
                mousePressed(e);
            }
        },
        CustomResource {
            points: [   x(210),y(141),
                        x(246),y(131),
                        x(279),y(135),
                        x(246),y(151),
                        x(210),y(147)     ]
            fill: Color.TRANSPARENT
            stroke: Color.TRANSPARENT
            nroRecurso: 3
            onMouseClicked:function(e) {
                mousePressed(e);
            }
        },
        CustomResource {
            points: [   x(342),y(135),
                        x(355),y(130),
                        x(399),y(128),
                        x(394),y(138) ]
            fill: Color.TRANSPARENT
            stroke: Color.TRANSPARENT
            nroRecurso: 4
            onMouseClicked:function(e) {
                mousePressed(e);
            }
        },
        CustomResource {
            points: [   x(271),y(127),
                        x(280),y(123),
                        x(310),y(124),
                        x(312),y(131),
                        x(299),y(135)   ]
            fill: Color.TRANSPARENT
            stroke: Color.TRANSPARENT
            nroRecurso: 5
            onMouseClicked:function(e) {
                mousePressed(e);
            }
        },
        CustomResource {
            points: [   x(450),y(137),
                        x(450),y(130),
                        x(507),y(128),
                        x(526),y(136)   ]
            fill: Color.TRANSPARENT
            stroke: Color.TRANSPARENT
            nroRecurso: 6
            onMouseClicked:function(e) {
                mousePressed(e);
            }
        },
        CustomResource {
            type: CustomResource.ELLIPSE
            x: x(377)
            y: y(172)
            radX: 90
            radY: 20
            fill: Color.TRANSPARENT
            stroke: Color.TRANSPARENT
            nroRecurso: 7
            onMouseClicked:function(e) {
                mousePressed(e);
            }
        }
        myPopupMenu
    ]
};

var button:Button = Button {

}

var slotSelectedIndexChanged = bind slotComboBox.selectedIndex; // on change event


var vbox:VBox = VBox {
    translateX: gapX
    translateY: gapY
    content: [  calendarPicker  ]
}


var hbox:HBox = HBox {
    content:[   Group {
                    content: [  Rectangle {
                                    width: (imagen.image.width)*panelWidth - gapX*2
                                },
                                vbox    ]
                },
                layout  ]
}

var dayText:Text = Text {
                            x: gapX+0
                            y:17 content: bind "{dayOfWeek(currentCalendar.get(Calendar.DAY_OF_WEEK))}, {currentCalendar.get(java.util.Calendar.DATE)}-{currentCalendar.get(java.util.Calendar.MONTH) + 1}-{currentCalendar.get(java.util.Calendar.YEAR)}";
                            fill: TEXT_COLOR
                        }


var slotComboBox : ComboBox = ComboBox {
    translateX: bind (dayText.x + dayText.boundsInLocal.width + gapX*2)
};
slotComboBox.select(0);
slotComboBox.skin.node.visible = false;

var background:Group = Group {
    content: [
        // the background and top header
                        dayText,
                        slotComboBox,
                        Line {
                            stroke: TEXT_COLOR
                            startX: 0
                            endX: (imagen.image.width + gapX*2) * (1 + panelWidth)
                            startY: 30
                            endY: 30
                        },
                        VBox {
                            content: [  Rectangle {
                                            fill: Color.TRANSPARENT
                                            width: (imagen.image.width) * (1 + panelWidth) - gapX*2
                                            height: gapY
                                        },
                                        hbox,
                                        Rectangle {
                                            fill: Color.TRANSPARENT
                                            width: (imagen.image.width) * (1 + panelWidth) - gapX*2
                                            height: gapY
                                        }   ]
        }
    ]
}

function x(x:Integer):Integer {
    return x + imagen.x as Integer;
}

function y(y:Integer):Integer {
    return y + imagen.y as Integer ;
}




var resourceBean:ResourceBean = new ResourceBean();

var value:String;

var resourceForm:NameForm = NameForm{
        presentationModel: ResourcePresentationModel{}
        translateX: bind slideFormX
        translateY: bind gapY

};

var diff:Float = bind -resourceForm.boundsInParent.width*2;
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

function mousePressed(e:MouseEvent) {
        var recurso: CustomResource = e.node as CustomResource;

        if(e.button == MouseButton.SECONDARY) {
            mesa = recurso.nroRecurso as Integer;
            startConsultaRequest(e, "http://localhost:8080/pruebasJava/Main?accion=consulta&locacion=resto&nroMesa={recurso.nroRecurso as Integer}");
        }
}

function optionSelected(texto:String):Void {
        resourceBean.setRecurso("Mesa {mesa}");
        resourceBean.setFecha("01/01/2009");
        resourceBean.setHora("{texto}");
        resourceBean.setUsuario("Jr.");
        selected = texto;
        slideLeft.play();
        slideRight.stop();
        resourceForm.setVisibleErrWarnNodes(true);
}

var okButton:Button = Button {
            translateX: bind myScene.width - okButton.width - 5
            translateY: bind myScene.height - okButton.height - 5
            text: " Ok "
            visible: bind (slideFormX == 0)
            action: function() {
                startConfirmaRequest(selected);
                resourceForm.setVisibleErrWarnNodes(false);
                slideRight.play();
                slideLeft.stop();
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
                resourceForm.setVisibleErrWarnNodes(false);
            }
};


var myScene:Scene = Scene {
    fill: Color.web("#FF9900")
    content: [background, resourceForm, okButton, cancelButton]
}

// show it all on screen
var stage:Stage = Stage {
    style: StageStyle.TRANSPARENT
    visible: true
    scene: myScene
}

resourceForm.presentationModel.mainScene = myScene;

var request: HttpRequest;

function startConsultaRequest(e:MouseEvent, url: String) {
    request = HttpRequest {

        location: url;

        // The content of a response can be accessed in the onInput callback function.
        // Be sure to close the input sream when finished with it in order to allow
        // the HttpRequest implementation to clean up resources related to this
        // request as promptly as possible.

        onInput: function(is: java.io.InputStream) {
            // use input stream to access content here.
            // can use input.available() to see how many bytes are available.
            try {
                var message = new String();
                //var item:menuItem;
                var item:String;

                while(is.available() > 0)
                    message += Character.toString(Character.valueOf(is.read()));

                var tokenizer : StringTokenizer = new StringTokenizer(message, ";");

                //myPopupMenu.deleteOptions();
                while (tokenizer.hasMoreTokens()) {
                    var texto : String = tokenizer.nextToken();
                    item = texto;
                    /*item = menuItem {
                        text: texto
                        customCall: optionSelected
                    };*/
                    if(javafx.util.Sequences.indexOf(slotComboBox.items, item) == -1) {
                        //insert item into myPopupMenu.content;
                        insert item into slotComboBox.items;
                    }
                    slotComboBox.skin.node.visible = (item.length() != 0);
                    slotComboBox.select(0);
                }
                //myPopupMenu.event = e;

            } finally {
                is.close();
            }
        }

        onException: function(ex: java.lang.Exception) {
            println("onException - exception: {ex.getClass()} {ex.getMessage()}");
        }

        onDone: function() {
            request.stop();
        }
    };
    request.start();
}


function startConfirmaRequest(texto:String):Void {
    request = HttpRequest {

        location: "http://localhost:8080/pruebasJava/Main?accion=confirma&locacion=resto&nroMesa={mesa}&horario={texto}";

        onInput: function(is: java.io.InputStream) {
            // use input stream to access content here.
            // can use input.available() to see how many bytes are available.

        }      

        onException: function(ex: java.lang.Exception) {
            println("onException - exception: {ex.getClass()} {ex.getMessage()}");
        }

        onDone: function() {
            println("onDone") ;
            request.stop();
        }
    };
    request.start();
}

function dayOfWeek(dayOfWeek:Integer):String {
    var days:String[]=["","Domingo","Lunes","Martes","Miercoles", "Jueves","Viernes","Sabado"];

    return days[dayOfWeek];
}