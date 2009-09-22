/*
 * CalendarPickerSkinStandard.fx
 *
 * Created on Aug 8, 2009, 4:00:17 PM
 */

package calendarpicker;

import javafx.scene.*;
import javafx.scene.text.*;
import nl.innovationinvestments.javafx.scene.*;
import javafx.scene.control.*;
import javafx.scene.shape.Rectangle;
import javafx.scene.paint.Color;
import java.util.Random;
import javafx.scene.paint.*;
import javafx.util.Math;
import javafx.scene.input.MouseEvent;
import calendarpicker.CalendarPickerBehavior;
import javafx.animation.*;
import javafx.scene.effect.DropShadow;

/**
 * @author Tom Eugelink
 * TODO: partitionalize the refresh so the overhead becomes less
 */

public class CalendarPickerSkinStandard extends CalendarPickerSkinAbstract
{
	// ===========================================================================================
	// controls

	// the font to use
	public-init var font = (Label{text:"bla"}).font;
	public-init var fontHeader = font;

	// color (defaults = Caspian)
	public-init var headerColor : Color = Color.BLACK;
	public-init var weekendColor : Color = Color.rgb(0, 153, 255);
	public-init var weekdayColor : Color = Color.GRAY;
	public-init var weekColor : Color = Color.GRAY;
	public-init var dayColor : Color = Color.BLACK;
	public-init var gridColor : Color = Color.DARKGRAY;
	public-init var selectedColor : Color = Color.rgb(0, 153, 255);
	public-init var todayColor : Color = Color.rgb(153, 204, 255);
	public-init var backgroundColor : Color = Color.WHITE;
	public-init var focusColor : Color = Color.rgb(0, 124, 216);

	public function setCaspianScheme()
	{
		font = (Label{text:"bla"}).font;
		fontHeader = font;

		headerColor = Color.BLACK;
		weekendColor = Color.rgb(0, 153, 255);
		weekdayColor = Color.GRAY;
		weekColor = Color.GRAY;
		dayColor = Color.BLACK;
		gridColor = Color.DARKGRAY;
		selectedColor = Color.rgb(0, 153, 255);
		todayColor = Color.rgb(153, 204, 255);
		backgroundColor = Color.WHITE;
		focusColor = Color.rgb(0, 124, 216);

		(monthNudger.skin as NudgerSkin).textColor = headerColor;
		(monthNudger.skin as NudgerSkin).highlightColor = selectedColor;
		(monthNudger.skin as NudgerSkin).font = fontHeader;
		(yearNudger.skin as NudgerSkin).textColor = headerColor;
		(yearNudger.skin as NudgerSkin).font = fontHeader;

		refresh();
	}

	public function setGreyScheme()
	{
		font = Font.font("sans-serif", FontWeight.BOLD, 14);
		fontHeader = Font.font("sans-serif", FontWeight.BOLD, 16);

		headerColor = Color.WHITE;
		weekendColor = Color.RED;
		weekdayColor = Color.GRAY;
		weekColor = Color.GRAY;
		dayColor = Color.WHITE;
		gridColor = Color.GRAY;
		selectedColor = Color.BLUE;
		todayColor = Color.RED;
		backgroundColor = Color.BLACK;
		focusColor = Color.BLUE;

		(monthNudger.skin as NudgerSkin).textColor = headerColor;
		(monthNudger.skin as NudgerSkin).highlightColor = selectedColor;
		(monthNudger.skin as NudgerSkin).font = fontHeader;
		(yearNudger.skin as NudgerSkin).textColor = headerColor;
		(yearNudger.skin as NudgerSkin).font = fontHeader;

		refresh();
	}


	// ===========================================================================================
	// controls

