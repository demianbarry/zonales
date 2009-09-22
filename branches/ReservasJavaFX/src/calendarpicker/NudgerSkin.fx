/*
 * NudgerSkin.fx
 *
 * Created on Aug 13, 2009, 11:27:51 AM
 */

package calendarpicker;
import javafx.scene.control.*;
import javafx.scene.*;
import javafx.scene.text.*;
import javafx.scene.paint.*;
import javafx.scene.shape.*;
import javafx.scene.input.MouseEvent;
import javafx.animation.*;
import javafx.scene.effect.DropShadow;

/**
 * @author Tom Eugelink
 */

public class NudgerSkin extends Skin
{
	// defaults
	public var font = (Label{text:"bla"}).font;
	public var textColor : Color = Color.BLACK
	on replace { leftColor = textColor; rightColor = textColor; };
	public var highlightColor : Color = Color.LIGHTGRAY;
	public var focusColor : Color = Color.rgb(0, 124, 216);
	var animationSpace : Integer = 5;

	// text
	var leftLabel = Text { content: "<", font: bind font, fill: bind leftColor, textOrigin: TextOrigin.TOP, x:5 };
	var rightLabel = Text { content: ">", font: bind font, fill: bind rightColor, textOrigin: TextOrigin.TOP, x: animationSpace + leftLabel.layoutBounds.width };

	// for animation
	var leftColor : Color;
	var rightColor : Color;
	
	// the active area
	var rectangle : Rectangle = Rectangle
	{ x:0, y:0, width: 0, height: bind rightLabel.layoutBounds.height, fill: Color.TRANSPARENT
	, onMouseClicked:  function(mouseEvent : MouseEvent) : Void
	{
		var lNudgerBehavior : NudgerBehavior = (behavior as NudgerBehavior);
		lNudgerBehavior.repeatingStop();
		var lAdd = if (mouseEvent.x < (rectangle.width / 2) ) -1 else 1;
		clickAnimation(lAdd);
		lNudgerBehavior.add( lAdd );
	}
	, onMouseDragged:  function(mouseEvent : MouseEvent) : Void
	{
		var lNudgerBehavior : NudgerBehavior = (behavior as NudgerBehavior);
		var lAdd = (mouseEvent.dragX / 2);
		repeatAnimation(lAdd);
		lNudgerBehavior.repeatingAdd( lAdd );
	}
	, onMouseReleased:  function(mouseEvent : MouseEvent) : Void
	{
		var lNudgerBehavior : NudgerBehavior = (behavior as NudgerBehavior);
		repeatAnimationStop();
		lNudgerBehavior.repeatingStop();
	}
	, onMouseExited:  function(mouseEvent : MouseEvent) : Void
	{
		var lNudgerBehavior : NudgerBehavior = (behavior as NudgerBehavior);
		repeatAnimationStop();
		lNudgerBehavior.repeatingStop();
	}
	, onMouseWheelMoved:  function(mouseEvent : MouseEvent) : Void
	{
		var lNudgerBehavior : NudgerBehavior = (behavior as NudgerBehavior);
		clickAnimation(-1 * mouseEvent.wheelRotation);
		lNudgerBehavior.add( -1 * mouseEvent.wheelRotation );
	}};

	/**
	 * visualize the focus
	 */
	var hasFocus : Boolean = bind control.focused
	on replace
	{
		// this is a highly unsatifying effect, we require something like a "border"
		leftLabel.effect = if(hasFocus) DropShadow{color: focusColor, radius: 5.0} else null;
		rightLabel.effect = if(hasFocus) DropShadow{color: focusColor, radius: 5.0} else null;
	};


	// ========================================================================
	// click
	
	function clickAnimation(add)
	{
		if (add < 0) iLeftClickTimeline.playFromStart();
		if (add > 0) iRightClickTimeline.playFromStart();
	}
	
	var iLeftClickTimeline : Timeline = Timeline { keyFrames:
	[	KeyFrame
		{
			time: 0s;
			values: leftColor => textColor;
		}
	,	KeyFrame
		{
			time: 100ms;
			values: leftColor => highlightColor;
		}
	,	KeyFrame
		{
			time: 200ms;
			values: leftColor => textColor;
		}
	]};

	var iRightClickTimeline : Timeline = Timeline { keyFrames:
	[	KeyFrame
		{
			time: 0s;
			values: rightColor => textColor;
		}
	,	KeyFrame
		{
			time: 100ms;
			values: rightColor => highlightColor;
		}
	,	KeyFrame
		{
			time: 200ms;
			values: rightColor => textColor;
		}
	]};

	// ========================================================================
	// repeat
	// we use two external booleans to tell the timelines if they should stop or not
	// this allows us to stop the timelines at the keyframes @0s

	function repeatAnimation(add)
	{
		if (add < 0)
		{
			iLeftRepeatTimelineRunning = true;
			iLeftRepeatTimeline.play();
			iRightRepeatTimelineRunning = false; 
		}
		if (add > 0) 
		{
			iLeftRepeatTimelineRunning = false;
			iRightRepeatTimelineRunning = true;
			iRightRepeatTimeline.play();
		}
	}
	function repeatAnimationStop()
	{
		iLeftRepeatTimelineRunning = false;
		iRightRepeatTimelineRunning = false;
	}
	
	var iLeftRepeatTimeline : Timeline = Timeline { repeatCount:Timeline.INDEFINITE, keyFrames:
	[	KeyFrame
		{
			time: 1ms;
			values: leftLabel.x => 5;
			action: function()
			{
				if (not iLeftRepeatTimelineRunning) iLeftRepeatTimeline.pause();
			}
		}
	,	KeyFrame
		{
			time: 500ms;
			values: leftLabel.x => 0;
		}
	]};
	var iLeftRepeatTimelineRunning : Boolean = false;

	var iRightRepeatTimeline : Timeline = Timeline { repeatCount:Timeline.INDEFINITE, keyFrames:
	[	KeyFrame
		{
			time: 1ms;
			values: rightLabel.x => animationSpace + leftLabel.layoutBounds.width;
			action: function()
			{
				if (not iRightRepeatTimelineRunning) iRightRepeatTimeline.pause();
			}
		}
	,	KeyFrame
		{
			time: 500ms;
			values: rightLabel.x => animationSpace + leftLabel.layoutBounds.width + animationSpace;
		}
	]};
	var iRightRepeatTimelineRunning : Boolean = false;


	// ===========================================================================================
	// control

	// create the behavior that comes with this control
	override protected var behavior = NudgerBehavior{};

	// ===========================================================================================
	// layout

	// build scene
	init
	{
		node = Group
		{
			content: [ rectangle, leftLabel, rightLabel ];
		}

		// make the sensitive area the right size
		rectangle.width = animationSpace + leftLabel.layoutBounds.width + rightLabel.layoutBounds.width + animationSpace;
	}
	
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

