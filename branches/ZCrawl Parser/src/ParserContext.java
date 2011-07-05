/* -----------------------------------------------------------------------------
 * ParserContext.java
 * -----------------------------------------------------------------------------
 *
 * Producer : com.parse2.aparse.Parser 2.0
 * Produced : Tue Jul 05 12:09:22 ART 2011
 *
 * -----------------------------------------------------------------------------
 */

import java.util.Stack;

public class ParserContext
{
  public final String text;
  public int index;

  private Stack<String> callStack = new Stack<String>();
  private Stack<String> errorStack = new Stack<String>();
  private int level = 0;
  private int error = -1;
  private int start = 0;

  private final boolean traceOn;

  public ParserContext(String text, boolean traceOn)
  {
    this.text = text;
    this.traceOn = traceOn;
    index = 0;
  }

  public void push(String rulename)
  {
    push(rulename, "");
  }

  public void push(String rulename, String trace)
  {
    callStack.push(rulename);
    start = index;
    if (traceOn)
    {
      System.out.println("-> " + ++level + ": " + rulename + "(" + (trace != null ? trace : "") + ")");
      System.out.println(index + ": " + text.substring(index, index + 10 > text.length() ? text.length() : index + 10).replaceAll("[^\\p{Print}]", " "));
    }
  }

  public void pop(String function, boolean result)
  {
    callStack.pop();
    if (traceOn)
    {
      System.out.println("<- " + level-- + ": " + function + "(" + (result ? "true," : "false,") + (index - start) + ")");
    }
    if (!result)
    {
      if (index > error)
      {
        error = index;
        errorStack = new Stack<String>();
        errorStack.addAll(callStack);
      }
    }
    else
    {
      if (index > error) error = -1;
    }
  }

  public Stack<String> getErrorStack()
  {
    return errorStack;
  }

  public int getErrorIndex()
  {
    return error;
  }
}

/* -----------------------------------------------------------------------------
 * eof
 * -----------------------------------------------------------------------------
 */
