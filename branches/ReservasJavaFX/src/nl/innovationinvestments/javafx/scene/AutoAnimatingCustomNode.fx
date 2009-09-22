/*
 * AutoAnimatingCustomNode.fx
 *
 * Created on Aug 8, 2009, 8:39:32 AM
 */

package nl.innovationinvestments.javafx.scene;

import javafx.scene.*;
import javafx.animation.*;

/**
 * @author Tom Eugelink
 *
 * extended CustomNode which adds simply animation and the management of the timelines.
 */

abstract public class AutoAnimatingCustomNode extends CustomNode
{
	// the node to be animated
    public var node: javafx.scene.Node = this;

	// speed
	var defaultDuration : Duration = 300ms;

    // ====================================================================
    // animated X

    /**
     * animate
     */
    public function animateXTo(value:Float, timespan:Duration, startupDelay:Duration)
	{
		// optimize: only if something is changed
		if (value.equals(node.translateX)) return;
		
    	// stop what we're doing
    	if (iTimelineX.running) iTimelineX.pause();

    	// start for new target
		var lInitialValue = node.translateX;
	    iTimelineX = Timeline {	keyFrames:
		[	KeyFrame
			{
				time: 0s;
				values: node.translateX => lInitialValue;
			}
		,	KeyFrame
			{
				time: startupDelay;
				values: node.translateX => lInitialValue;
				canSkip: true;
			}
		,	KeyFrame
			{
				time: startupDelay + timespan;
				values: node.translateX => value tween Interpolator.EASEOUT;
				action: function()
				{
					iTimelineX = null;
				};
			}
		]};
     	iTimelineX.play();
	}
    var iTimelineX: Timeline;

    /**
     * animate in 2 seconds
     */
    public function animateXTo(value:Float)
	{
        animateXTo(value, defaultDuration, 0s);
    }

    /**
     * no animation
     */
    public function jumpXTo(value:Float)
	{
    	// stop
    	if (iTimelineX.running) iTimelineX.stop();

        // jump
        node.translateX = value;
    }


    // ====================================================================
    // animated Y

    /**
     * animate
     */
    public function animateYTo(value:Float, timespan:Duration, startupDelay:Duration)
	{
		// optimize: only if something is changed
		if (value.equals(node.translateY)) return;

    	// stop what we're doing
    	if (iTimelineY.running) iTimelineY.pause();

    	// start for new target
		var lInitialValue = node.translateY;
	    iTimelineY = Timeline {	keyFrames:
		[	KeyFrame
			{
				time: 0s;
				values: node.translateY => lInitialValue;
			}
		,	KeyFrame
			{
				time: startupDelay;
				values: node.translateY => lInitialValue;
				canSkip: true
			}
		,	KeyFrame
			{
				time: startupDelay + timespan;
				values: node.translateY => value tween Interpolator.EASEOUT;
				action: function()
				{
					iTimelineY = null;
				};
			}
		]};
     	iTimelineY.play();
	}
    var iTimelineY: Timeline;

    /**
     * animate in 2 seconds
     */
    public function animateYTo(value:Float)
	{
        animateYTo(value, defaultDuration, 0s);
    }

    /**
     * no animation
     */
    public function jumpYTo(value:Float)
	{
    	// stop
    	if (iTimelineY.running) iTimelineY.stop();

        // jump
        node.translateY = value;
    }

    // ====================================================================
    // animated Opacity

    /**
     * animate
     */
    public function animateOpacityTo(value:Float, timespan:Duration, startupDelay:Duration)
	{
		// optimize: only if something is changed
		if (value.equals(node.opacity)) return;

    	// stop what we're doing
    	if (iTimelineOpacity.running) iTimelineOpacity.pause();

    	// start for new target
		var lInitialValue = node.opacity;
	    iTimelineOpacity = Timeline {	keyFrames:
		[	KeyFrame
			{
				time: 0s;
				values: node.opacity => lInitialValue;
			}
		,	KeyFrame
			{
				time: startupDelay;
				values: node.opacity => lInitialValue;
				canSkip: true
			}
		,	KeyFrame
			{
				time: startupDelay + timespan;
				values: node.opacity => value tween Interpolator.EASEOUT;
				action: function()
				{
					iTimelineOpacity = null;
				};
			}
		]};
     	iTimelineOpacity.play();
	}
    var iTimelineOpacity: Timeline;

    /**
     * animate in 2 seconds
     */
    public function animateOpacityTo(value:Float)
	{
        animateOpacityTo(value, defaultDuration, 0s);
    }

    /**
     * no animation
     */
    public function jumpOpacityTo(value:Float)
	{
    	// stop
    	if (iTimelineOpacity.running) iTimelineOpacity.stop();

        // jump
        node.opacity = value;
    }
}