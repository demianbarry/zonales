/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package backend.controllers;

import org.zkoss.zk.ui.Executions;
import org.zkoss.zk.ui.event.Event;
import org.zkoss.zul.api.Window;

/**
 *
 * @author nacho
 */
public class IndexController extends BaseController {

    Window prueba;
    //Component window1;

    public IndexController() {
        super(false);
    }


    public void onClick$citiesMenu (Event event) {
       prueba = (Window) Executions.createComponents("/citiesWindow.zul", null, null);
    }

    public void inClick$suppliersMenu (Event event) {
        
    }

}
