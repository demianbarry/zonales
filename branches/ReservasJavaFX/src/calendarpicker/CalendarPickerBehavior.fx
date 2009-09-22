/*
 * JFXCalendarBehavior.fx
 *
 * Created on Jul 27, 2009, 8:52:01 AM
 */
package calendarpicker;
import javafx.scene.control.*;
import javafx.util.Sequences;


/**
 * @author Tom Eugelink
 */

public class CalendarPickerBehavior extends javafx.scene.control.Behavior
{
	public function select(calendar : java.util.Calendar, range : Boolean, extend : Boolean)
	{
		//CalendarPickerSkinControls.printlnCalendar("select", calendar);
		var lControl = (skin.control as CalendarPicker);

		// for a range determine start and end
		var lStart : java.util.Calendar = if (lControl.calendar.before(calendar)) lControl.calendar else calendar;
		var lEnd : java.util.Calendar = if (lControl.calendar.before(calendar)) calendar else lControl.calendar;
		lStart = lStart.clone() as java.util.Calendar;
		
		// Single
		if ( lControl.mode == CalendarPicker.MODE_SINGLE )
		{
			// set one value
			lControl.calendars = [calendar];
			lControl.calendar = calendar;
		}

		// range or multiple without extend active
		if ( (lControl.mode == CalendarPicker.MODE_RANGE)
		  or (lControl.mode == CalendarPicker.MODE_MULTIPLE and extend == false)
		   )
		{
			if (range == false) 
			{
				// set one value
				lControl.calendars = [calendar];
				lControl.calendar = calendar;
			}
			else
			{
				// add all dates to a new range
				var lSequence : java.util.Calendar[] = [];
				while (lStart.before(lEnd) or lStart.equals(lEnd))
				{
					var lCalendar = lStart.clone() as java.util.Calendar;
					insert lCalendar into lSequence;
					lStart.add(java.util.Calendar.DATE, 1);
				}
				lControl.calendars = lSequence;
				lControl.calendar = calendar;
			}
		}

		// multiple with extend active
		if ( lControl.mode == CalendarPicker.MODE_MULTIPLE and extend == true)
		{
			if (range == false)
			{
				// add one value
				insert calendar into lControl.calendars;
				lControl.calendar = calendar;
			}
			else if (range == true)
			{
				// add all dates to the range
				while (lStart.before(lEnd) or lStart.equals(lEnd))
				{
					// if the calendar is not present in calendars
					if (Sequences.indexOf(lControl.calendars, lStart) < 0) // not found
					{
						var lCalendar = lStart.clone() as java.util.Calendar;
						insert lCalendar into lControl.calendars;
					}
					lStart.add(java.util.Calendar.DATE, 1);
				}
				lControl.calendar = calendar;
			}
		}

		if (not skin.control.focused) skin.control.requestFocus();
	}
}