/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.crawlConfig.objets;

import java.util.ArrayList;

/**
 *
 * @author nacho
 */
public class State {

    public static ArrayList<String> getCrawlConfigStates() {
        ArrayList<String> states = new ArrayList<String>();

        states.add("Generada");
        states.add("Publicada");
        states.add("Despublicada");
        states.add("Anulada");

        return states;
    }

}
