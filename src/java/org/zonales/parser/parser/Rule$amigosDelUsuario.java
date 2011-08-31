/* -----------------------------------------------------------------------------
 * Rule$amigosDelUsuario.java
 * -----------------------------------------------------------------------------
 *
 * Producer : com.parse2.aparse.Parser 2.0
 * Produced : Wed Aug 03 09:32:54 ART 2011
 *
 * -----------------------------------------------------------------------------
 */
package org.zonales.parser.parser;

import java.util.ArrayList;
import org.zonales.metadata.Criterio;
import org.zonales.metadata.ZCrawling;

final public class Rule$amigosDelUsuario extends Rule {

    private Rule$amigosDelUsuario(String spelling, ArrayList<Rule> rules) {
        super(spelling, rules);
    }

    public Object accept(ZCrawling zcrawling, Visitor visitor) {
        return visitor.visit(zcrawling, this);
    }

    public static Rule$amigosDelUsuario parse(ParserContext context) {
        context.push("amigosDelUsuario");

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
                        rule = Rule$usuario.parse(context);
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
            rule = new Rule$amigosDelUsuario(context.text.substring(s0, context.index), e0);
        } else {
            context.index = s0;
        }

        context.pop("amigosDelUsuario", parsed);

        Criterio criterio = new Criterio();
        criterio.setAmigosDe(rule.spelling);
        if (context.getZcrawling().getNocriterio()) {
            context.getZcrawling().getNoCriterios().add(criterio);
        } else {
            context.getZcrawling().getCriterios().add(criterio);
        }
        context.getZcrawling().setNocriterio(false);

        return (Rule$amigosDelUsuario) rule;
    }
}

/* -----------------------------------------------------------------------------
 * eof
 * -----------------------------------------------------------------------------
 */
