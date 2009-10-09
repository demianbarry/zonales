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

import javafx.scene.image.Image;
import javafx.scene.image.ImageView;

public def ELLIPSE:String = "Ellipse";
public def POLYGON:String = "Polygon";
/**
 * @author Nosotros
 */

public class CustomResource extends CustomNode {

    public var points: Integer[];
    public var nroRecurso: Integer;
    public var fill: Color;
    public var stroke: Color;
    public var x: Number;
    public var y: Number;
    public var radX: Number;
    public var radY: Number;
    public var type: String;
    public var image: String;

    //var applet: Applet = FX.getArgument("javafx.applet") as Applet;
    //var jsObject = new JavaScriptUtil(applet);


    var resource : Group;

    public override function create(): Node {
        return createResource(points, nroRecurso, fill, stroke);
    }

    function createResource (points: Number[], nroRecurso: Number, fillColor: Color, strokeColor: Color) {
                Group { content: [
                    if(type.equals(ELLIPSE)) {
                        Group { content: [
                        Ellipse {
                            cursor: Cursor.HAND
                            centerX: x
                            centerY: y
                            radiusX: radX
                            radiusY: radY
                            fill: fillColor
                            stroke: strokeColor
                        },
                        ImageView {
                                image: Image {  url: "{__DIR__}images/{image}"
                                             }
                                x: bind x
                                y: bind y
                        }]
                        }
                    } else {
                        Group { content: [
                        Polygon {
                            cursor: Cursor.HAND
                            points: points
                            fill: fillColor
                            stroke: strokeColor
                        },
                        ImageView {
                                image: Image {  url: "{__DIR__}images/{image}"
                                             }
                                x: bind getCenterX(points)
                                y: bind getCenterY(points)
                        }]
                    }
                    }]
                }
    }

    function getCenterX(points:Integer[]):Integer {
        if(sizeof(points) != 0) {
            var average = 0;
            var count = 0;
            for(i in points) {
                java.lang.System.out.println("X-{count mod 2}---{count}---{i}");
                if(count++ mod 2 == 0) {
                    average += i;
                }
            }
            java.lang.System.out.println("=====================");
            java.lang.System.out.println("{average/(sizeof(points)/2)}---{sizeof(points)}--------{image}");
            java.lang.System.out.println("=====================");
            return average/(sizeof(points)/2)
        }
        return 0;
    }

    function getCenterY(points:Integer[]):Integer {
        if(sizeof(points) != 0) {
            var average = 0;
            var count = 0;
            for(i in points) {
                java.lang.System.out.println("Y-{count mod 2}---{count}---{i}");
                if(count++ mod 2 != 0) {
                    average += i;
                }
            }
            java.lang.System.out.println("=====================");
            java.lang.System.out.println("{average/(sizeof(points)/2)}----{sizeof(points)}-------{image}");
            java.lang.System.out.println("=====================");
            return average/(sizeof(points)/2)
        }
        return 0;
    }
}
