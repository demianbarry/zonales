/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.crawlConfig.services;

import org.zonales.BaseService;
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
import org.zonales.ZGram.ZGram;
import org.zonales.ZGram.daos.ZGramDao;
import org.zonales.crawlConfig.daos.ServiceDao;
import org.zonales.crawlConfig.objets.Plugin;
import org.zonales.crawlConfig.objets.Service;
import org.zonales.crawlConfig.plugins.publishers.Publisher;
import org.zonales.errors.ZMessages;

/**
 *
 * @author nacho
 */
public class PublishService extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();
        try {
            String zgramId = request.getParameter("id");
            ZGramDao zgramDao = new ZGramDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
            ServiceDao serviceDao = new ServiceDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
            ZGram zgram = (new Gson()).fromJson(zgramDao.retrieveJson(zgramId), ZGram.class);
            
            Service service;            
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Buscando servicio según metadata {0}", new Object[]{zgram.toString()});

            //Recupero la configuración del servicio
            service = serviceDao.retrieve(zgram.getFuente());

            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Fuente {0}, servicio {1}", new Object[]{zgram.getFuente(), service.toString()});

            Publisher publisher = null;
            ArrayList<Plugin> plugins = service.getPlugins();
            for (Plugin plugin : plugins) {
                if ("Publisher".equals(plugin.getType())) {
                    publisher = (Publisher) Class.forName(plugin.getClassName()).newInstance();
                    break;
                }
            }

            if (publisher != null) {                
                out.print(publisher.publish(zgram, zgramId, props));
            } else {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "No se recupero URL del servicio {0}", new Object[]{service.toString()});
                out.print(ZMessages.DATA_NOT_FOUND);
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
