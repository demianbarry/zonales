/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.crawlConfig.services;

import org.zonales.BaseService;
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
import org.zonales.errors.ZMessages;
import org.zonales.metadata.ZCrawling;

/**
 *
 * @author nacho
 */
public class GetTestService extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setCharacterEncoding("UTF-8");
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();
        try {
            String metadataJson = request.getParameter("q");
            String since = request.getParameter("since");
            ServiceDao serviceDao = new ServiceDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
            Service service;
            ZCrawling metadata = new ZCrawling();
            Gson metadataGson = new Gson();
            String urlServlet = "";

            //Mapeo en un objeto ZCrawling la metadata que vienen en formato JSON en el request
            metadata = metadataGson.fromJson(metadataJson, ZCrawling.class);

            if (since == null) {
                metadata.setUltimoHitDeExtraccion(null);
            }

            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Buscando servicio según metadata {0}", new Object[]{metadataJson});

            //Recupero la configuración del servicio
            service = serviceDao.retrieve(metadata.getFuente());

            if (service != null) {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Fuente {0}, servicio {1}", new Object[]{metadata.getFuente(), service.toString()});

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
                    out.print(ZMessages.appendMessage(ZMessages.SUCCESS, "\", url: \"" + urlServlet));
                } else {
                    Logger.getLogger(this.getClass().getName()).log(Level.INFO, "No se recupero URL del servicio {0}", new Object[]{service.toString()});
                    out.print(ZMessages.DATA_NOT_FOUND);
                }
            } else {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "No se recupero el servicio {0}");
                out.print(ZMessages.CONFIG_NOT_FOUND);
            }
        } catch (Exception ex) {
            StringBuilder stacktrace = new StringBuilder();
            for (StackTraceElement line : ex.getStackTrace()) {
                stacktrace.append(line.toString());
                stacktrace.append("\n");
            }
            Logger.getLogger(this.getClass().getName()).log(Level.SEVERE, "EXCEPCION: {0}\nTRACE: {1}", new Object[]{ex, stacktrace.toString()});
            out.print(ZMessages.UNKNOWN_ERROR);
        }


    }
}
