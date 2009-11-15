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
public class Helper {

    public static Boolean checkReserve(Calendar ra, Calendar da, Calendar rc, Calendar dc) {

        if (ra.get(Calendar.DAY_OF_YEAR) != rc.get(Calendar.DAY_OF_YEAR)) {
            return false;
        } else {
            if (hourOf(ra).getTimeInMillis() < hourOf(rc).getTimeInMillis()) {
                return ((hourOf(ra).getTimeInMillis() + hourOf(da).getTimeInMillis()) < hourOf(rc).getTimeInMillis());
            }

            if (hourOf(ra).getTimeInMillis() > hourOf(rc).getTimeInMillis()) {
                return ((hourOf(rc).getTimeInMillis() + hourOf(dc).getTimeInMillis()) < hourOf(ra).getTimeInMillis());
            }
        }

        return false;
    }

    private static Calendar hourOf(Calendar date) {

        Calendar dateaux = Calendar.getInstance();

        dateaux.set(1970, 0, 1, date.get(Calendar.HOUR_OF_DAY), date.get(Calendar.MINUTE), date.get(Calendar.SECOND));

        return dateaux;

    }

}
