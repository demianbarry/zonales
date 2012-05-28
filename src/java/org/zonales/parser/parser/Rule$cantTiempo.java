package org.zonales.parser.parser;

/* -----------------------------------------------------------------------------
 * Rule$cantTiempo.java
 * -----------------------------------------------------------------------------
 *
 * Producer : com.parse2.aparse.Parser 2.0
 * Produced : Thu May 24 11:59:23 ART 2012
 *
 * -----------------------------------------------------------------------------
 */

import java.util.ArrayList;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.zonales.metadata.ZCrawling;

final public class Rule$cantTiempo extends Rule
{
  private Rule$cantTiempo(String spelling, ArrayList<Rule> rules)
  {
    super(spelling, rules);
  }

  public Object accept(ZCrawling zcrawling, Visitor visitor) {
        return visitor.visit(zcrawling, this);
    }

  public static Rule$cantTiempo parse(ParserContext context)
  {
    context.push("cantTiempo");

    boolean parsed = true;
    int s0 = context.index;
    ArrayList<Rule> e0 = new ArrayList<Rule>();
    Rule rule;

    parsed = false;
    if (!parsed)
    {
      {
        ArrayList<Rule> e1 = new ArrayList<Rule>();
        int s1 = context.index;
        parsed = true;
        if (parsed)
        {
          boolean f1 = true;
          int c1 = 0;
          for (int i1 = 0; i1 < 1 && f1; i1++)
          {
            rule = Rule$int.parse(context);
            if ((f1 = rule != null))
            {
              e1.add(rule);
              c1++;
            }
          }
          parsed = c1 == 1;
        }
        if (parsed)
          e0.addAll(e1);
        else
          context.index = s1;
      }
    }

    rule = null;
    if (parsed)
      rule = new Rule$cantTiempo(context.text.substring(s0, context.index), e0);
    else
      context.index = s0;
    
    if(rule != null) {
        Logger.getLogger("CantTiempo").log(Level.INFO, "Seteo cantidad de tiempo: {0}", rule.spelling);
        context.getZcrawling().setPeriodicidad(Integer.valueOf(rule.spelling));
    }

    context.pop("cantTiempo", parsed);

    return (Rule$cantTiempo)rule;
  }
}

/* -----------------------------------------------------------------------------
 * eof
 * -----------------------------------------------------------------------------
 */
