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

    public static final String GENERATED = "generated";
    public static final String COMPILED = "compiled";
    public static final String NO_COMPILED = "no-compiled";
    public static final String PUBLISHED = "published";
    public static final String UNPUBLISHED = "unpublished";
    public static final String STANDBY = "stand-by";
    public static final String VOID = "void";

    public static ArrayList<String> getCrawlConfigStates() {
        ArrayList<String> states = new ArrayList<String>();

        states.add(GENERATED);
        states.add(PUBLISHED);
        states.add(UNPUBLISHED);
        states.add(COMPILED);
        states.add(STANDBY);
        states.add(VOID);

        return states;
    }

}
