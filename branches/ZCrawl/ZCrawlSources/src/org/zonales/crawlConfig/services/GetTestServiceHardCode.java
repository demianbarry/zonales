/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.crawlConfig.services;

import com.google.gson.Gson;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.List;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.crawlConfig.daos.ServiceDao;
import org.zonales.crawlConfig.objets.Service;
import org.zonales.crawlParser.metadata.Criterio;
import org.zonales.crawlParser.metadata.Filtro;
import org.zonales.crawlParser.metadata.ZCrawling;

/**
 *
 * @author nacho
 */
public class GetTestServiceHardCode implements GetTestService {

    public GetTestServiceHardCode() {
    }

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();
        String metadataJson = request.getParameter("q");
        ServiceDao serviceDao = new ServiceDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
        Service service;
        ZCrawling metadata = new ZCrawling();
        Gson metadataGson = new Gson();
        String urlServlet = "";

        Logger.getLogger(GetTestServiceHardCode.class.getName()).log(Level.INFO, "Buscando servicio según metadata {0}", new Object[]{metadataJson});

        //Mapeo en un objeto ZCrawling la metadata que vienen en formato JSON en el request
        metadata = metadataGson.fromJson(metadataJson, ZCrawling.class);

        //Recupero la configuración del servicio
        service = serviceDao.retrieveService(metadata.getFuente());

        Logger.getLogger(GetTestServiceHardCode.class.getName()).log(Level.INFO, "Fuente {0}, servicio {1}", new Object[]{metadata.getFuente(), service.getName()});

        //Si la fuente es Facebook
        if ("facebook".equals(service.getName())) {
            if ((urlServlet = getURLFacebook(metadata, service)) == null) {
                out.print("Debe especificarse una Localidad"); //reemplazar por el error correspondiente
            } else {
                out.print(urlServlet);
            }
        } else 
        //Si la fuente es Twitter
        if ("twitter".equals(service.getName())) {
            if ((urlServlet = getURLTwitter(metadata, service)) == null) {
                out.print("Debe especificarse una Localidad"); //reemplazar por el error correspondiente
            } else {
                Logger.getLogger(GetTestServiceHardCode.class.getName()).log(Level.INFO, "URL de recuperación de test para Twitter: {0}", new Object[]{urlServlet});
                out.print(urlServlet);
            }
        } else 
        //Si la fuente es Twitter
        if ("feed".equals(service.getName())) {
            if ((urlServlet = getURLFeed(metadata, service)) == null) {
                out.print("Debe especificarse una Localidad"); //reemplazar por el error correspondiente
            } else {
                Logger.getLogger(GetTestServiceHardCode.class.getName()).log(Level.INFO, "URL de recuperación de test para Feeds: {0}", new Object[]{urlServlet});
                out.print(urlServlet);
            }
        }
    }

    private String getURLTwitter(ZCrawling metadata, Service service) {
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

    private String getURLFacebook(ZCrawling metadata, Service service) {
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
    
    private String getURLFeed(ZCrawling metadata, Service service) {
        if (metadata == null) {
            return null;
        }

        String urlServlet = service.getUri() + "?url=";
        urlServlet += metadata.getUriFuente();
        
        urlServlet += "&tags=" + metadata.getLocalidad();
        for(String tag : metadata.getTags()){
            urlServlet += ","+tag;
        }
        
        if (metadata.getCriterios() != null) {

            for (Criterio criterio : metadata.getCriterios()) {                

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
