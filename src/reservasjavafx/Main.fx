package reservasjavafx;

import javafx.scene.*;
import javafx.scene.image.*;
import javafx.scene.input.*;
import javafx.scene.paint.*;
import javafx.scene.text.*;
import javafx.scene.shape.*;
import javafx.io.http.HttpRequest;
import java.util.StringTokenizer;
import javafx.scene.input.MouseEvent;
import java.lang.Void;
import reservasjavafx.CustomResource;
import calendarpicker.CalendarPicker;
import javafx.stage.Stage;
import javafx.stage.StageStyle;
import reservasjavafx.menu.popupMenu;
import javafx.scene.layout.VBox;
import javafx.data.pull.PullParser;
import javafx.scene.layout.HBox;
import java.util.Calendar;
import calendarpicker.ComboBox;
import java.io.InputStream;
import com.sun.javafx.data.pull.impl.StreamException;


import com.google.gson.Gson;
import reservasjavafx.GetConfigStruct;

import java.util.Date;

var gapX = 10;
var gapY = 40;
var TEXT_COLOR = Color.BLACK;

var RESOURCE = "resource";
var SLOTS = "slots";

var structConfig:GetConfigStruct = new GetConfigStruct();

var fromCal:Calendar = Calendar.getInstance();
var toCal:Calendar = Calendar.getInstance();
toCal.setTime(fromCal.getTime());
var range = bind structConfig.range on replace {
    fromCal.set(structConfig.range.fromYear, structConfig.range.fromMonth, structConfig.range.fromDate);
    toCal.set(structConfig.range.toYear, structConfig.range.toMonth, structConfig.range.toDate);
}



var mesa: Integer = 0;
var selected: String;

var stageDragInitialX:Number;
var stageDragInitialY:Number;

var coords :String;

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
    stroke: Color.web("#FF9900")
    fill: Color.WHITE
    containerWidth: bind imagen.image.width
    containerHeight: bind imagen.image.height
};

// the picker
var calendarPicker:CalendarPicker = CalendarPicker {
        mode: CalendarPicker.MODE_SINGLE
};

var currentCalendar:Calendar = bind calendarPicker.calendar on replace {
    findSlots();
}
function findSlots(){
    if(fromCal.equals(toCal) or fromCal.compareTo(currentCalendar)*toCal.compareTo(currentCalendar) > 0) {
        
        fromCal.setTime(currentCalendar.getTime());
        toCal.setTime(currentCalendar.getTime());
        toCal.add(Calendar.DATE, 30);
        doRequest("http://localhost:8080/ReservasServlet/Reserves?name=getConfig&resourceGroup=1&fromYear={fromCal.get(Calendar.YEAR)}&fromMonth={fromCal.get(Calendar.MONTH)}&fromDate={fromCal.get(Calendar.DATE)}&toYear={toCal.get(Calendar.YEAR)}&toMonth={toCal.get(Calendar.MONTH)}&toDate={toCal.get(Calendar.DATE)}", SLOTS);
    }

}

function setSlots() {
    var maxLength = 0;
    var item:String;
    for(slot in structConfig.slots) {
        if(slot.day.equalsIgnoreCase(dayOfWeek(currentCalendar.DAY_OF_WEEK))) {
            if(maxLength < slot.toString().length())
                maxLength = slot.toString().length();
            delete slot from slotComboBox.items;
            insert slot into slotComboBox.items;
        }
    }

    slotComboBox.skin.node.visible = (sizeof(slotComboBox.items) != 0);
    slotComboBox.skin.control.width = maxLength*7;
    slotComboBox.select(0);
}


var layout:Group = Group {};

doRequest("http://localhost:8080/ReservasServlet/Reserves?name=getLayout&resourceGroup=1", RESOURCE);

function x(x:Integer):Integer {
    return (x + imagen.x as Integer);
}

function y(y:Integer):Integer {
    return (y + imagen.y as Integer);
}

/*var resourceBean:ResourceBean = new ResourceBean();

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
};*/

function mousePressed(e:MouseEvent) {
        var recurso: CustomResource = e.node as CustomResource;

        if(e.button == MouseButton.PRIMARY) {
            java.lang.System.out.println("------------------");
            java.lang.System.out.println("{slotComboBox.selectedItem} --- {recurso.nroRecurso}");
            java.lang.System.out.println("------------------");
        }
}

