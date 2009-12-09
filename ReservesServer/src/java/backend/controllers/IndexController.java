/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package backend.controllers;

import java.util.Vector;
import org.zkoss.zhtml.Messagebox;
import org.zkoss.zk.ui.Executions;
import org.zkoss.zk.ui.event.Event;
import org.zkoss.zul.api.Window;

/**
 *
 * @author nacho
 */
public class IndexController extends BaseController {

    Vector<Window> prueba = new Vector<Window>();
    //Component window1;

    public IndexController() {
        super(false);
    }


    public void onClick$citiesMenu (Event event) {
        try {
            prueba.add((Window) Executions.createComponents("/citiesWindow.zul", null, null));
        } catch (Exception ex) {
            try {
                ex.printStackTrace();
                Messagebox.show("La ventana ya se encuentra abierta", "Error", Messagebox.OK, Messagebox.ERROR);
            } catch (InterruptedException ex1) {
                ex1.printStackTrace();
            }
        }
    }

    public void onClick$suppliersMenu (Event event) {
        try {
            prueba.add((Window) Executions.createComponents("/suppliersWindow.zul", null, null));
        } catch (Exception ex) {
            try {
                ex.printStackTrace();
                Messagebox.show("La ventana ya se encuentra abierta", "Error", Messagebox.OK, Messagebox.ERROR);
            } catch (InterruptedException ex1) {
                ex1.printStackTrace();
            }
        }
    }

    public void onClick$locationsMenu (Event event) {
        try {
            prueba.add((Window) Executions.createComponents("/locationsWindow.zul", null, null));
        } catch (Exception ex) {
            try {
                ex.printStackTrace();
                Messagebox.show("La ventana ya se encuentra abierta", "Error", Messagebox.OK, Messagebox.ERROR);
            } catch (InterruptedException ex1) {
                ex1.printStackTrace();
            }
        }
    }

}
