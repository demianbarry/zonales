/*
 * CalendarPickerSkin.fx
 *
 * Created on Jul 27, 2009, 8:52:01 AM
 */

package calendarpicker;
import javafx.scene.control.*;
import javafx.scene.layout.*;
import javafx.scene.paint.*;
import javafx.scene.effect.*;
import calendarpicker.ComboBox;

/**
 * @author Tom Eugelink
 */

public class CalendarPickerSkinControls extends CalendarPickerSkinAbstract
{
	// ===========================================================================================
	// controls

	// maximum 6 week each with 7 buttons
	var buttons : ToggleButton[] = for (i in [0..(6*7)-1]) ToggleButton
	{
		text: "btn{i}"
		onMouseClicked: function (mouseEvent): Void { clicked(buttons[i].text, mouseEvent.shiftDown, mouseEvent.controlDown);  }
	};

	// week
	var weekLabels : Label[] = for (i in [0..5]) Label { text: "wk{i}" };

	// day
	var dayLabels : Label[] = for (i in [0..6]) Label { text: "dy{i}" };

	// month
	var monthComboBox : ComboBox = ComboBox { items: [ for (i in [0..11]) "mnth{i}" ] };
	var monthSelectedIndexChanged = bind monthComboBox.selectedIndex // on change event
	on replace
	{
		// update the calendar
		if (monthComboBox.selectedIndex >= 0)
		{
			calendar.set(java.util.Calendar.MONTH, monthComboBox.selectedIndex);
			refresh();
		}
	};
	var monthPrevButton : Button = Button { text: "<", action: function(): Void
	{
		if (monthComboBox.selectedIndex > 0) { monthComboBox.selectPreviousRow();  }
		else
		{
			var lYear = java.lang.Integer.parseInt(yearTextBox.text);
			calendar.set(java.util.Calendar.YEAR, lYear - 1);
			calendar.set(java.util.Calendar.MONTH, 11);
			refresh();
		}
	}};
	var monthNextButton : Button = Button { text: ">", action: function(): Void
	{
		if (monthComboBox.selectedIndex < 11) { monthComboBox.selectNextRow(); }
		else
		{
			var lYear = java.lang.Integer.parseInt(yearTextBox.text);
			calendar.set(java.util.Calendar.YEAR, lYear + 1);
			calendar.set(java.util.Calendar.MONTH, 0);
			refresh();
		}
	}};

	// year
	var yearTextBox = TextBox { text: "year" };
	var yearTextBoxFocus = bind yearTextBox.focused
	on replace
	{
		// only on focus lost
		if (yearTextBoxFocus == false)
		{
			try
			{
				var lYear = java.lang.Integer.parseInt(yearTextBox.text);
				calendar.set(java.util.Calendar.YEAR, lYear);
			}
			catch ( e :java.lang.NumberFormatException ) { /* ignored on purpose */ }
			refresh();
		}
	}
	;
	var yearPrevButton : Button = Button { text: "<", action: function(): Void
	{
		var lYear = java.lang.Integer.parseInt(yearTextBox.text);
		calendar.set(java.util.Calendar.YEAR, lYear - 1);
		refresh();
	} };
	var yearNextButton : Button = Button { text: ">", action: function(): Void
	{
		var lYear = java.lang.Integer.parseInt(yearTextBox.text);
		calendar.set(java.util.Calendar.YEAR, lYear + 1);
		refresh();
	} };

	// just for accessing the colors
	var dummyLabel = Label{ text: "dummy" };

	// ===========================================================================================
	// layout

	// build scene
	init
	{
		node =	VBox
				{
					content:
					[
						HBox
						{
							content:
							[ monthComboBox, monthPrevButton, monthNextButton
							, Label{text: "   "}
							, yearTextBox, yearPrevButton, yearNextButton
							]
						}
					,	Tile
						{
							hgap: 2
							vgap: 2
							columns: 8
							content:
							[	Label{}      , for (i in [0..6]) dayLabels[i]
							,	weekLabels[0], for (i in [(0*7)..(1*7)-1]) buttons[i]
							,	weekLabels[1], for (i in [(1*7)..(2*7)-1]) buttons[i]
							,	weekLabels[2], for (i in [(2*7)..(3*7)-1]) buttons[i]
							,	weekLabels[3], for (i in [(3*7)..(4*7)-1]) buttons[i]
							,	weekLabels[4], for (i in [(4*7)..(5*7)-1]) buttons[i]
							,	weekLabels[5], for (i in [(5*7)..(6*7)-1]) buttons[i]
							]
						}
					]
				}
	}

	// ===========================================================================================
	// event

	function clicked(idx : String, range : Boolean, extend : Boolean)
	{
		// get date
		var lIdx = java.lang.Integer.parseInt(idx);

		// create the calendar
		var lCalendar = boundControl.calendar.clone() as java.util.Calendar;
		lCalendar.set(java.util.Calendar.YEAR, java.lang.Integer.parseInt(yearTextBox.text));
		lCalendar.set(java.util.Calendar.MONTH, monthComboBox.selectedIndex);
		lCalendar.set(java.util.Calendar.DATE, lIdx);

		// set it
		(behavior as CalendarPickerBehavior).select(lCalendar, range, extend);
	}


	// ===========================================================================================
	// CalendarPickerSkin interface

	// TODO: only refresh when something actually has changed
	override public function refresh() : Void
	{
		// year
		var lYear = calendar.get(java.util.Calendar.YEAR);
		yearTextBox.text = "{lYear}";

		// month
		var lMonth = calendar.get(java.util.Calendar.MONTH);
		monthComboBox.items = getMonthNames();
		monthComboBox.select(lMonth);

		// setup the dayLabels
		var lWeekdayNames = getWeekdayNames();
		for (i in  [0..6])
		{
			// assign day
			dayLabels[i].text = lWeekdayNames[i];
			dayLabels[i].textFill = if ( isWeekdayWeekend(i) ) Color.RED else dummyLabel.textFill; // TODO: colors customizable
		}

		// setup the weekLabels
		var lWeekLabels = getWeekLabels();
		for (i in [0..5])
		{
			// set label
			weekLabels[i].text = "{lWeekLabels[i]}";

			// first hide
			weekLabels[i].visible = false;
		}

		// setup the buttons [0..(6*7)-1]
		// determine with which button to start
		var lFirstOfMonthIdx = determineFirstOfMonthDayOfWeek();

		// hide the preceeding buttons
		for (i in [0..lFirstOfMonthIdx - 1])
		{
			buttons[i].visible = false;
		}
		
		// set the month buttons
		var lDaysInMonth = determineDaysInMonth();
		var lCalendar = calendar.clone() as java.util.Calendar;
		for (i in [1..lDaysInMonth])
		{
			// set the date
			lCalendar.set(java.util.Calendar.DATE, i);

			// determine the index in the buttons
			var lIdx = lFirstOfMonthIdx + i - 1;

			// update the button
			buttons[lIdx].visible = true;
			buttons[lIdx].text = "{i}";

			// make the corresponding weeklabel visible
			weekLabels[lIdx / 7].visible = true;

			// highlight today
			buttons[lIdx].effect = if (isToday(lCalendar)) InnerShadow {color: Color.BROWN} else null; // TODO: color custom

			// is this date selected
			buttons[lIdx].selected = boundControl.isSelected(lCalendar);
		}

		// hide the trailing buttons
		for (i in [lFirstOfMonthIdx + lDaysInMonth..(6*7)-1])
		{
			buttons[i].visible = false;
		}
	}
}