/*function optionSelected(texto:String):Void {
        resourceBean.setRecurso("Mesa {mesa}");
        resourceBean.setFecha("01/01/2009");
        resourceBean.setHora("{texto}");
        resourceBean.setUsuario("Jr.");
        selected = texto;
        slideLeft.play();
        slideRight.stop();
        resourceForm.setVisibleErrWarnNodes(true);
}*/

/*var okButton:Button = Button {
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
};*/

var request: HttpRequest;

var slotComboBox : ComboBox = ComboBox {};
slotComboBox.select(0);
//slotComboBox.skin.node.visible = false;

var slotSelectedIndexChanged = bind slotComboBox.selectedIndex; // on change event
slotComboBox.skin.control.translateX = 300;

function startConsultaRequest(e:MouseEvent, url: String) {
    request = HttpRequest {

        location: url;

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

                while (tokenizer.hasMoreTokens()) {
                    var texto : String = tokenizer.nextToken();
                    item = texto;
                    delete item from slotComboBox.items;
                    insert item into slotComboBox.items;
                    slotComboBox.skin.node.visible = (sizeof(slotComboBox.items) != 0);
                    slotComboBox.select(0);
                }

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

 function doRequest(url:String, type:String) {
    request = HttpRequest {

        location: url;

        onInput: {
                if(type == RESOURCE)
                    parseResources
                else
                    parseSlots
                }

        onException: function(ex: java.lang.Exception) {
            println("onException - exception: {ex.getClass()} {ex.getMessage()}");
        }

        onDone: function() {
            if(type == SLOTS)
                setSlots();

            println("onDone");
            request.stop();
        }
    };
    request.start();
}

function parseSlots(is:InputStream):Void {
    try {
        var message = new String();

        while(is.available() > 0)
            message += Character.toString(Character.valueOf(is.read()));

        var gson:Gson = new Gson();
        structConfig = gson.fromJson(message, GetConfigStruct.class);

        
        
    } finally {
        is.close();
    }
}

function parseResources(is:InputStream):Void {
    delete layout.content;

    var parser = PullParser {
                    documentType:PullParser.JSON
                    input: is
    }

    insert imagen into layout.content;
    while(parser.event.type != PullParser.END_DOCUMENT) {
        if (parser.event.type == PullParser.START_ARRAY_ELEMENT) {
            var resource = parseResource(parser);
            insert resource into layout.content;
        }        
        try {
            parser.forward();
        } catch(ex:StreamException) {
        }

    }

    insert myPopupMenu into layout.content;
}

var hbox:HBox = HBox {
    content: [  Group {
                    content: [  calendarPicker,
                                slotComboBox    ]
                }
             ]
}

var dayText:Text = Text {
                        x: gapX+0
                        y:22
                        content: bind "{dayOfWeek(currentCalendar.get(Calendar.DAY_OF_WEEK))}, {currentCalendar.get(java.util.Calendar.DATE)}-{currentCalendar.get(java.util.Calendar.MONTH) + 1}-{currentCalendar.get(java.util.Calendar.YEAR)}";
                        fill: TEXT_COLOR
                   }

var vbox:VBox = VBox {
    content: [                  Rectangle {
                                    fill: Color.TRANSPARENT
                                    width: (imagen.image.width)+gapX*2
                                    height: gapY/2
                                },
                                dayText,
                                Rectangle {
                                    fill: Color.TRANSPARENT
                                    width: (imagen.image.width)+gapX*2
                                    height: gapY/2
                                },
                                hbox,
                                Rectangle {
                                    fill: Color.TRANSPARENT
                                    width: (imagen.image.width)+gapX*2
                                    height: gapY/2
                                },
                                layout,
                                Rectangle {
                                    fill: Color.TRANSPARENT
                                    width: (imagen.image.width)+gapX*2
                                    height: gapY/2
                                }
            ]
}



var background:HBox = HBox {
                    content: [  Rectangle {
                                    fill: Color.TRANSPARENT
                                    width: gapX
                                    height: 3*gapY
                                },
                                vbox,
                                Rectangle {
                                    fill: Color.TRANSPARENT
                                    width: gapX
                                    height: 3*gapY
                                }
                            ]
}

function parseResource(parser:PullParser):CustomResource {

    var type:String;
    var fillColor:Color;
    var strokeColor:Color;
    var nroRecurso:Integer;
    var points:Integer[];
    var x;
    var y;
    var scale = 1.0;
    var radX;
    var radY;
    var imagen;

    var evt = bind parser.event;

    while(evt.type != PullParser.END_DOCUMENT) {        
        parser.forward();        
        if(evt.type == PullParser.END_ARRAY_ELEMENT) {
            break;
        } else if (evt.type == PullParser.START_ARRAY_ELEMENT) {
            points = parsePoints(parser);
        } else if (evt.type == PullParser.START_VALUE) {
            parser.forward();
            if (evt.type == PullParser.TEXT) {
                    if(evt.name.equalsIgnoreCase("type")) {
                        if(evt.text.equalsIgnoreCase("ELLIPSE"))
                            type = CustomResource.ELLIPSE
                        else
                            type = CustomResource.POLYGON
                    } else
                    if(evt.name.equalsIgnoreCase("fill")) {
                            fillColor = Color.web(evt.text);
                    } else
                    if(evt.name.equalsIgnoreCase("stroke")) {
                            strokeColor = Color.web(evt.text);
                    } else
                    if(evt.name.equalsIgnoreCase("imagen")) {
                            imagen = evt.text;
                    }

            } else if (evt.type == PullParser.INTEGER) {
                    if(evt.name.equalsIgnoreCase("nroRecurso")) {
                            nroRecurso = evt.integerValue
                    } else
                    if(evt.name.equalsIgnoreCase("fill")) {
                            fillColor = Color.web("WHITE", evt.integerValue);
                    } else
                    if(evt.name.equalsIgnoreCase("stroke")) {
                            strokeColor = Color.web("WHITE", evt.integerValue);
                    } else
                    if(evt.name.equalsIgnoreCase("x")) {
                            x = evt.integerValue;
                    } else
                    if(evt.name.equalsIgnoreCase("y")) {
                            y = evt.integerValue;
                    } else
                    if(evt.name.equalsIgnoreCase("radX")) {
                            radX = evt.integerValue;
                    } else
                    if(evt.name.equalsIgnoreCase("radY")) {
                            radY = evt.integerValue;
                    }
            } else if (evt.type == PullParser.TRUE) {
            } else if (evt.type == PullParser.NUMBER) {
                    if(evt.name.equalsIgnoreCase("fill")) {
                            fillColor = Color.web("WHITE", evt.numberValue);
                    } else
                    if(evt.name.equalsIgnoreCase("stroke")) {
                            strokeColor = Color.web("WHITE", evt.numberValue);
                    } else
                    if(evt.name.equalsIgnoreCase("scale")) {
                            scale = evt.numberValue;
                    }

            }
        }
    }

    return CustomResource {
                    type: type
                    fill: fillColor
                    stroke: strokeColor
                    points: points
                    nroRecurso:nroRecurso
                    x: x
                    y: y
                    radX: radX
                    radY: radY
                    onMouseClicked:mousePressed
                    image: imagen
                    scale: scale
    };
}

function parsePoints(parser:PullParser):Integer[] {
    var points:Integer[];

    while(parser.event.type != PullParser.END_DOCUMENT) {        
        parser.forward();
        if(parser.event.type == PullParser.END_ARRAY) {
            break;
        } else if (parser.event.type == PullParser.START_VALUE) {
            parser.forward();
        }
        if (parser.event.type == PullParser.INTEGER) {
                if("x".equals(parser.event.name))
                    insert x(parser.event.integerValue) into points
                else
                if("y".equals(parser.event.name))
                    insert y(parser.event.integerValue) into points;
        }
    }
    
    return points
}

function dayOfWeek(dayOfWeek:Integer):String {
    var days:String[]=["","Domingo","Lunes","Martes","Miercoles", "Jueves","Viernes","Sabado"];

    return days[dayOfWeek];
}

var myScene:Scene = Scene {
    width: imagen.image.width+gapX*2;
    height: gapY*3+ 150+ imagen.image.height
    fill: Color.web("#FF9900")
    content: [background/*, resourceForm, okButton, cancelButton*/]
}


// show it all on screen
var stage:Stage = Stage {
    style: StageStyle.TRANSPARENT
    scene: myScene
}

//resourceForm.presentationModel.mainScene = myScene;