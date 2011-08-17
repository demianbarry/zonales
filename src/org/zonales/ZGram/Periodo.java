/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.ZGram;

import java.util.ArrayList;

/**
 *
 * @author nacho
 */
public class Periodo {

    public static final String DAY = "day";
    public static final String WEEK = "week";
    public static final String MONTH = "month";
    public static final String ALL = "all";

    public static ArrayList<String> getPeriodos() {
        ArrayList<String> periodos = new ArrayList<String>();

        periodos.add(DAY);
        periodos.add(WEEK);
        periodos.add(MONTH);
        periodos.add(ALL);

        return periodos;
    }

}
