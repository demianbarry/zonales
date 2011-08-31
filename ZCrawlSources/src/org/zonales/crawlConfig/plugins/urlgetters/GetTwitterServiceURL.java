/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.crawlConfig.plugins.urlgetters;

import org.zonales.crawlConfig.objets.Service;
import org.zonales.metadata.Criterio;
import org.zonales.metadata.Filtro;
import org.zonales.metadata.ZCrawling;

/**
 *
 * @author nacho
 */
public class GetTwitterServiceURL implements GetServiceURL {

    @Override
    public String getURL(ZCrawling metadata, Service service) {
        if (metadata == null) {
            return null;
        }

        String urlServlet = service.getUri() 
                                + "?zone="
                                + metadata.getLocalidad()
                                + "&q=";

        if (metadata.getCriterios() != null) {

            for (Criterio criterio : metadata.getCriterios()) {

                if (metadata.getCriterios().indexOf(criterio) != 0) {
                    urlServlet += "+";
                }

                urlServlet += "(";

                if (criterio.getDeLosUsuarios() != null) {
                    for (String usuario : criterio.getDeLosUsuarios()) {
                        if (criterio.getDeLosUsuarios().indexOf(usuario) != 0) {
                            urlServlet += "+OR+";
                        }
                        urlServlet += "from:" + usuario;
                    }
                }

                if (criterio.getPalabras() != null) {
                    for (String palabra : criterio.getPalabras()) {
                        if (criterio.getPalabras().indexOf(palabra) != 0) {
                            urlServlet += "+";
                            if (!criterio.getSiosi()) {
                                urlServlet += "OR+";
                            }
                        }
                        urlServlet += palabra.trim();
                    }
                }
                urlServlet += ")";
            }
        }

        if (metadata.getFiltros() != null) {
            for (Filtro filtro : metadata.getFiltros()) {
                if (filtro.getMinActions() != null && filtro.getMinActions() > 0) {
                }
            }
        }

        if (metadata.getTags() != null) {
            for (String tag : metadata.getTags()) {
                if (metadata.getTags().indexOf(tag) == 0) {
                    urlServlet += "&tags=";
                } else {
                    urlServlet += ",";
                }
                urlServlet += tag;
            }
        }

        if (metadata.getNoCriterios() != null) {

            for (Criterio criterio : metadata.getNoCriterios()) {

                if (metadata.getNoCriterios().indexOf(criterio) != 0) {
                    urlServlet += "+";
                }

                if (criterio.getPalabras() != null) {
                    for (String palabra : criterio.getPalabras()) {
                        if (criterio.getPalabras().indexOf(palabra) != 0) {
                            urlServlet += "+-";
                        }
                        urlServlet += palabra.trim();
                    }
                }
            }
        }

        return urlServlet;
    }
}
