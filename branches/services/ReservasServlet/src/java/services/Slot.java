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
public class Slot {

    int id;
    String day;
    String from;
    String to;
    String min;
    String max;
    String steep;
    String tolerance;
    String alias;

    public Slot(int id, String day, Date from, Date to, Date min, Date max, Date steep, Date tolerance, String alias) {
        this.id = id;
        this.day = day;
        this.from = from.toString();
        this.to = to.toString();
        this.min = min.toString();
        this.max = max.toString();
        this.steep = steep.toString();
        this.tolerance = tolerance.toString();
        this.alias = alias;
    }

    public String getDay() {
        return day;
    }

    public void setDay(String day) {
        this.day = day;
    }

    public String getFrom() {
        return from;
    }

    public void setFrom(String from) {
        this.from = from;
    }

    public String getMax() {
        return max;
    }

    public void setMax(String max) {
        this.max = max;
    }

    public String getMin() {
        return min;
    }

    public void setMin(String min) {
        this.min = min;
    }

    public String getSteep() {
        return steep;
    }

    public void setSteep(String steep) {
        this.steep = steep;
    }

    public String getTo() {
        return to;
    }

    public void setTo(String to) {
        this.to = to;
    }

    public String getTolerance() {
        return tolerance;
    }

    public void setTolerance(String tolerance) {
        this.tolerance = tolerance;
    }

    public String getAlias() {
        return alias;
    }

    public void setAlias(String alias) {
        this.alias = alias;
    }

}
