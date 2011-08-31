/* -----------------------------------------------------------------------------
 * Rule$tag.java
 * -----------------------------------------------------------------------------
 *
 * Producer : com.parse2.aparse.Parser 2.0
 * Produced : Thu Aug 04 12:08:11 ART 2011
 *
 * -----------------------------------------------------------------------------
 */
package org.zonales.parser.parser;

import java.io.File;
import java.io.FileInputStream;
import java.io.InputStream;
import java.util.ArrayList;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.zonales.metadata.ZCrawling;
import org.zonales.tagsAndZones.daos.TagDao;
import org.zonales.tagsAndZones.daos.ZoneDao;

final public class Rule$tag extends Rule {

    private Rule$tag(String spelling, ArrayList<Rule> rules) {
        super(spelling, rules);
    }

    public Object accept(ZCrawling zcrawling, Visitor visitor) {
        return visitor.visit(zcrawling, this);
    }

    public static Rule$tag parse(ParserContext context) {
        context.push("tag");

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
                        rule = Rule$cadena.parse(context);
                        if (rule != null) {
                            TagDao tagDao = new TagDao("localhost", 27017, "crawl");
                            if (tagDao.retrieve(rule.spelling.replace(" ", "_").replace("\"", "").toLowerCase()) == null) {
                                rule = null;
                                context.setMessage("Alguno de los tags indicados no existen en la base de datos."
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
                    e0.addAll(e1);
                } else {
                    context.index = s1;
                }
            }
        }

        rule = null;
        if (parsed) {
            rule = new Rule$tag(context.text.substring(s0, context.index), e0);
        } else {
            context.index = s0;
        }

        context.pop("tag", parsed);

        return (Rule$tag) rule;
    }
}

/* -----------------------------------------------------------------------------
 * eof
 * -----------------------------------------------------------------------------
 */
