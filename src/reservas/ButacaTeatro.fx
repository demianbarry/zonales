/*
 * Butaca.fx
 *
 * Created on 01/09/2009, 09:04:17 PM
 */

package reservas;

import java.applet.Applet;

import javafx.scene.CustomNode;
import javafx.scene.Group;
import javafx.scene.Node;

import javafx.scene.paint.Color;
import javafx.scene.shape.Line;
import javafx.scene.shape.Rectangle;
import javafx.scene.transform.Rotate;

import javafx.scene.input.MouseEvent;


/**
 * @author Administrador
 */

public class ButacaTeatro extends CustomNode {

    public-init var tamanioButaca: Number;
    public-init var x: Number;
    public-init var y: Number;
    public-init var fila: Number;
    public-init var nroButaca: Number;
    public-init var color: Color;
    public-init var selectedColor: Color;
    public-init var overColor: Color;
    public-init var anguloRotacion: Number;

    var applet: Applet = FX.getArgument("javafx.applet") as Applet;
    var jsObject = new JavaScriptUtil(applet);


    var butaca : Group;

    public override function create(): Node {
        butaca = createButaca(x, y, tamanioButaca, fila, nroButaca);
        return Group {
            content: [
                butaca
            ]
        };
    }

    function createButaca (x: Number, y: Number, tamanioButaca: Number, fila: Number, nroButaca: Number) {
        
        var rectangle : Rectangle;

        Group {
            transforms: Rotate {
                angle: anguloRotacion
                pivotX: x
                pivotY: y
            }

            content: [
                rectangle = Rectangle {
                    x: x
                    y: y
                    width: tamanioButaca
                    height: tamanioButaca
                    arcWidth: tamanioButaca / 3
                    arcHeight: tamanioButaca / 3
                    fill: color
                    stroke: Color.BLACK
                    strokeWidth: 1
                }
                Line {
                    startX: x
                    startY: y + (tamanioButaca - 5)
                    endX: (x + tamanioButaca)
                    endY: y + (tamanioButaca - 5)
                    strokeWidth: 1
                    stroke: Color.BLACK
                }
            ]
            onMouseEntered: function( e: MouseEvent ):Void {
                if (rectangle.fill != selectedColor) {
                    rectangle.fill = overColor
                }
            }
            onMouseExited: function( e: MouseEvent ):Void {
                if (rectangle.fill != selectedColor) {
                    rectangle.fill = color
                }
            }
            onMouseClicked: function( e: MouseEvent ):Void {
                if (rectangle.fill != selectedColor) {
                    rectangle.fill = selectedColor;
                    jsObject.call("showUbicacion", ["Fila: {fila.intValue()} Butaca: {nroButaca.intValue()}, Reserva confirmada"]);
                } else {
                    rectangle.fill = color;
                    jsObject.call("showUbicacion", ["Fila: {fila.intValue()} Butaca: {nroButaca.intValue()}, Reserva cancelada"]);
                }
            }
        }
    }


}
