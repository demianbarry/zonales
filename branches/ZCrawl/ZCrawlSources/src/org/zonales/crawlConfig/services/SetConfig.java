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
import org.zonales.crawlConfig.objets.State;
import org.zonales.errors.Errors;
import org.zonales.errors.Error;

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

        
        //out.print("Nombre: " + name + "<br>Uri: " + uri + "<br>Params: " + params + "<br>");        

        if (params != null) {
            StringTokenizer paramToken = new StringTokenizer(params, ",;");
            while (paramToken.hasMoreTokens()) {
                String paramName = paramToken.nextToken();
                Boolean paramRequired = Boolean.valueOf(paramToken.nextToken());
                //out.print("Nombre parametro: " + paramName + "<br>Required: " + paramRequired + "<br>");
                service.addParam(paramName, paramRequired);
            }
        }

        if (plugins != null) {
            StringTokenizer pluginToken = new StringTokenizer(plugins, ",;");
            while (pluginToken.hasMoreTokens()) {
                String pluginName = pluginToken.nextToken();
                String pluginType = pluginToken.nextToken();
                //out.print("Nombre parametro: " + paramName + "<br>Required: " + paramRequired + "<br>");
                service.addPlugin(pluginName, pluginType); //TODO: Manejar el error de typos en caso de que no sea uno de la lista
            }
        } else {
            //Debe haber al menos un plugin
            Error error = Errors.PARAM_REQUIRED_FAILED;
            error.setMsg("Plugin param is required");
            out.print(error);
            return;
        }

        service.setState(State.GENERATED);

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Guardando servicio según parametros {0}", new Object[]{service});

        try {
            ServiceDao serviceDao = new ServiceDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
            serviceDao.save(service);
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Servicio guardado {0}", new Object[]{service});
            out.print(Errors.SUCCESS);
        } catch (MongoException ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Error guardado servicio {0}: {1}", new Object[]{service,ex.getMessage()});
            out.print(Errors.SAVE_FAILED);
        } catch (Exception ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.SEVERE, "Base de datos no disponible {0}", new Object[]{ex.getMessage()});
            out.print(Errors.NO_DB_FAILED);
        }
    }
}