	// month and year
	var headerLabel : HeaderNode = HeaderNode { text: "month year" };
	var monthNudger : Nudger = Nudger{ value: calendar.get(java.util.Calendar.MONTH), skin: NudgerSkin{ font: fontHeader } };
	var monthNudgerValue : Integer = bind monthNudger.value
	on replace
	{
		if (monthNudgerValue < 0)
		{
			calendar.add(java.util.Calendar.YEAR, -1);
			monthNudger.value = 11;
		}
		else if (monthNudgerValue > 11)
		{
			calendar.add(java.util.Calendar.YEAR, 1);
			monthNudger.value = 0;
		}
		else
		{
			calendar.set(java.util.Calendar.MONTH, monthNudgerValue);
			refreshSetHeader();
			refreshPlaceHeader();
			refresh();
		}
	};
	var yearNudger : Nudger = Nudger{ value: calendar.get(java.util.Calendar.YEAR), skin: NudgerSkin{ font: fontHeader } };
	var yearNudgerValue : Integer = bind yearNudger.value
	on replace
	{
		calendar.set(java.util.Calendar.YEAR, yearNudgerValue);
		refreshSetHeader();
		refreshPlaceHeader();
		refresh();
	};

	// dayOfWeek
	var dayOfWeekLabels : DayOfWeekNode[] = for (i in [0..6]) DayOfWeekNode { text: "wk{i}"};

	// week
	var weekLabels : WeekNode[] = for (i in [0..5]) WeekNode{ weeknr: i };

	// days
	var dayButtons : DayNode[] = for (i in [1..31]) DayNode{ daynr: i };
	
	// background
	var rectangle : Rectangle = Rectangle{ x:0, y:0, width:5, height:5, fill: bind backgroundColor };

	/**
	 * visualize the focus
	 */
	var hasFocus : Boolean = bind control.focused
	on replace
	{
		// this is a highly unsatifying effect, we require something like a "border"
		headerLabel.effect = if(hasFocus) DropShadow{color: focusColor, radius: 5.0} else null;
	};

// ===========================================================================================
	// layout

	// build scene
	init
	{
		node =	Group
		{
			content: [ rectangle
			         , monthNudger, headerLabel, yearNudger
			         , dayOfWeekLabels, weekLabels
					 , dayButtons
					 ];

		}
	}

	/**
	 * accumulate refresh calls
	 */
	override public function refresh() : Void
	{
		//println("refresh();");
		iRefreshTimeline.playFromStart();
	}
	var iRefreshTimeline = Timeline { repeatCount:1, keyFrames:
	[	KeyFrame
		{
			time: 0s;
		}
	,	KeyFrame
		{
			time: 300ms;
			action: function()
			{
				refreshActual();
			};
		}
	]};


