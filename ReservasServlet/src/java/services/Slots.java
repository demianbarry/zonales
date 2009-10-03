/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package services;

import java.sql.Time;
import java.sql.Timestamp;

/**
 *
 * @author Nosotros
 */
public class Slots {

    int id;
    String day;
    Time from;
    Time to;
    Timestamp min;
    Timestamp max;
    Timestamp steep;
    Timestamp tolerance;

    public Slots(int id, String day, Time from, Time to, Timestamp min, Timestamp max, Timestamp steep, Timestamp tolerance) {
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
