/* -----------------------------------------------------------------------------
 * Rule$localidad.java
 * -----------------------------------------------------------------------------
 *
 * Producer : com.parse2.aparse.Parser 2.0
 * Produced : Thu Aug 04 12:08:11 ART 2011
 *
 * -----------------------------------------------------------------------------
 */
package org.zonales.parser.parser;

import com.google.gson.Gson;
import java.io.IOException;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.util.ArrayList;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.zonales.errors.ZMessages;
import org.zonales.helpers.ConnHelper;
import org.zonales.metadata.ZCrawling;
import org.zonales.scheduler.UrlService;
import org.zonales.tagsAndZones.daos.ZoneDao;

final public class Rule$localidad extends Rule {

    private Rule$localidad(String spelling, ArrayList<Rule> rules) {
        super(spelling, rules);
    }

    public Object accept(ZCrawling zcrawling, Visitor visitor) {
        return visitor.visit(zcrawling, this);
    }

    public static Rule$localidad parse(ParserContext context) {
        String zoneKey = "";
        context.push("localidad");

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
                            //Recupero la clave utilizando la cadena extendida
                            try {
                                String url = Globals.nodeUrl + "/zones/getKey?word=" + rule.spelling.trim().replace("\"", "");
                                Logger.getLogger(Rule$localidad.class.getName()).log(Level.INFO, "Recuperación Key de zona. URL: {0}", url);
                                HttpURLConnection connection = ConnHelper.getURLConnection(url, Globals.timeout);
                                int code = connection.getResponseCode();
                                Logger.getLogger(Rule$localidad.class.getName()).log(Level.INFO, "Recuperación Key de zona. Codigo respuesta: {0}", code);
                                if (code == 200) {                
                                    zoneKey = ConnHelper.getStringFromInpurStream(connection.getInputStream());
                                    Logger.getLogger(Rule$localidad.class.getName()).log(Level.INFO, "Recuperación Key de zona. Key: {0}", zoneKey);
                                }
                                connection.disconnect();
                            } catch (MalformedURLException ex) {
                                Logger.getLogger(Rule$localidad.class.getName()).log(Level.SEVERE, null, ex);
                            } catch (IOException ex) {
                                Logger.getLogger(Rule$localidad.class.getName()).log(Level.SEVERE, null, ex);
                            }
                            
                            ZoneDao zoneDao = new ZoneDao(Globals.host, Globals.port, Globals.db);

                            if (zoneDao.retrieveByExtendedString(zoneKey) == null) {
                                rule = null;
                                context.setMessage("La localidad no existe en la base de datos."
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
            rule = new Rule$localidad(context.text.substring(s0, context.index), e0);
        } else {
            context.index = s0;
        }
        
        if(rule != null) {
            context.getZcrawling().setLocalidad(zoneKey);
        }

        context.pop("localidad", parsed);

        return (Rule$localidad) rule;
    }
}

/* -----------------------------------------------------------------------------
 * eof
 * -----------------------------------------------------------------------------
 */
