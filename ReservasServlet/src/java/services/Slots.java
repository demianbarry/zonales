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

    public String getDay() {
        return day;
    }

    public void setDay(String day) {
        this.day = day;
    }

    public Date getFrom() {
        return from;
    }

    public void setFrom(Date from) {
        this.from = from;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public Date getMax() {
        return max;
    }

    public void setMax(Date max) {
        this.max = max;
    }

    public Date getMin() {
        return min;
    }

    public void setMin(Date min) {
        this.min = min;
    }

    public Date getSteep() {
        return steep;
    }

    public void setSteep(Date steep) {
        this.steep = steep;
    }

    public Date getTo() {
        return to;
    }

    public void setTo(Date to) {
        this.to = to;
    }

    public Date getTolerance() {
        return tolerance;
    }

    public void setTolerance(Date tolerance) {
        this.tolerance = tolerance;
    }

}
