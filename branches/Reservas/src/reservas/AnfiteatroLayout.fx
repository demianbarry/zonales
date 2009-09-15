/*
 * AnfiteatroLayout.fx
 *
 * Created on 14-sep-2009, 20:40:34
 */

package reservas;

import javafx.scene.CustomNode;

import javafx.scene.Group;
import javafx.scene.Node;
import javafx.scene.shape.Rectangle;
import javafx.scene.paint.Color;
import java.lang.Math;

/**
 * @author nacho
 */

public class AnfiteatroLayout extends CustomNode {

        var espacioEntreButacas: Number = 3;
        var tamanioButaca: Number = 15;
        var columnas: Number = 19;
        var filas: Number = 10;

        var x: Number = espacioEntreButacas;
        var y: Number = espacioEntreButacas;

        var a: Number = 270;
        var b: Number = 30;
        var radio: Number = 100;
        var angulo: Number = (Math.PI - (Math.PI / ((columnas - 1) * 2)));

        var vacia1: Number = 7;
        var vacia2: Number = 13;

        var butacas: ButacaTeatro[];

        public override function create(): Node {
                var rectangle: Rectangle;

                for (fila in [1..filas]){
                    for (columna in [1..columnas]) {
                        if (columna != vacia1) {
                            if (columna != vacia2) {
                                insert ButacaTeatro{
                                    x: (a + (radio * Math.cos(angulo))) + (tamanioButaca / 2)
                                    y: (b + (radio * (Math.sin(angulo) * (-1))))
                                    tamanioButaca: tamanioButaca
                                    fila: fila
                                    nroButaca: columna
                                    color: Color.RED
                                    selectedColor: Color.GRAY
                                    overColor: Color.ORANGE
                                    anguloRotacion: (270 - (((angulo + (Math.PI / 36)) * 180) / Math.PI))
                                } into butacas;
                            }
                        }
                        angulo = angulo + (Math.PI / (columnas - 1))
                    }
                    vacia1 = vacia1 + 1;
                    vacia2 = vacia2 + 2;
                    radio = radio + tamanioButaca + espacioEntreButacas;
                    columnas = columnas + 3;
                    angulo = (Math.PI - (Math.PI / ((columnas - 1) * 2)));
                    
                }

                return Group {
                        content: butacas
                };
        }

}
