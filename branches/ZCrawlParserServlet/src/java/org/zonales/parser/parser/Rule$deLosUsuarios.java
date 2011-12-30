/* -----------------------------------------------------------------------------
 * Rule$deLosUsuarios.java
 * -----------------------------------------------------------------------------
 *
 * Producer : com.parse2.aparse.Parser 2.0
 * Produced : Wed Aug 03 09:32:54 ART 2011
 *
 * -----------------------------------------------------------------------------
 */
package org.zonales.parser.parser;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
import org.zonales.metadata.Criterio;
import org.zonales.metadata.ZCrawling;

final public class Rule$deLosUsuarios extends Rule {

    private Rule$deLosUsuarios(String spelling, ArrayList<Rule> rules) {
        super(spelling, rules);
    }

    public Object accept(ZCrawling zcrawling, Visitor visitor) {
        return visitor.visit(zcrawling, this);
    }

    public static Rule$deLosUsuarios parse(ParserContext context) {
        context.push("deLosUsuarios");

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
                        rule = Rule$usuarios.parse(context);
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
            rule = new Rule$deLosUsuarios(context.text.substring(s0, context.index), e0);
        } else {
            context.index = s0;
        }

        context.pop("deLosUsuarios", parsed);

        if (rule != null) {
            Pattern pattern = Pattern.compile("\\[([-|+]?([0-9]*\\.[0-9]+)),([-|+]?([0-9]*\\.[0-9]+))\\]");            

            Criterio criterio = new Criterio();
            List<String> usuarios = new ArrayList<String>();
            for(String usuario : Arrays.asList(pattern.matcher(rule.spelling).replaceAll("").split(","))){
                usuarios.add(usuario.trim());
            }
            List<Double> latitudes = new ArrayList<Double>();
            List<Double> longitudes = new ArrayList<Double>();

            int index;
            for (String usuario : usuarios) {
                index = rule.spelling.indexOf(usuario) + usuario.length();
                while(index < rule.spelling.length() && rule.spelling.charAt(index) != '[' && rule.spelling.charAt(index) != ','){
                    index++;
                }
                if(index < rule.spelling.length() && rule.spelling.charAt(index) == '['){                    
                    latitudes.add(Double.valueOf(rule.spelling.substring(index+1, (index = rule.spelling.indexOf(',', index)))));
                    longitudes.add(Double.valueOf(rule.spelling.substring(index+1, (index = rule.spelling.indexOf(']', index)))));
                } else {
                    latitudes.add(null);
                    longitudes.add(null);
                }
                
            }
            criterio.setDeLosUsuarios(usuarios);
            criterio.setDeLosUsuariosLatitudes(latitudes);
            criterio.setDeLosUsuariosLongitudes(longitudes);
            if (context.getZcrawling().getNocriterio()) {
                context.getZcrawling().getNoCriterios().add(criterio);
            } else {
                context.getZcrawling().getCriterios().add(criterio);
            }
            context.getZcrawling().setNocriterio(false);
        }

        return (Rule$deLosUsuarios) rule;
    }
}

/* -----------------------------------------------------------------------------
 * eof
 * -----------------------------------------------------------------------------
 */
