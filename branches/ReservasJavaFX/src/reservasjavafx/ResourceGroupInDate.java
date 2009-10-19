/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package reservasjavafx;

import java.util.Calendar;
import java.util.Date;

/**
 *
 * @author Nosotros
 */
public class ResourceGroupInDate {

    Integer resourceGruopID;
    int dateYear;
    int dateMonth;
    int dateDay;
    String hour;
    String duration;

    public ResourceGroupInDate(Integer resourceGruopID, Calendar date, Date hour, Calendar duration) {
        this.resourceGruopID = resourceGruopID;
        this.dateYear = date.get(Calendar.YEAR);
        this.dateMonth = date.get(Calendar.MONTH) + 1;
        this.dateDay = date.get(Calendar.DATE);
        this.hour = hour.toString();
        this.duration = ((Integer)duration.get(Calendar.HOUR_OF_DAY)).toString();
    }

}
