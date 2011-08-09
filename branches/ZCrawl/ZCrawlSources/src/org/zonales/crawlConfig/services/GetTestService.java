/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.crawlConfig.services;

import org.zonales.crawlConfig.plugins.urlgetters.GetServiceURL;
import com.google.gson.Gson;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.crawlConfig.daos.ServiceDao;
import org.zonales.crawlConfig.objets.Plugin;
import org.zonales.crawlConfig.objets.Service;
import org.zonales.crawlParser.metadata.ZCrawling;

/**
 *
 * @author nacho
 */
public class GetTestService extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        try {
            response.setContentType("text/html");
            PrintWriter out = response.getWriter();
            String metadataJson = request.getParameter("q");
            ServiceDao serviceDao = new ServiceDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
            Service service;
            ZCrawling metadata = new ZCrawling();
            Gson metadataGson = new Gson();
            String urlServlet = "";

            //Mapeo en un objeto ZCrawling la metadata que vienen en formato JSON en el request
            metadata = metadataGson.fromJson(metadataJson, ZCrawling.class);

            Logger.getLogger(GetTestService.class.getName()).log(Level.INFO, "Buscando servicio según metadata {0}", new Object[]{metadataJson});

            //Recupero la configuración del servicio
            service = serviceDao.retrieve(metadata.getFuente());

            Logger.getLogger(GetTestService.class.getName()).log(Level.INFO, "Fuente {0}, servicio {1}", new Object[]{metadata.getFuente(), service.toString()});

            GetServiceURL getServiceURL = null;
            ArrayList<Plugin> plugins = service.getPlugins();
            for (Plugin plugin : plugins) {
                if ("URLGetter".equals(plugin.getType())) {
                    getServiceURL = (GetServiceURL) Class.forName(plugin.getClassName()).newInstance();
                    break;
                }
            }

            if (getServiceURL != null) {
                urlServlet = getServiceURL.getURL(metadata, service);
                out.print(urlServlet);
            } else {
                Logger.getLogger(GetTestService.class.getName()).log(Level.INFO, "No se recupero URL del servicio {0}", new Object[]{service.toString()});
            }
        } catch (Exception ex) {
            StringBuilder stacktrace = new StringBuilder();
            for (StackTraceElement line : ex.getStackTrace()) {
                stacktrace.append(line.toString());
                stacktrace.append("\n");
            }
            Logger.getLogger(GetTestService.class.getName()).log(Level.SEVERE, "EXCEPCION: {0}\nTRACE: {1}", new Object[]{ex, stacktrace.toString()});
        }


    }
}
