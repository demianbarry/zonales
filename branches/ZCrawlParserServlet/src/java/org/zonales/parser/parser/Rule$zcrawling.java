/* -----------------------------------------------------------------------------
 * Rule$zcrawling.java
 * -----------------------------------------------------------------------------
 *
 * Producer : com.parse2.aparse.Parser 2.0
 * Produced : Fri Sep 23 12:10:58 ART 2011
 *
 * -----------------------------------------------------------------------------
 */
package org.zonales.parser.parser;

import java.util.ArrayList;
import org.zonales.metadata.ZCrawling;

final public class Rule$zcrawling extends Rule {

    private Rule$zcrawling(String spelling, ArrayList<Rule> rules) {
        super(spelling, rules);
    }

    public Object accept(ZCrawling zcrawling, Visitor visitor) {
        return visitor.visit(zcrawling, this);
    }

    public static Rule$zcrawling parse(ParserContext context) {
        context.push("zcrawling");

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
                        parsed = false;
                        if (!parsed) {
                            {
                                ArrayList<Rule> e2 = new ArrayList<Rule>();
                                int s2 = context.index;
                                parsed = true;
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Terminal$StringValue.parse(context, "**");
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$descripcion.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Terminal$StringValue.parse(context, "**");
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$mspace.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    e1.addAll(e2);
                                } else {
                                    context.index = s2;
                                }
                            }
                        }
                        if (parsed) {
                            c1++;
                        }
                        f1 = parsed;
                    }
                    parsed = true;
                }
                if (parsed) {
                    boolean f1 = true;
                    int c1 = 0;
                    for (int i1 = 0; i1 < 1 && f1; i1++) {
                        rule = Rule$space.parse(context);
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
                        rule = Terminal$StringValue.parse(context, "extraer");
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
                        rule = Rule$mspace.parse(context);
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
                        rule = Terminal$StringValue.parse(context, "para");
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
                        rule = Rule$mspace.parse(context);
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
                        rule = Terminal$StringValue.parse(context, "la");
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
                        rule = Rule$mspace.parse(context);
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
                        rule = Terminal$StringValue.parse(context, "localidad");
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
                        rule = Rule$mspace.parse(context);
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
                        rule = Rule$localidad.parse(context);
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
                        rule = Rule$mspace.parse(context);
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
                        rule = Terminal$StringValue.parse(context, "mediante");
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
                        rule = Rule$mspace.parse(context);
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
                        rule = Terminal$StringValue.parse(context, "la");
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
                        rule = Rule$mspace.parse(context);
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
                        rule = Terminal$StringValue.parse(context, "fuente");
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
                        rule = Rule$mspace.parse(context);
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
                        rule = Rule$fuente.parse(context);
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
                        parsed = false;
                        if (!parsed) {
                            {
                                ArrayList<Rule> e2 = new ArrayList<Rule>();
                                int s2 = context.index;
                                parsed = true;
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$mspace.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Terminal$StringValue.parse(context, "asignando");
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$mspace.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Terminal$StringValue.parse(context, "los");
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$mspace.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Terminal$StringValue.parse(context, "tags");
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$mspace.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$tags.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    e1.addAll(e2);
                                } else {
                                    context.index = s2;
                                }
                            }
                        }
                        if (parsed) {
                            c1++;
                        }
                        f1 = parsed;
                    }
                    parsed = true;
                }
                if (parsed) {
                    boolean f1 = true;
                    int c1 = 0;
                    for (int i1 = 0; i1 < 1 && f1; i1++) {
                        parsed = false;
                        if (!parsed) {
                            {
                                ArrayList<Rule> e2 = new ArrayList<Rule>();
                                int s2 = context.index;
                                parsed = true;
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$mspace.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Terminal$StringValue.parse(context, "a");
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$mspace.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Terminal$StringValue.parse(context, "partir");
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$mspace.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$criterios.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    e1.addAll(e2);
                                } else {
                                    context.index = s2;
                                }
                            }
                        }
                        if (parsed) {
                            c1++;
                        }
                        f1 = parsed;
                    }
                    parsed = true;
                }
                if (parsed) {
                    boolean f1 = true;
                    int c1 = 0;
                    for (int i1 = 0; i1 < 1 && f1; i1++) {
                        parsed = false;
                        if (!parsed) {
                            {
                                ArrayList<Rule> e2 = new ArrayList<Rule>();
                                int s2 = context.index;
                                parsed = true;
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$incluyeComentarios.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    e1.addAll(e2);
                                } else {
                                    context.index = s2;
                                }
                            }
                        }
                        if (parsed) {
                            c1++;
                        }
                        f1 = parsed;
                    }
                    parsed = true;
                }
                if (parsed) {
                    boolean f1 = true;
                    int c1 = 0;
                    for (int i1 = 0; i1 < 1 && f1; i1++) {
                        parsed = false;
                        if (!parsed) {
                            {
                                ArrayList<Rule> e2 = new ArrayList<Rule>();
                                int s2 = context.index;
                                parsed = true;
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$mspace.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Terminal$StringValue.parse(context, "y");
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$mspace.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Terminal$StringValue.parse(context, "filtrando");
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$mspace.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Terminal$StringValue.parse(context, "por");
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$mspace.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$filtros.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    e1.addAll(e2);
                                } else {
                                    context.index = s2;
                                }
                            }
                        }
                        if (parsed) {
                            c1++;
                        }
                        f1 = parsed;
                    }
                    parsed = true;
                }
                if (parsed) {
                    boolean f1 = true;
                    int c1 = 0;
                    for (int i1 = 0; i1 < 1 && f1; i1++) {
                        parsed = false;
                        if (!parsed) {
                            {
                                ArrayList<Rule> e2 = new ArrayList<Rule>();
                                int s2 = context.index;
                                parsed = true;
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$incluyeTagsFuente.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    e1.addAll(e2);
                                } else {
                                    context.index = s2;
                                }
                            }
                        }
                        if (parsed) {
                            c1++;
                        }
                        f1 = parsed;
                    }
                    parsed = true;
                }
                if (parsed) {
                    boolean f1 = true;
                    int c1 = 0;
                    for (int i1 = 0; i1 < 1 && f1; i1++) {
                        parsed = false;
                        if (!parsed) {
                            {
                                ArrayList<Rule> e2 = new ArrayList<Rule>();
                                int s2 = context.index;
                                parsed = true;
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$mspace.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Terminal$StringValue.parse(context, "cada");
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$mspace.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    boolean f2 = true;
                                    int c2 = 0;
                                    for (int i2 = 0; i2 < 1 && f2; i2++) {
                                        rule = Rule$temporalidad.parse(context);
                                        if ((f2 = rule != null)) {
                                            e2.add(rule);
                                            c2++;
                                        }
                                    }
                                    parsed = c2 == 1;
                                }
                                if (parsed) {
                                    e1.addAll(e2);
                                } else {
                                    context.index = s2;
                                }
                            }
                        }
                        if (parsed) {
                            c1++;
                        }
                        f1 = parsed;
                    }
                    parsed = true;
                }
                if (parsed) {
                    boolean f1 = true;
                    int c1 = 0;
                    for (int i1 = 0; i1 < 1 && f1; i1++) {
                        rule = Rule$space.parse(context);
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
                        rule = Terminal$StringValue.parse(context, ".");
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
                        rule = Rule$space.parse(context);
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
            rule = new Rule$zcrawling(context.text.substring(s0, context.index), e0);
        } else {
            context.index = s0;
        }

        context.pop("zcrawling", parsed);

        return (Rule$zcrawling) rule;
    }
}

/*
 * -----------------------------------------------------------------------------
 * eof
 * -----------------------------------------------------------------------------
 */
