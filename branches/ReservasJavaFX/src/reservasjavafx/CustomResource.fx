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
    public var scale: Number;
    public var radX: Number;
    public var radY: Number;
    public var type: String;
    public var image: String;

    //var applet: Applet = FX.getArgument("javafx.applet") as Applet;
    //var jsObject = new JavaScriptUtil(applet);

    var myImage:Image = Image {  url: "{__DIR__}images/{image}"};
    var resource : Group;

    var imageWidth = bind myImage.width;
    var imageHeight = bind myImage.height;

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
                                image: myImage
                                x: bind x  - imageWidth/2
                                y: bind y  - imageHeight/2
                                scaleX: scale
                                scaleY: scale
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
                                image: myImage;
                                x: bind getCenterX(points) - imageWidth/2
                                y: bind getCenterY(points) - imageHeight/2
                                scaleX: scale
                                scaleY: scale
                        }]
                    }
                    }]
                }
    }

    function getCenterX(points:Integer[]):Integer {
        if(sizeof(points) != 0) {
            var max = points[0];
            var min = points[0];
            var average = 0;
            var count = 0;
            for(i in points) {
                if(count++ mod 2 == 0) {
                    if(i > max)
                        max = i;
                    if(i < min)
                        min = i;
                    average += i;
                }
            }
            return average/(sizeof(points)/2)
        }
        return 0;
    }

    function getCenterY(points:Integer[]):Integer {
        if(sizeof(points) != 0) {
            var max = points[1];
            var min = points[1];
            var average = 0;
            var count = 0;
            for(i in points) {
                if(count++ mod 2 != 0) {
                    if(i > max)
                        max = i;
                    if(i < min)
                        min = i;
                    average += i;
                }
            }
            return average/(sizeof(points)/2)
        }
        return 0;
    }
}
