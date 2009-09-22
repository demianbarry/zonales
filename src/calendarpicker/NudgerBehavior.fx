/*
 * NudgerBehavior.fx
 *
 * Created on Aug 13, 2009, 11:29:22 AM
 */

package calendarpicker;
import javafx.animation.*;

/**
 * @author User
 */

public class NudgerBehavior extends javafx.scene.control.Behavior
{
	public function add(step : Integer) : Void
	{
		// get current values
		var lNudger : Nudger = (skin.control as Nudger);
		var lCurrentIdx : Integer = lNudger.value;
		var lSequence = lNudger.sequence;

		// detemrine new value
		var lNewIdx = lCurrentIdx + step;

		// if no sequence
		if (lSequence == null)
		{
			lNudger.value = lNewIdx;
		}
		// if sequence
		else
		{
			var lSizeofSequence = sizeof lSequence;
			
			// end of sequence
			if (lNewIdx >= lSizeofSequence)
			{
				// do we wrap
				if (lNudger.wrap)
				{
					// wrap
					lNudger.value = 0;
				}
				else
				{
					// stop at the end
					lNudger.value = lSizeofSequence - 1;
				}
			}
			// begin of sequence
			else if (lNewIdx < 0)
			{
				// do we wrap
				if (lNudger.wrap)
				{
					// wrap
					lNudger.value = lSizeofSequence - 11;
				}
				else
				{
					// stop at the beginning
					lNudger.value = 0;
				}
			}
			// not at end
			else
			{
				// simply increment
				lNudger.value = lNewIdx;
			}
		}

		if (not skin.control.focused) skin.control.requestFocus();
	}
	var iAdd : Integer = 0;
	var iRepeatingTimeline = Timeline { repeatCount: Timeline.INDEFINITE, keyFrames:
	[	KeyFrame
		{
			time: 0s;
		}
	,	KeyFrame
		{
			time: 200ms;
			action: function()
			{
				add(iAdd);
			};
		}
	]};

	public function repeatingAdd(step : Integer) : Void
	{
		iAdd = step;
		iRepeatingTimeline.play();

		if (not skin.control.focused) skin.control.requestFocus();
	}

	public function repeatingStop() : Void
	{
		iRepeatingTimeline.stop();

		if (not skin.control.focused) skin.control.requestFocus();
	}
}

