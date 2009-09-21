/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package pruebas;

import java.util.ArrayList;
import java.util.Hashtable;
import java.util.List;

/**
 *
 * @author nacho
 */
public class Resto {

    Hashtable<Integer, List> mesas;

    public Resto(int cantMesas) {
        mesas = new Hashtable<Integer, List>();
        List<Integer> valores;
        for (int i = 0; i < cantMesas; i++) {
            valores = new ArrayList();
            valores.add(0);
            valores.add(0);
            valores.add(0);
            valores.add(0);
            mesas.put(i, valores);
        }
    }

    public List getLibres(int nroMesa) {
        return mesas.get(nroMesa);
    }

    public void setLibres(int nroMesa, List valor) {
        this.mesas.put(nroMesa, valor);
    }

    public void mostrar() {
        System.out.print(mesas);
    }

}

