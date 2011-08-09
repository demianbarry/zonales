/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.crawlConfig.services;

import com.mongodb.MongoException;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.Properties;
import java.util.StringTokenizer;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.crawlConfig.daos.ServiceDao;
import org.zonales.crawlConfig.objets.Service;

/**
 *
 * @author nacho
 */
public class SetConfig extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();
        String name = request.getParameter("name");
        String uri = request.getParameter("uri");
        String plugins = request.getParameter("plugins");
        String params = request.getParameter("params");
        Service service = new Service(name, uri);
        StringTokenizer paramToken = new StringTokenizer(params, ",;");
        StringTokenizer pluginToken = new StringTokenizer(plugins, ",;");
        ServiceDao serviceDao = new ServiceDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));

        //out.print("Nombre: " + name + "<br>Uri: " + uri + "<br>Params: " + params + "<br>");        

        while (paramToken.hasMoreTokens()) {
            String paramName = paramToken.nextToken();
            Boolean paramRequired = Boolean.valueOf(paramToken.nextToken());
            //out.print("Nombre parametro: " + paramName + "<br>Required: " + paramRequired + "<br>");
            service.addParam(paramName, paramRequired);
        }

        while (pluginToken.hasMoreTokens()) {
            String pluginName = pluginToken.nextToken();
            String pluginType = pluginToken.nextToken();
            //out.print("Nombre parametro: " + paramName + "<br>Required: " + paramRequired + "<br>");
            service.addPlugin(pluginName, pluginType); //TODO: Manejar el error de typos en caso de que no sea uno de la lista
        }

        service.setState("Generada");

        Logger.getLogger(GetTestService.class.getName()).log(Level.INFO, "Guardando servicio según parametros {0}", new Object[]{service});

        try {
            serviceDao.save(service);
            Logger.getLogger(GetTestService.class.getName()).log(Level.INFO, "Servicio guardado {0}", new Object[]{service});
            out.print(props.getProperty("success_message"));
        } catch (MongoException ex) {
            Logger.getLogger(GetTestService.class.getName()).log(Level.INFO, "Error guardado servicio {0}: {1}", new Object[]{service,ex.getMessage()});
            out.print(props.getProperty("failed_message") + ": " + ex.getMessage());
        }
    }
}
