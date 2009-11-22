/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package backend.controllers;

import org.zkoss.zk.ui.Component;
import org.zkoss.zk.ui.event.Event;
import org.zkoss.zul.api.Window;

/**
 *
 * @author nacho
 */
public class IndexController extends BaseController {

    Window window1;
    //Component window1;

    public IndexController() {
        super(true);
    }


    public void onClick$recursosMenu (Event event) {
        window1.setVisible(true);
    }

    public void onClose$window1 (Event event) {
        System.out.println("----->Cerré la ventana");
    }

}
