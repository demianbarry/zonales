/*
 * AutoAnimatingNode.fx
 *
 * Created on Aug 8, 2009, 8:39:32 AM
 */

package nl.innovationinvestments.javafx.scene;
import javafx.scene.*;

/**
 * @author Tom Eugelink
 *
 * A decorator for any node to add the simple animations
 *   AutoAnimatingNode { node: mynode }
 */
public class AutoAnimatingNode extends AutoAnimatingCustomNode
{
	public override function create(): Node
	{
		return node;
	}
}