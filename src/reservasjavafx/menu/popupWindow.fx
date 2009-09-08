package reservasjavafx.menu;

import javafx.scene.CustomNode;
import javafx.scene.Node;
import javafx.scene.Group;
import javafx.scene.paint.*;
import javafx.scene.shape.*;
import javafx.scene.input.*;
import javafx.scene.text.*;
import javafx.geometry.*;
import javafx.scene.effect.DropShadow;
import javafx.animation.*;
import java.lang.Math;

public class popupWindow extends CustomNode {

    // espacio hacia el borde del menu
    public var padding=4.0;
    // espacio vertical entre opciones
    public var verticalSpacing=2.0;

    // ancho del menu
    var menuWidth=100.0;
    // alto del menu
    var menuHeight=50.0;
    // escala del menu
    var scale=1.0;

    // font del menu
    public var font:Font = Font{ size: 14};
    // color de fondo del menu
    public var fill:Color = Color.WHITE;
    // color del borde del menu
    public var borderColor:Color = Color.BLACK;
    // color del borde del menu
    public var stroke:Color = Color.BLACK;
    // ancho del borde del menu
    public var borderWidth:Number = 1;
    // color para destacar fondo de opcion
    public var highlight:Color = Color.LIGHTBLUE;
    // color para destacar texto de opcion
    public var highlightStroke:Color = Color.BLUE;
    // indica si tiene sombra
    public var shadow:Boolean=true;
    // sombra horizontal
    public var shadowX=5.0;
    // sombra horizontal
    public var shadowY=5.0;
    // esquinas redondeadas
    public var corner=0.0;
    // gradiente opcional
    public var gradient:LinearGradient=null;
    // animacion in/out
    public var animate=false;

    // opacidad
    override public var opacity=1.0;
    // giro del menu
    override public var rotate=0.0;


    // opciones del menu popup
    public var content:String=null;

    // animacion para mostrar menu
    var appear=Timeline {
        repeatCount:1
        keyFrames: [
            at (0s) { scale => 0.0 }
            at (0.2s) { scale => 1.0 tween Interpolator.EASEIN }
        ]
    };

    // crea el componente del menu flotante
    override function create():Node {

        // no es visible al crearlo
        this.visible=false;

        Group {
//            cache:true
            rotate: bind rotate
            scaleX: bind scale
            scaleY: bind scale
            opacity: bind opacity
            content: [
                // rectangulo de fondo del menu flotante
                Rectangle {
                    // posiciona en la ubicacion del mouse
                    x: 0
                    y: 0
                    arcHeight: bind corner
                    arcWidth: bind corner
                    // asigna tamano dependiendo de las opciones
                    width: bind menuWidth + padding * 2
                    height: bind menuHeight + padding
                    // parametros para pintar el fondo
                    fill: bind if (gradient != null) then gradient else fill
                    stroke: bind borderColor
                    strokeWidth: bind borderWidth
                    // si la sobra esta activa, la agrega, sino la omite
                    effect: bind if (shadow) {
                        DropShadow {
                            offsetX:bind shadowX
                            offsetY:bind shadowY
                        }
                    }
                    else null;
                },
                // agrega las opciones pregeneradas
                                        Rectangle {
                            // bloquea el click para los elementos que estan debajo
                            blocksMouse:true
                            fill:Color.TRANSPARENT
                            width: 100
                            height: 100
                            onMousePressed: function(e) {
                                // con el boton principal del mouse
                                if (e.button==MouseButton.PRIMARY) {
                                    // ejecuta la funcion asociada al boton
                                    // traspasando el evento del mouse (posicion del mouse)
                                    // oculta el menu flotante
                                    this.visible=false;
                                }
                            };
                        },
                        Text {
                            content: bind content;
                            fill: Color.BLACK
                            x: 0
                            y: 0
                            font: bind font
                            textOrigin: TextOrigin.TOP

                        }
            ]
        }
    }

}
