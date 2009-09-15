/*
 * PlanoTeatroRectangular.fx
 *
 * Created on 14-sep-2009, 23:34:33
 */

package reservas;


import javafx.scene.CustomNode;
import javafx.scene.Group;
import javafx.scene.Node;

import javafx.scene.paint.Color;
import javafx.scene.shape.Rectangle;
import javafx.scene.transform.Rotate;
import javafx.scene.shape.Polygon;
import javafx.scene.shape.Line;

/**
 * @author nacho
 */

public class PlanoTeatroRectangular extends CustomNode {
    
    public-init var x: Number;
    public-init var y: Number;
    public-init var ancho: Number;
    public-init var largo: Number;
    public-init var color: Color;
    public-init var tamanioEscenario: Number;
    public-init var anguloRotacion: Number;

    var teatro : Group;

    public override function create(): Node {
        teatro = createTeatro(x, y, ancho, largo, color, tamanioEscenario, anguloRotacion);
        return Group {
            content: [
                teatro
            ]
        };
    }

    function createTeatro (x: Number, y: Number, ancho: Number, largo: Number, color: Color, tamanioEscenario: Number, anguloRotacion: Number) {

        Group {
            transforms: Rotate {
                angle: anguloRotacion
                pivotX: x
                pivotY: y
            }

            content: [
                Rectangle {
                    x: x + 1
                    y: y
                    width: ancho
                    height: largo
                    fill: color
                    stroke: Color.BLACK
                    strokeWidth: 2
                }
                Polygon {
                    stroke: Color.BLACK
                    strokeWidth: 2
                    fill: color
                    points: [
                        x + 2 , y + 2,
                        (x - 2 + ancho), y + 2,
                        (x + ancho - (tamanioEscenario / 3)), (y + tamanioEscenario),
                        (x + (tamanioEscenario / 3)), (y + tamanioEscenario)
                    ]
                }
                Group {
                    transforms: Rotate {
                        angle: -17
                        pivotX: tamanioEscenario / 9
                        pivotY: tamanioEscenario / 3
                    }

                    content: [
                        Rectangle {
                            x: (tamanioEscenario / 9) + 1
                            y: (tamanioEscenario / 3) + 1
                            width: tamanioEscenario / 3
                            height: tamanioEscenario / 3
                            fill: color
                            stroke: Color.BLACK
                            strokeWidth: 2
                        }
                        Line {
                            startX: (2 * (tamanioEscenario / 9)) + 1
                            startY: (tamanioEscenario / 3) + 1
                            endX: (2 * (tamanioEscenario / 9)) + 1
                            endY: (2 * (tamanioEscenario / 3)) + 1
                            strokeWidth: 1
                            stroke: Color.BLACK
                        }
                        Line {
                            startX: (3 * (tamanioEscenario / 9)) + 1
                            startY: (tamanioEscenario / 3) + 1
                            endX: (3 * (tamanioEscenario / 9)) + 1
                            endY: (2 * (tamanioEscenario / 3)) + 1
                            strokeWidth: 1
                            stroke: Color.BLACK
                        }
                    ]

                }
                Group {
                    transforms: Rotate {
                        angle: -163
                        pivotX: ancho - (2 * (tamanioEscenario / 9))
                        pivotY: 2 * (tamanioEscenario / 3)
                    }

                    content: [
                        Rectangle {
                            x: ancho - (2 * (tamanioEscenario / 9)) + 1
                            y: (2 * (tamanioEscenario / 3)) + 1
                            width: tamanioEscenario / 3
                            height: tamanioEscenario / 3
                            fill: color
                            stroke: Color.BLACK
                            strokeWidth: 2
                        }
                        Line {
                            startX: ancho - (tamanioEscenario / 9) + 1
                            startY: (2 * (tamanioEscenario / 3)) + 1
                            endX: ancho - (tamanioEscenario / 9) + 1
                            endY: (3 * (tamanioEscenario / 3)) + 1
                            strokeWidth: 1
                            stroke: Color.BLACK
                        }
                        Line {
                            startX: ancho + 1
                            startY: (2 * (tamanioEscenario / 3)) + 1
                            endX: ancho +  1
                            endY: (3 * (tamanioEscenario / 3)) + 1
                            strokeWidth: 1
                            stroke: Color.BLACK
                        }
                    ]

                }
            ]

        }
    }

}
