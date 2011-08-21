/* -----------------------------------------------------------------------------
 * Terminal$StringValue.java
 * -----------------------------------------------------------------------------
 *
 * Producer : com.parse2.aparse.Parser 2.0
 * Produced : Thu Aug 04 12:08:11 ART 2011
 *
 * -----------------------------------------------------------------------------
 */

package parser;

import java.util.ArrayList;
import org.zonales.metadata.ZCrawling;

public class Terminal$StringValue extends Rule
{
  private Terminal$StringValue(String spelling, ArrayList<Rule> rules)
  {
    super(spelling, rules);
  }

  public static Terminal$StringValue parse(
    ParserContext context, 
    String regex)
  {
    context.push("StringValue", regex);

    boolean parsed = true;

    Terminal$StringValue stringValue = null;
    try
    {
      String value = 
        context.text.substring(
          context.index, 
          context.index + regex.length());

      if ((parsed = value.equalsIgnoreCase(regex)))
      {
        context.index += regex.length();
        stringValue = new Terminal$StringValue(value, null);
      }
    }
    catch (IndexOutOfBoundsException e) {parsed = false;}

    context.pop("StringValue", parsed);

    return stringValue;
  }

  public Object accept(ZCrawling zcrawling, Visitor visitor)
  {
    return visitor.visit(zcrawling, this);
  }
}
/* -----------------------------------------------------------------------------
 * eof
 * -----------------------------------------------------------------------------
 */
