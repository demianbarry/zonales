/*
 * Nudger.fx
 *
 * Created on Jul 27, 2009, 8:52:01 AM
 */

package calendarpicker;
import javafx.scene.*;
import javafx.scene.control.*;
import javafx.util.Sequences;
import java.lang.IllegalArgumentException;


/**
 * @author Tom Eugelink
 */



/**
 *
 */
public class Nudger extends Control
{
	// nudge over a sequence
	public var sequence : Object[]
	on replace
	{
		// if sequence is set
		if (sequence != null)
		{
			// value must be in range of sequence
			if ( value < 0 ) value = 0;
			if ( value >= (sizeof sequence) ) value = (sizeof sequence) - 1;
		}

	};

	// active value
	public var value : Integer
	on replace
	{
		// if a sequence is set
		if (sequence != null)
		{
			// value must be in range of sequence
			if ( value < 0 ) value = 0;
			if ( value >= (sizeof sequence) ) value = (sizeof sequence) - 1;
		}
	};

	// wrap in the sequence or not
	public var wrap : Boolean = false;

	// allow to get focus
	public override var focusTraversable = true;

	// ===========================================================================================
	// control

	override function create(): Node
	{
        // create the default skin
		skin = NudgerSkin{ };

        // continue
		super.create();
	}
}

