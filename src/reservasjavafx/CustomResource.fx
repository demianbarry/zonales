/*
 * CustomResource.fx
 *
 * Created on 21/09/2009, 14:46:00
 */

package reservasjavafx;

import javafx.scene.CustomNode;
import javafx.scene.Group;
import javafx.scene.Node;

import javafx.scene.paint.Color;

import javafx.scene.*;
import javafx.scene.paint.*;
import javafx.scene.shape.*;

import javafx.scene.shape.Polygon;


public def ELLIPSE:String = "Ellipse";
public def POLYGON:String = "Polygon";
/**
 * @author Nosotros
 */

public class CustomResource extends CustomNode {

    public-init var points: Number[];
    public-init var nroRecurso: Number;
    public-init var fill: Color;
    public-init var stroke: Color;
    public-init var x: Float;
    public-init var y: Float;
    public-init var radX: Float;
    public-init var radY: Float;
    public-init var type: String;

    //var applet: Applet = FX.getArgument("javafx.applet") as Applet;
    //var jsObject = new JavaScriptUtil(applet);


    var resource : Group;

    public override function create(): Node {
        resource = createResource(points, nroRecurso, fill, stroke);
        return Group {
            content: [
                resource
            ]
        };
    }

    function createResource (points: Number[], nroRecurso: Number, fillColor: Color, strokeColor: Color) {
        Group {
            content: [
                if(type.equals(ELLIPSE)) {
                    Ellipse {
                        cursor: Cursor.HAND
                        centerX: x
                        centerY: y
                        radiusX: radX
                        radiusY: radY
                        fill: fillColor
                        stroke: strokeColor
                    }
                } else {
                    Polygon {
                        cursor: Cursor.HAND
                        points: points
                        fill: fillColor
                        stroke: strokeColor
                    }
                }
            ]
        }
    }
}