	public function refreshActual() : Void
	{
		//println("refreshActual();");

		// set header
		refreshSetHeader();
		var lHeaderHeighest = HeaderNode{ text: "W", }.textNode.layoutBounds.height;

		// set dayOfWeeklabels and determine widest
		var lDayOfWeekLabel : String[] = getWeekdayNames();
		for (i in [0..6]) 
		{
			var lText : DayOfWeekNode = dayOfWeekLabels[i];
			lText.text = lDayOfWeekLabel[i];
			if (lText.text.length() > 0) lText.text = lText.text.substring(0, 1); // only the first letter
			lText.weekend = isWeekdayWeekend(i);
		}
		// we're down to one letter
		var lDayOfWeekLabelWidest = DayOfWeekNode{ text: "W" }.textNode.layoutBounds.width;
		var lDayOfWeekLabelHeighest = DayOfWeekNode{ text: "W" }.textNode.layoutBounds.width;
		
		// set weekLabels and determine widest
		var lWeekLabel = getWeekLabels();
		for (i in [0..5])
		{
			var lText : WeekNode = weekLabels[i];
			lText.weeknr = lWeekLabel[i];
		}
		// always assume two digits
		var lWeekLabelWidest = WeekNode{ weeknr:88 }.textNode.layoutBounds.width;

		// determine widest date label
		var lDateLabelWidest = DayNode{ daynr: 88 }.textNode.layoutBounds.width;

		// determine the grid size
		var lGridBlockSize = lDayOfWeekLabelWidest; // the column label
		lGridBlockSize = Math.max(lGridBlockSize, lWeekLabelWidest); // the row label
		lGridBlockSize = Math.max(lGridBlockSize, lDateLabelWidest); // the contents
		lGridBlockSize += 2*iMargin; // small inner margin
		//println("lGridBlockSize={lGridBlockSize}");

		// -----------------------

		// layout dayOfWeekLabel
		for (i in [0..6]) 
		{
			dayOfWeekLabels[i].size = lGridBlockSize;
			dayOfWeekLabels[i].setFill(); // force color change if the size did not change
			dayOfWeekLabels[i].jumpXTo( iMargin + lWeekLabelWidest + iMargin + (i * (lGridBlockSize + iMargin)) );
			dayOfWeekLabels[i].jumpYTo( iMargin + lHeaderHeighest + iMargin );
		}

		// layout weekLabels
		for (i in [0..5])
		{
			weekLabels[i].size = lGridBlockSize;
			weekLabels[i].jumpXTo( iMargin );
			weekLabels[i].jumpYTo( iMargin + lHeaderHeighest + iMargin + lDayOfWeekLabelHeighest + iMargin + (i * (lGridBlockSize + iMargin)) );
		}

		// layout days
		var lCalendar : java.util.Calendar = (calendar.clone() as java.util.Calendar);
		var lDayOfWeekCol = determineFirstOfMonthDayOfWeek();
		var lDaysInMonth = determineDaysInMonth();
		var lWeekRow = 0;
		var lWeekRowLastVisible = lWeekRow;
		for (i in [0..30])
		{
			// determine the calendar
			lCalendar.set(java.util.Calendar.DATE, (i+1));
			
			// put in some randomness so the neetness goes out of the animation
			var lStartupDelay = Duration.valueOf(iRandom.nextInt(100));
			var lDuration = Duration.valueOf(250 + iRandom.nextInt(100));

			// position
			dayButtons[i].animateXTo( iMargin + lWeekLabelWidest + iMargin + (lDayOfWeekCol * (lGridBlockSize + iMargin)), lDuration, lStartupDelay );
			dayButtons[i].animateYTo( iMargin + lHeaderHeighest + iMargin + lDayOfWeekLabelHeighest + iMargin + (lWeekRow * (lGridBlockSize + iMargin)), lDuration, lStartupDelay );
			dayButtons[i].size = lGridBlockSize;
			dayButtons[i].isSelected = (control as CalendarPicker).isSelected(lCalendar);
			dayButtons[i].isToday = isToday(lCalendar);
			dayButtons[i].fillRectangle(); // force color setting
			
			// visible or not (only 29..31)
			if (i > 27)
			{
				dayButtons[i].animateOpacityTo( (if ((i+1) > lDaysInMonth) 0.0 else 1.0), 500ms, 100ms );
			}
			if ((i+1) <= lDaysInMonth) lWeekRowLastVisible = lWeekRow;

			// next
			lDayOfWeekCol++;
			if (lDayOfWeekCol > 6)
			{
				lDayOfWeekCol = 0;
				lWeekRow++;
			}
		}

		// should the last weeklabels be hidden?
		for (i in [4..5])
		{
			weekLabels[i].animateOpacityTo( if (lWeekRowLastVisible < i) 0.0 else 1.0 );
		}

		// background
		rectangle.width = iMargin + lWeekLabelWidest + iMargin + (7 * (lGridBlockSize + iMargin)) + iMargin;
		rectangle.height = iMargin + lHeaderHeighest + iMargin + lDayOfWeekLabelHeighest + iMargin + (6 * (lGridBlockSize + iMargin)) + iMargin ;

		// header
		refreshPlaceHeader();
		monthNudger.translateX = iMargin;// + lWeekLabelWidest;
		monthNudger.translateY = iMargin;
		yearNudger.translateX = rectangle.width - yearNudger.skin.node.layoutBounds.width - iMargin;
		yearNudger.translateY = iMargin;
	}
	public function refreshSetHeader() : Void
	{
		// set header
		headerLabel.text = "{getMonthName()} {calendar.get(java.util.Calendar.YEAR)}";
	}
	public function refreshPlaceHeader() : Void
	{
		// set header
		headerLabel.jumpXTo( (rectangle.width - headerLabel.textNode.layoutBounds.width) / 2 );
		headerLabel.jumpYTo( iMargin );
	}
	var iRandom : Random = new Random();
	var iMargin = 2;
}

