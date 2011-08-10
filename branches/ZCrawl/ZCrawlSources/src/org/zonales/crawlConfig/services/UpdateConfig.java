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
import org.zonales.errors.Errors;

/**
 *
 * @author nacho
 */
public class UpdateConfig extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();
        String name = request.getParameter("name");
        String newName = request.getParameter("newname");
        String newUri = request.getParameter("newuri");
        String newPlugins = request.getParameter("newplugins");
        String newParams = request.getParameter("newparams");
        String newState = request.getParameter("newstate");
        Service service = new Service();
        StringTokenizer paramToken;
        StringTokenizer pluginToken;
        ServiceDao serviceDao = new ServiceDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));

        if (name == null) {
            out.print("Config name is required");
            return;
        }

        if (newName != null) {
            service.setName(newName);
        }
        if (newUri != null) {
            service.setUri(newUri);
        }
        if (newPlugins != null) {
            pluginToken = new StringTokenizer(newPlugins, ",;");
            while (pluginToken.hasMoreTokens()) {
                String pluginName = pluginToken.nextToken();
                String pluginType = pluginToken.nextToken();
                service.addPlugin(pluginName, pluginType);  //TODO: Manejar error de tipo
            }
        }
        if (newParams != null) {
            paramToken = new StringTokenizer(newParams, ",;");
            while (paramToken.hasMoreTokens()) {
                String paramName = paramToken.nextToken();
                Boolean paramRequired = Boolean.valueOf(paramToken.nextToken());
                service.addParam(paramName, paramRequired);
            }
        }
        if (newState != null) {
            service.setState(newState);
        }

        Logger.getLogger(GetTestService.class.getName()).log(Level.INFO, "Actualizando configuración {0} con nuevos parametros {1}", new Object[]{name, service});

        try {
            serviceDao.update(name, service);
            Logger.getLogger(GetTestService.class.getName()).log(Level.INFO, "Configuración Actualizada {0} con nuevos parametros {1}", new Object[]{name, service});
            out.print(Errors.SUCCESS);
        } catch (MongoException ex) {
            Logger.getLogger(GetTestService.class.getName()).log(Level.WARNING, "Error actualizando configuración {0} con nuevos parametros {1}", new Object[]{name, service});
            out.print(Errors.UPDATE_FAILED);
        }
    }

}
