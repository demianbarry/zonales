/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package pruebas;

/**
 *
 * @author nacho
 */
public class Teatro {
    Integer matriz[][][];

    public Teatro(int filas, int columnas) {
        matriz = new Integer[filas][columnas][2];
        Integer[] valores = {0, 0};
        for (int i = 0; i < filas; i++) {
            for (int j= 0; j < columnas; j++) {
                matriz[i][j] = valores;
            }
        }
    }

    public Integer[] getLibres(int fila, int columna) {
        return matriz[fila][columna];
    }

    public void setLibres(int fila, int columna, Integer[] valor) {
        this.matriz[fila][columna] = valor;
    }


}
