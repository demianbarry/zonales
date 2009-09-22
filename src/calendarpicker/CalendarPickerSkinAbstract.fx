/*
 * CalendarPickerSkinAbstract.fx
 *
 * Created on Aug 8, 2009, 4:01:05 PM
 */

package calendarpicker;
import javafx.scene.control.*;

/**
 * @author User
 */

abstract public class CalendarPickerSkinAbstract extends Skin, CalendarPickerSkin
{
	// ===========================================================================================
	// variables

	// create a bind variable of the active control
	public var boundControl : CalendarPicker = bind control as CalendarPicker;

    // the current visual calendar
	// this is NOT the currently active calendar, that is stored in the Calendar class, but it represents the visible month
	public var calendar : java.util.Calendar = java.util.Calendar.getInstance()
	on replace
	{
		// allways 1st of the month (no problems with a possible 30th februari)
		calendar.set(java.util.Calendar.DATE, 1);
		refresh()
	}
	;

	// used for updating; if the calendar is set, then we jump to display that
	protected var controlCalendar = bind boundControl.calendar
	on replace
	{
		if (controlCalendar != null)
		{
			calendar = controlCalendar.clone() as java.util.Calendar;
		}
	}
	;

	// used for formatting
	protected var locale : java.util.Locale = bind boundControl.locale
	on replace
	{
		if (locale != null)
		{
			// change the formatter
			simpleDateFormat = (java.text.SimpleDateFormat.getDateInstance(java.text.SimpleDateFormat.LONG, locale) as java.text.SimpleDateFormat);

			// refresh
			refresh();
		}
	}
	;
	protected var simpleDateFormat : java.text.SimpleDateFormat = java.text.SimpleDateFormat.getDateInstance() as java.text.SimpleDateFormat;

	// ===========================================================================================
	// control

	// create the behavior that comes with this control
	override protected var behavior = CalendarPickerBehavior{};

	// to be implemented by the actual skin
	// TODO: split refresh into a locale related and calendar related part?
	abstract override public function refresh() : Void;


	// ===========================================================================================
	// UTILITY

	/**
	 * get all month names
	 */
	protected function getMonthNames() : String[]
	{
		// month
		var lCalendar = new java.util.GregorianCalendar(2009, 0, 1);
		simpleDateFormat.applyPattern("MMMMM");
		return
		[
			for (i in [0..11])
			{
				lCalendar.set(java.util.Calendar.MONTH, i);
				simpleDateFormat.format(lCalendar.getTime())
			}
		];
	}

	/**
	 * get current month name
	 */
	protected function getMonthName() : String
	{
		// month
		simpleDateFormat.applyPattern("MMMMM");
		return simpleDateFormat.format(calendar.getTime());
	}

	/**
	 * return the list of weekday names
	 */
	protected function getWeekdayNames() : String[]
	{
		// setup the dayLabels
		// Calendar.SUNDAY = 1 and Calendar.SATURDAY = 7
		simpleDateFormat.applyPattern("E");
		var lCalendar = new java.util.GregorianCalendar(2009, 6, 4 + calendar.getFirstDayOfWeek()); // july 5th 2009 is a Sunday
		return
		[
			for (i in  [0..6])
			{
				// next
				lCalendar.set(java.util.Calendar.DATE, 4 + calendar.getFirstDayOfWeek() + i);

				// assign day
				simpleDateFormat.format(lCalendar.getTime());
			}
		];
	}

	/**
	 * check if a certain weekday name is a certain day-of-the-week
	 */
	protected function isWeekday(idx : Integer, weekdaynr : Integer) : Boolean
	{
		// setup the dayLabels
		// Calendar.SUNDAY = 1 and Calendar.SATURDAY = 7
		var lCalendar = new java.util.GregorianCalendar(2009, 6, 4 + calendar.getFirstDayOfWeek()); // july 5th 2009 is a Sunday
		lCalendar.add(java.util.Calendar.DATE, idx);
		var lDayOfWeek = lCalendar.get(java.util.Calendar.DAY_OF_WEEK);

		// check
		return (lDayOfWeek == weekdaynr);
	}

	/**
	 * check if a certain weekday name is a certain day-of-the-week
	 */
	protected function isWeekdayWeekend(idx : Integer) : Boolean
	{
		return (isWeekday(idx, java.util.Calendar.SATURDAY) or isWeekday(idx, java.util.Calendar.SUNDAY));
	}

	/**
	 * Get a sequence with the weeklabels
	 */
	protected function getWeekLabels() : Integer[]
	{
		// result
		var lWeekLabels : Integer[];

		// setup the weekLabels
		var lCalendar = calendar.clone() as java.util.Calendar;
		for (i in [0..5])
		{
			// set label
			insert lCalendar.get(java.util.Calendar.WEEK_OF_YEAR) into lWeekLabels;

			// next week
			lCalendar.add(java.util.Calendar.DATE, 7);
		}

		// done
		return lWeekLabels;
	}

	/**
	 * determine on which day of week idx the first of of the months is
	 */
	protected function determineFirstOfMonthDayOfWeek()
	{
		// determine with which button to start
		var lFirstDayOfWeek = calendar.getFirstDayOfWeek();
		var lFirstOfMonthIdx = calendar.get(java.util.Calendar.DAY_OF_WEEK) - lFirstDayOfWeek;
		if (lFirstOfMonthIdx < 0) lFirstOfMonthIdx += 7;
		return lFirstOfMonthIdx;
	}

	/**
	 * determine the number of days in the month
	 */
	protected function determineDaysInMonth()
	{
		// determine the number of days in the month
		var lCalendar = calendar.clone() as java.util.Calendar;
		lCalendar.add(java.util.Calendar.MONTH, 1);
		lCalendar.add(java.util.Calendar.DATE, -1);
		return lCalendar.get(java.util.Calendar.DAY_OF_MONTH);
	}

	/**
	 * determine if a date is today
	 */
	protected function isToday(calendar : java.util.Calendar) : Boolean
	{
		var lYear = calendar.get(java.util.Calendar.YEAR);
		var lMonth = calendar.get(java.util.Calendar.MONTH);
		var lDay = calendar.get(java.util.Calendar.DATE);
		return (lYear == iTodayYear and lMonth == iTodayMonth and lDay == iTodayDay);
	}
	var iToday = java.util.Calendar.getInstance();
	var iTodayYear = iToday.get(java.util.Calendar.YEAR);
	var iTodayMonth = iToday.get(java.util.Calendar.MONTH);
	var iTodayDay = iToday.get(java.util.Calendar.DATE);



	// ===========================================================================================
	// Generic SKIN

	override function contains(localX: Number, localY: Number): Boolean
	{
		node.contains(localX, localY);
	}

	override function intersects(localX: Number, localY: Number,localWidth: Number, localHeight: Number): Boolean
	{
		node.intersects(localX, localY, localWidth, localHeight);
	}
}

public function printlnCalendar(s : String, c : java.util.Calendar)
{
	if (c == null) { println("{s} null"); }
	else println("{s} {c.get(java.util.Calendar.YEAR)}-{c.get(java.util.Calendar.MONTH) + 1}-{c.get(java.util.Calendar.DATE)} {c.hashCode()}")
}
