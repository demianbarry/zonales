/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package services;

import java.util.Date;

/**
 *
 * @author Nosotros
 */
public class Slots {

    int id;
    String day;
    Date from;
    Date to;
    Date min;
    Date max;
    Date steep;
    Date tolerance;

    public Slots(int id, String day, Date from, Date to, Date min, Date max, Date steep, Date tolerance) {
        this.id = id;
        this.day = day;
        this.from = from;
        this.to = to;
        this.min = min;
        this.max = max;
        this.steep = steep;
        this.tolerance = tolerance;
    }
    

}
