/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.crawlConfig.plugins.urlgetters;

import java.util.ArrayList;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.zonales.crawlConfig.objets.Service;
import org.zonales.helpers.Utils;
import org.zonales.metadata.Criterio;
import org.zonales.metadata.Filtro;
import org.zonales.metadata.ZCrawling;

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

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "FB: Medatada {0}", metadata);

        //Agrego uri del servicio
        urlServlet += service.getUri() + "?";
        //Si la localidad es nula, retorno error (Zone es el único campo oblicatorio en facebook)
        if (metadata.getLocalidad() == null) {
            return null;
        } else {
            //Agrego la zona
            urlServlet += "zone=" + Utils.normalizeZone(metadata.getLocalidad());
        }
        //Si hay criterios, voy agregando los parámetros
        List<String> usuarios = new ArrayList<String>();
        //List<Double> latitudes = new ArrayList<Double>();
        //List<Double> longitudes = new ArrayList<Double>();
        List<String> extendedStrings = new ArrayList<String>();

        if (metadata.getCriterios() != null) {

            for (Criterio criterio : metadata.getCriterios()) {
                //Si hay usuarios, los agrego
                if (criterio.getDeLosUsuarios() != null) {
                    for (String usuario : criterio.getDeLosUsuarios()) {
                        usuarios.add(usuario);
                    }
                }

                //Agrego las latitudes
                /*if (criterio.getDeLosUsuariosLatitudes() != null) {
                for(Double latitud : criterio.getDeLosUsuariosLatitudes()){
                latitudes.add(latitud);
                }
                }
                
                //Agrego las longitudes
                if (criterio.getDeLosUsuariosLongitudes() != null) {
                for(Double longitud : criterio.getDeLosUsuariosLongitudes()){
                longitudes.add(longitud);
                }
                }*/

                //Agrego las longitudes
                if (criterio.getDeLosUsuariosPlaces() != null) {
                    for(String extendedString : criterio.getDeLosUsuariosPlaces()){
                        extendedStrings.add(extendedString);
                    }
                }

                //Si hay keywords, los agrego
                if (criterio.getPalabras() != null) {
                    keywords += keywords.length() > 0 ? "," : "";
                    for (String keyword : criterio.getPalabras()) {
                        keywords += keyword + ",";
                    }
                }
            }
        }

        if (usuarios.size() > 0) {
            int key = 0;
            for (String usuario : usuarios) {
                users += users.length() > 0 ? ";" : "";
                users += usuario;
                if (extendedStrings.size() == usuarios.size()) {
                    if (extendedStrings.get(key) != null) {
                        users += "[" + extendedStrings.get(key) + "]";
                    }
                }
                key++;
            }
        }

        //Si hay no-criterios, voy agregando los parámetros
        if (metadata.getNoCriterios() != null) {
            for (Criterio criterio : metadata.getNoCriterios()) {
                //Si hay keywords, las agrego con el signo de admiración adelante
                if (criterio.getPalabras() != null) {
                    keywords += keywords.length() > 0 ? "," : "";
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
            urlServlet += "&users=" + users; //users.substring(0, users.length() - 1);
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
        if (metadata.getIncluyeComentarios()) {
            urlServlet += "&getComments=true";
        }
        
        if (metadata.getExtraePublicacionesDeTerceros()) {
            urlServlet += "&getThirdPartyPosts=true";
        }

        if (!"".equals(commenters)) {
            urlServlet += "&commenters=" + commenters.substring(0, commenters.length() - 1);
        }

        if (metadata.getUltimoHitDeExtraccion() != null) {
            urlServlet += "&since=" + metadata.getUltimoHitDeExtraccion();
        }
        return urlServlet;
    }
}
