/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.crawlConfig.services;

import com.google.gson.Gson;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.Properties;
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
        PrintWriter out = response.getWriter();
        response.setContentType("text/html");
        String metadataJson = request.getParameter("q");
        ServiceDao serviceDao = new ServiceDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
        Service service;
        ZCrawling metadata = new ZCrawling();
        Gson metadataGson = new Gson();
        String urlServlet = "";
        String users = "";
        String keywords = "";
        String tags = "";
        String commenters = "";

        //Mapeo en un objeto ZCrawling la metadata que vienen en formato JSON en el request
        metadata = metadataGson.fromJson(metadataJson, ZCrawling.class);

        //Recupero la configuración del servicio
        service = serviceDao.retrieveService(metadata.getFuente());

        //Si la fuente es Facebook
        if ("facebook".equals(service.getName())) {
            //Agrego uri del servicio
            urlServlet += service.getUri() + "?";
            //Si la localidad es nula, retorno error (Zone es el único campo oblicatorio en facebook)
            if (metadata.getLocalidad() == null) {
                out.print("Debe especificarse una Localidad"); //reemplazar por el error correspondiente
                return;
            } else {
                //Agrego la zona
                urlServlet += "zone=" + metadata.getLocalidad();
            }
            //Si hay criterios, voy agregando los parámetros
            if (metadata.getCriterios() != null) {
                for (Criterio criterio: metadata.getCriterios()) {
                    //Si hay usuarios, los agrego
                    if (criterio.getDelUsuario() != null) {
                        users += criterio.getDelUsuario() + ",";
                    }
                    //Si hay keywords, los agrego
                    if (criterio.getPalabras() != null) {
                        for (String keyword: criterio.getPalabras()) {
                            keywords +=  keyword + ",";
                        }
                    }
                }
            }
            //Si hay no-criterios, voy agregando los parámetros
            if (metadata.getNoCriterios() != null) {
                for (Criterio criterio: metadata.getNoCriterios()) {
                    //Si hay keywords, las agrego con el signo de admiración adelante
                    if (criterio.getPalabras() != null) {
                        for (String keyword: criterio.getPalabras()) {
                            keywords += "!" + keyword + ",";
                        }
                    }
                }
            }
            //Si hay tags, los agrego
            if (metadata.getTags() != null) {
                for (String tag: metadata.getTags()) {
                    tags += tag + ",";
                }
            }
            //Si hay commenters, los voy agregando
            if (metadata.getComentarios() != null) {
                for (String comentario: metadata.getComentarios()) {
                    commenters += comentario + ",";
                }
            }
            if (metadata.getFiltros() != null) {
                for (Filtro filtro: metadata.getFiltros()) {
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
        }

        out.print(urlServlet);
    }
}