class HeaderNode extends AutoAnimatingCustomNode
{
	var textNode : Text = Text { content: "?", fill: bind headerColor, font: bind fontHeader, textOrigin: TextOrigin.TOP };

	public var text : String
	on replace
	{
		textNode.content = text;
	};

	public override function create(): Node
	{
		return textNode;
	}

}

class DayOfWeekNode extends AutoAnimatingCustomNode
{
	var textNode : Text = Text { content: "?", font: bind font, textOrigin: TextOrigin.TOP };

	public var text : String
	on replace
	{
		textNode.content = text;
	};

	public var weekend : Boolean
	on replace
	{
			setFill();
	};
	public function setFill()
	{
		textNode.fill = if (weekend) weekendColor else weekdayColor;
	}


	public var size : Number
	on replace
	{
		// center
		textNode.x = ((size - textNode.layoutBounds.width) / 2) + 1;
	};

	public override function create(): Node
	{
		return textNode;
	}

}

class WeekNode extends AutoAnimatingCustomNode
{
	var textNode : Text = Text { x:0, y:0, content: "?", font: bind font, fill: weekColor, textOrigin: TextOrigin.TOP };

	public var weeknr : Integer
	on replace
	{
		textNode.content = "{weeknr}";
	};

	public var size : Number
	on replace
	{
		// center 
		textNode.y = ((size - textNode.layoutBounds.height) / 2) + 1;
	};
	public override function create(): Node
	{
		return textNode;
	}
}

class DayNode extends AutoAnimatingCustomNode
{
	var textNode : Text = Text 
	{ x:0, y:0
	, content: "?"
	, fill: bind dayColor
	, font: bind font
	, textOrigin: TextOrigin.TOP
	};

	var rectangleNode : Rectangle = Rectangle
	{ x:0, y:0
	, fill: gridColor
	, stroke: Color.TRANSPARENT
	};
									
	public var daynr : Integer
	on replace
	{
		textNode.content = "{daynr}";
	};

	public var size : Number
	on replace
	{
		textNode.x = ((size - textNode.layoutBounds.width) / 2) + 1;
		textNode.y = ((size - textNode.layoutBounds.height) / 2) + 1;

		rectangleNode.width = size;
		rectangleNode.height = size;
	};

	public var isSelected : Boolean = false
	on replace
	{
		fillRectangle();
	};

	public var isToday : Boolean = false
	on replace
	{
		fillRectangle();
	};

	function fillRectangle()
	{
		rectangleNode.fill = if (isSelected) selectedColor else (if (isToday) todayColor else gridColor);
	}


	public override function create(): Node
	{
		onMouseClicked = function(mouseEvent : MouseEvent) : Void
		{
			var lCalendar = (calendar.clone() as java.util.Calendar);
			lCalendar.set(java.util.Calendar.DATE, daynr);
			(behavior as CalendarPickerBehavior).select(lCalendar, mouseEvent.shiftDown, mouseEvent.controlDown);
		};
		
		return Group
		       {
				   content: 
				   [ rectangleNode
				   , textNode
				   ]
			   };
	}
}


