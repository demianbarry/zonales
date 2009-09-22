/*
 * Calendar.fx
 *
 * Created on Jul 27, 2009, 8:52:01 AM
 */

package calendarpicker;
import javafx.scene.*;
import javafx.scene.control.*;
import javafx.util.Sequences;


/**
 * The locale only determines the language that is used, settings like "first day of week" are set by the calendar.
 * So it is possible to have english calendar settings (e.g. first day of week is monday) with french texts.
 * In order to have both the values correct, one needs to use the locale getter of Calendar:
 *    java.util.Calendar.getInstance(Locale)
 *
 * All values are initialized to the JVM's defaults.
 *
 * @author Tom Eugelink
 */

// constants for the mode
public def MODE_SINGLE = 0;
public def MODE_RANGE = 1;
public def MODE_MULTIPLE = 2;

/**
 *
 */
public class CalendarPicker extends Control
{
	// ===========================================================================================
	// variables

	// selection mode
	public var mode = MODE_SINGLE
	on replace
	{
		//println("mode on replace: set to {mode}");
		calendars = [java.util.Calendar.getInstance()];
	}
	;

    // the selected calendar
	public var calendar : java.util.Calendar // is set by the on replace trigger on mode = java.util.Calendar.getInstance()
	on replace
	{
		//CalendarPickerSkinControls.printlnCalendar("calendar on replace: set to", calendar);
		// if the calendar is not present in calendars
		if (calendar != null and calendars != null and Sequences.indexOf(calendars, calendar) < 0) // not found
		{
			// modify the range
			//CalendarPickerSkinControls.printlnCalendar("calendar on replace: updating range", calendar);
			calendars = [calendar];
			(skin as CalendarPickerSkin).refresh();
		}
	};

	// the calendars
	public var calendars : java.util.Calendar[] // is set be the on replace trigger on calendar = [java.util.Calendar.getInstance()]
	on replace
	{
		//println("calendars on replace: set to {sizeof calendars}");
		// check if the calendar is a valid sequence, if not make it a sequence of one
		if (sizeof calendars == 0) { calendar = java.util.Calendar.getInstance(); }
		// check if the calendar is present in calendars
		else
		{
			// if the calendar is not present in calendars
			if ( calendar == null
			  or (calendar != null and calendars != null and Sequences.indexOf(calendars, calendar) < 0) // not found
			   )
			{
				// set calendar to the first value in the range
				calendar = calendars[0];
				(skin as CalendarPickerSkin).refresh();
			}
		}
	};
	
	// the locale under which we are running
	public var locale : java.util.Locale = java.util.Locale.getDefault()
	on replace { if (locale == null) throw new java.lang.IllegalArgumentException("Null not allowed"); }
	;

	// ===========================================================================================
	// logic

	/**
	 * determine if a date is selected
	 */
	public function isSelected(calendar : java.util.Calendar) : Boolean
	{
		// null is always false
		if (calendar == null) return false;

		// determine
		var lYear = calendar.get(java.util.Calendar.YEAR);
		var lMonth = calendar.get(java.util.Calendar.MONTH);
		var lDay = calendar.get(java.util.Calendar.DATE);

		// scan all
		for (c in calendars)
		{
			// determine
			var lControlYear = c.get(java.util.Calendar.YEAR);
			var lControlMonth = c.get(java.util.Calendar.MONTH);
			var lControlDay = c.get(java.util.Calendar.DATE);

			// determine
			var lFound = (lControlYear == lYear and lControlMonth == lMonth and lControlDay == lDay);
			if (lFound == true) return true;
		}

		// not found
		return false;
	}


	// ===========================================================================================
	// control
	
	override function create(): Node
	{
        // create the default skin
		//skin = CalendarPickerSkinControls{ };
		skin = CalendarPickerSkinStandard{ };

        // continue
		super.create();
	}
}

