/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.crawlConfig.plugins.urlgetters;

import org.zonales.crawlConfig.objets.Service;
import org.zonales.crawlParser.metadata.Criterio;
import org.zonales.crawlParser.metadata.Filtro;
import org.zonales.crawlParser.metadata.ZCrawling;

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

        String urlServlet = service.getUri() + "?q=";

        Boolean first = true;

        /*if (metadata.getLocalidad() != null && !metadata.getLocalidad().equals("")) {
        first = false;
        urlServlet += metadata.getLocalidad() + "+AND+";
        }*/

        if (metadata.getCriterios() != null) {

            for (Criterio criterio : metadata.getCriterios()) {
                if (!first) {
                    urlServlet += "+";
                    if (!criterio.getSiosi()) {
                        urlServlet += "OR+";
                    }
                } else {
                    first = false;
                }

                if (criterio.getDelUsuario() != null) {
                    urlServlet += "from:" + criterio.getDelUsuario();
                }
                if (criterio.getPalabras() != null) {
                    for (String palabra : criterio.getPalabras()) {
                        if (criterio.getPalabras().indexOf(palabra) != 0) {
                            urlServlet += "+";
                            if (!criterio.getSiosi()) {
                                urlServlet += "OR+";
                            }
                        } else {

                        }
                        urlServlet += palabra.trim();
                    }
                }
            }
        }
        if (metadata.getFiltros() != null) {
            for (Filtro filtro : metadata.getFiltros()) {
                if (filtro.getMinActions() != null && filtro.getMinActions() > 0) {
                }
            }
            if (metadata.getTags() != null) {
                Boolean firstTag = true;
                for (String tag : metadata.getTags()) {
                    if (firstTag) {
                        urlServlet += "&tags=" + tag;
                        firstTag = false;
                    } else {
                        urlServlet += "," + tag;
                    }
                }
            }
        }

        return urlServlet;
    }

}
