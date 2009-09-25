/*
 * CalendarTest.fx
 *
 * Created on Jul 27, 2009, 8:52:01 AM
 */

package calendarpicker;

import javafx.stage.*;
import javafx.scene.*;
import javafx.scene.control.*;
import javafx.scene.layout.*;
import javafx.scene.effect.Reflection;
import javafx.scene.paint.Color;

// the picker
var calendarPicker = CalendarPicker
{
	translateX: 2
};


// mode
var modeComboBox : ComboBox = ComboBox
{	items: [ "SINGLE", "RANGE",  "MULTIPLE"]
,	layoutInfo: LayoutInfo { width: 150 }
};
modeComboBox.select(0);
var modeSelectedIndexChanged = bind modeComboBox.selectedIndex // on change event
on replace
{
	// update the calendar
	if (modeComboBox.selectedIndex >= 0)
	{
		if (modeComboBox.selectedIndex == 0) calendarPicker.mode = CalendarPicker.MODE_SINGLE;
		if (modeComboBox.selectedIndex == 1) calendarPicker.mode = CalendarPicker.MODE_RANGE;
		if (modeComboBox.selectedIndex == 2) calendarPicker.mode = CalendarPicker.MODE_MULTIPLE;
	}
};

// locales
var locales = [];
var localeComboBox : ComboBox = ComboBox
{	items: []
,	layoutInfo: LayoutInfo { width: 150 }
};
insert "" into localeComboBox.items;
for ( lLocale in java.util.Locale.getAvailableLocales() )
{
	insert lLocale.getDisplayName() into localeComboBox.items;
}
localeComboBox.items = javafx.util.Sequences.sort(localeComboBox.items as java.lang.Comparable[]);
localeComboBox.select(0);
var localeSelectedIndexChanged = bind localeComboBox.selectedIndex // on change event
on replace
{
	// update the calendar
	if (localeComboBox.selectedIndex >= 0)
	{
		for ( lLocale in java.util.Locale.getAvailableLocales() )
		{
			if (lLocale.getDisplayName().equals( localeComboBox.selectedItem ))
			{
				calendarPicker.calendar = java.util.Calendar.getInstance(lLocale);
				calendarPicker.locale = lLocale;
			}
		}
	}
};

// mode
var schemeComboBox : ComboBox = ComboBox
{	items: [ "Caspian", "Gray"]
};
schemeComboBox.select(0);
var schemeChangedI = bind schemeComboBox.selectedIndex // on change event
on replace
{
	// update the calendar
	if (schemeComboBox.selectedIndex >= 0)
	{
		if (schemeComboBox.selectedIndex == 0) (calendarPicker.skin as CalendarPickerSkinStandard).setCaspianScheme();
		if (schemeComboBox.selectedIndex == 1) (calendarPicker.skin as CalendarPickerSkinStandard).setGreyScheme();
	}
};

// skin
var skinComboBox : ComboBox = ComboBox
{	items: [ "Standard", "Control"]
	layoutInfo: LayoutInfo { width: 150 }
};
var skinComboBoxChanged = bind skinComboBox.selectedIndex // on change event
on replace
{
	// update the calendar
	if (skinComboBox.selectedIndex >= 0)
	{
		if (skinComboBox.selectedIndex == 0)
		{
			calendarPicker.skin = CalendarPickerSkinStandard{};
			schemeComboBox.visible = true;
			schemeComboBox.select(0);
			(calendarPicker.skin as CalendarPickerSkinStandard).setCaspianScheme();
			calendarPicker.effect = Reflection{};
		}
		if (skinComboBox.selectedIndex == 1)
		{
			calendarPicker.skin = CalendarPickerSkinControls{};
			schemeComboBox.visible = false;
			calendarPicker.effect = null;
		}
	}
};
skinComboBox.select(0);



// current picker value
var currentCalendarTextBox = TextBox{editable: false};
var currentCalendar = bind calendarPicker.calendar
on replace
{
	var c = calendarPicker.calendar;
	currentCalendarTextBox.text = "{c.get(java.util.Calendar.YEAR)}-{c.get(java.util.Calendar.MONTH) + 1}-{c.get(java.util.Calendar.DATE)}";
};

// stage
Stage
{
    width: 500;
    height: 700;
    scene: Scene
    {
		///stylesheets: ["{__DIR__}style.css"],
        content:
        [	VBox
			{
				content:
				[	Tile
					{
						columns: 3
						content:
						[	Label {text: "Select locale"}, localeComboBox, Label{}
						,	Label {text: "Select mode"}, modeComboBox, Label{}
						,	Label {text: "Select skin"}, skinComboBox, Label{}
						,	Label {text: "Select scheme"}, schemeComboBox, Label{}
						,	Label {text: "Current picker value"}, currentCalendarTextBox, Label{}
						]
					}
				,	Label { layoutInfo: LayoutInfo{height: 50} }
				,	calendarPicker
				]
			}
        ]
    }

}

