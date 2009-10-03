/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package services;

import java.util.Calendar;

/**
 *
 * @author Nosotros
 */
public class Range {

    Integer resourceGruopID;
    int fromYear;
    int fromMonth;
    int fromDate;
    int toYear;
    int toMonth;
    int toDate;

    public Range(Integer resourceGruopID, Calendar from, Calendar to) {
        this.resourceGruopID = resourceGruopID;
        this.fromYear = from.get(Calendar.YEAR);
        this.fromMonth = from.get(Calendar.MONTH);
        this.fromDate = from.get(Calendar.DATE);
        this.toYear = to.get(Calendar.YEAR);
        this.toMonth = to.get(Calendar.MONTH);
        this.toDate = to.get(Calendar.DATE);
    }
    

}
