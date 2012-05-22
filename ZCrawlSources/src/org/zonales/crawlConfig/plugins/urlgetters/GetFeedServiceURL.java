/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.crawlConfig.plugins.urlgetters;

import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.zonales.crawlConfig.objets.Service;
import org.zonales.helpers.Utils;
import org.zonales.metadata.Criterio;
import org.zonales.metadata.ZCrawling;

/**
 *
 * @author nacho
 */
public class GetFeedServiceURL implements GetServiceURL {

    @Override
    public String getURL(ZCrawling metadata, Service service) {
        if (metadata == null) {
            return null;
        }

        String urlServlet = service.getUri() + "?url=" + metadata.getUriFuente() + "&formato=json";

        Boolean first = true;

        /*if (metadata.getLocalidad() != null && !metadata.getLocalidad().equals("")) {
        first = false;
        urlServlet += metadata.getLocalidad() + "+AND+";
        }*/

        if (metadata.getCriterios() != null) {

            for (Criterio criterio : metadata.getCriterios()) {

                if (criterio.getPalabras() != null) {
                    urlServlet += "&palabras=";
                    for (String palabra : criterio.getPalabras()) {
                        if (criterio.getPalabras().indexOf(palabra) != 0) {
                            urlServlet += ",";
                        }
                        urlServlet += palabra;
                    }
                }
            }
        }

        if (metadata.getNoCriterios() != null) {

            for (Criterio nocriterio : metadata.getNoCriterios()) {

                if (nocriterio.getPalabras() != null) {
                    urlServlet += "&nopalabras=";
                    for (String palabra : nocriterio.getPalabras()) {
                        if (nocriterio.getPalabras().indexOf(palabra) != 0) {
                            urlServlet += ",";
                        }
                        urlServlet += palabra;
                    }
                }
            }
        }

        urlServlet += "&zone=" + Utils.normalizeZone(metadata.getLocalidad());
        if (metadata.getTags() != null) {
            for (String tag : metadata.getTags()) {
                if (metadata.getTags().indexOf(tag) != 0) {
                    urlServlet += ",";
                } else {
                    urlServlet += "&tags=";
                }
                urlServlet += tag;
            }
        }

        /*if (metadata.getSourceLatitude() != null) {
        urlServlet += "&latitud="+ metadata.getSourceLatitude();             
        }
        
        if (metadata.getSourceLongitude() != null) {
        urlServlet += "&longitud="+ metadata.getSourceLongitude();
        }*/

        if (metadata.getPlace() != null) {
            try {
                urlServlet += "&place=" + URLEncoder.encode(metadata.getPlace(), "UTF-8");
            } catch (UnsupportedEncodingException ex) {
                Logger.getLogger(GetFeedServiceURL.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
        
        //Si agregué commenters, los pongo en la URL
        if (metadata.getIncluyeComentarios()) {
            urlServlet += "&comments=true";
        }

        Logger.getLogger(this.getClass().getName()).log(Level.SEVERE,
                "FEED URL GETTER RESULT: {0}", new Object[]{urlServlet});

        return urlServlet;
    }
}
