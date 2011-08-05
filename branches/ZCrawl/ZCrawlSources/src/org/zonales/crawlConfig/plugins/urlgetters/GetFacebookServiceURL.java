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
public class GetFacebookServiceURL implements GetServiceURL {

    @Override
    public String getURL(ZCrawling metadata, Service service) {
        String urlServlet = "";
        String users = "";
        String keywords = "";
        String tags = "";
        String commenters = "";

        //Agrego uri del servicio
        urlServlet += service.getUri() + "?";
        //Si la localidad es nula, retorno error (Zone es el único campo oblicatorio en facebook)
        if (metadata.getLocalidad() == null) {
            return null;
        } else {
            //Agrego la zona
            urlServlet += "zone=" + metadata.getLocalidad();
        }
        //Si hay criterios, voy agregando los parámetros
        if (metadata.getCriterios() != null) {
            for (Criterio criterio : metadata.getCriterios()) {
                //Si hay usuarios, los agrego
                if (criterio.getDelUsuario() != null) {
                    users += criterio.getDelUsuario() + ",";
                }
                //Si hay keywords, los agrego
                if (criterio.getPalabras() != null) {
                    for (String keyword : criterio.getPalabras()) {
                        keywords += keyword + ",";
                    }
                }
            }
        }
        //Si hay no-criterios, voy agregando los parámetros
        if (metadata.getNoCriterios() != null) {
            for (Criterio criterio : metadata.getNoCriterios()) {
                //Si hay keywords, las agrego con el signo de admiración adelante
                if (criterio.getPalabras() != null) {
                    for (String keyword : criterio.getPalabras()) {
                        keywords += "!" + keyword + ",";
                    }
                }
            }
        }
        //Si hay tags, los agrego
        if (metadata.getTags() != null) {
            for (String tag : metadata.getTags()) {
                tags += tag + ",";
            }
        }
        //Si hay commenters, los voy agregando
        if (metadata.getComentarios() != null) {
            for (String comentario : metadata.getComentarios()) {
                commenters += comentario + ",";
            }
        }
        if (metadata.getFiltros() != null) {
            for (Filtro filtro : metadata.getFiltros()) {
                //Si existe el filtro minActions, lo agrego
                if (filtro.getMinActions() != null) {
                    urlServlet += "&minactions=" + filtro.getMinActions();
                }
            }
        }

        //Si agregué usuarios, los pongo en la URL
        if (!"".equals(users)) {
            urlServlet += "&users=" + users.substring(0, users.length() - 1);
        }
        //Si agregué keywords, los pongo en la URL
        if (!"".equals(keywords)) {
            urlServlet += "&keywords=" + keywords.substring(0, keywords.length() - 1);
        }
        //Si agregué tags, los pongo en la URL
        if (!"".equals(tags)) {
            urlServlet += "&tags=" + tags.substring(0, tags.length() - 1);
        }
        //Si agregué commenters, los pongo en la URL
        if (!"".equals(commenters)) {
            urlServlet += "&commenters=" + commenters.substring(0, commenters.length() - 1);
        }
        return urlServlet;
    }

}
