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

    public static final String DAY = "Day";
    public static final String WEEK = "Week";
    public static final String MONTH = "Month";
    public static final String ALL = "All";

    public static ArrayList<String> getPeriodos() {
        ArrayList<String> periodos = new ArrayList<String>();

        periodos.add(DAY);
        periodos.add(WEEK);
        periodos.add(MONTH);
        periodos.add(ALL);

        return periodos;
    }

}
