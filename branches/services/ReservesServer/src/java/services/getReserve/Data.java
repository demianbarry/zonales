/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package services.getReserve;

import java.sql.Time;
import java.util.Date;

/**
 *
 * @author Nosotros
 */
public class Data {

    int id;
    Date datetimeReserve;
    Date datetimeRealization;
    Time duration;
    Date expiry;

    public Data() {
    }

    public Data(int id, Date datetimeReserve, Date datetimeRealization, Time duration, Date expiry) {
        this.id = id;
        this.datetimeReserve = datetimeReserve;
        this.datetimeRealization = datetimeRealization;
        this.duration = duration;
        this.expiry = expiry;
    }

    public Date getDatetimeRealization() {
        return datetimeRealization;
    }

    public void setDatetimeRealization(Date datetimeRealization) {
        this.datetimeRealization = datetimeRealization;
    }

    public Date getDatetimeReserve() {
        return datetimeReserve;
    }

    public void setDatetimeReserve(Date datetimeReserve) {
        this.datetimeReserve = datetimeReserve;
    }

    public Time getDuration() {
        return duration;
    }

    public void setDuration(Time duration) {
        this.duration = duration;
    }

    public Date getExpiry() {
        return expiry;
    }

    public void setExpiry(Date expiry) {
        this.expiry = expiry;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

}
