/*
 * TeatroLayout1.fx
 *
 * Created on 01/09/2009, 03:46:38 PM
 */

package reservas;

import javafx.scene.CustomNode;

import javafx.scene.Group;
import javafx.scene.Node;
import javafx.scene.shape.Rectangle;
import javafx.scene.paint.Color;


/**
 * @author Administrador
 */

 public class TeatroLayoutRectangular extends CustomNode {

        var espacioEntreButacas: Number = 3;
        var tamanioButaca: Number = 15;
        var columnas: Number = 12;
        var filas: Number = 18;
        var tamanioEscenario = 80;

        var x: Number = espacioEntreButacas * 2;
        var y: Number = (espacioEntreButacas + tamanioEscenario);

        var butacas: ButacaTeatro[];

        public override function create(): Node {
                var plano: PlanoTeatroRectangular;

                for (fila in [1..filas]){
                    for (columna in [1..columnas]) {
                        insert ButacaTeatro{
                            x: x + (columna * (tamanioButaca + espacioEntreButacas))
                            y: y + (fila * (tamanioButaca + espacioEntreButacas))
                            tamanioButaca: tamanioButaca
                            fila: fila
                            nroButaca: columna
                            color: Color.RED
                            selectedColor: Color.GRAY
                            overColor: Color.ORANGE
                            anguloRotacion:0
                        } into butacas;
                    }
                }

                return Group {
                        content: [
                                plano = PlanoTeatroRectangular {
                                    x: 0;
                                    y: 0;
                                    ancho: 260;
                                    largo: 450;
                                    color: Color.LIGHTGREY;
                                    tamanioEscenario: tamanioEscenario;
                                    anguloRotacion: 0;
                                }
                                butacas
                        ]
                };
        }

 }


