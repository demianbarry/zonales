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

    public static final String GENERATED = "Generated";
    public static final String PUBLISHED = "Published";
    public static final String UNPUBLISHED = "Unpublished";
    public static final String VOID = "Void";

    public static ArrayList<String> getCrawlConfigStates() {
        ArrayList<String> states = new ArrayList<String>();

        states.add(GENERATED);
        states.add(PUBLISHED);
        states.add(UNPUBLISHED);
        states.add(VOID);

        return states;
    }

}
