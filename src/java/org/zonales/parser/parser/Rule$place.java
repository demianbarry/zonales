/* -----------------------------------------------------------------------------
 * Rule$palce.java
 * -----------------------------------------------------------------------------
 *
 * Producer : com.parse2.aparse.Parser 2.0
 * Produced : Thu Aug 04 12:08:11 ART 2011
 *
 * -----------------------------------------------------------------------------
 */
package org.zonales.parser.parser;

import java.util.ArrayList;
import org.zonales.metadata.ZCrawling;
import org.zonales.tagsAndZones.daos.PlaceDao;

final public class Rule$place extends Rule {

    private Rule$place(String spelling, ArrayList<Rule> rules) {
        super(spelling, rules);
    }

    public Object accept(ZCrawling zcrawling, Visitor visitor) {
        return visitor.visit(zcrawling, this);
    }

    public static Rule$place parse(ParserContext context) {
        context.push("place");

        boolean parsed = true;
        int s0 = context.index;
        ArrayList<Rule> e0 = new ArrayList<Rule>();
        Rule rule;

        parsed = false;
        if (!parsed) {
            {
                ArrayList<Rule> e1 = new ArrayList<Rule>();
                int s1 = context.index;
                parsed = true;
                if (parsed) {
                    boolean f1 = true;
                    int c1 = 0;
                    for (int i1 = 0; i1 < 1 && f1; i1++) {
                        rule = Terminal$StringValue.parse(context, "[");
                        if ((f1 = rule != null)) {
                            e1.add(rule);
                            c1++;
                        }
                    }
                    parsed = c1 == 1;
                }
                if (parsed) {
                    boolean f1 = true;
                    int c1 = 0;
                    for (int i1 = 0; i1 < 1 && f1; i1++) {
                        rule = Rule$cadena.parse(context);
                        if (rule != null) {
                            PlaceDao placeDao = new PlaceDao(Globals.host, Globals.port, Globals.db);

                            if (placeDao.retrieveByExtendedString(rule.spelling.replace(", ", ",+").replace(" ", "_").replace("\"", "").replace(",+", ", ").toLowerCase()) == null) {
                                rule = null;
                                context.setMessage("El lugar no existe en la base de datos."
                                        + " Por favor, utilice las sugerencias que le ofrece la interfaz.\n");
                            }
                        }
                        if ((f1 = rule != null)) {
                            e1.add(rule);
                            c1++;
                        }
                    }
                    parsed = c1 == 1;
                }
                if (parsed) {
                    boolean f1 = true;
                    int c1 = 0;
                    for (int i1 = 0; i1 < 1 && f1; i1++) {
                        rule = Terminal$StringValue.parse(context, "]");
                        if ((f1 = rule != null)) {
                            e1.add(rule);
                            c1++;
                        }
                    }
                    parsed = c1 == 1;
                }
                if (parsed) {
                    e0.addAll(e1);
                } else {
                    context.index = s1;
                }
            }
        }

        rule = null;
        if (parsed) {
            rule = new Rule$place(context.text.substring(s0, context.index), e0);
        } else {
            context.index = s0;
        }
        
        context.pop("place", parsed);

        return (Rule$place) rule;
    }
}

/* -----------------------------------------------------------------------------
 * eof
 * -----------------------------------------------------------------------------
 */
