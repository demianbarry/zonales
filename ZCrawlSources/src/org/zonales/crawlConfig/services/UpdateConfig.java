/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.crawlConfig.services;

import org.zonales.BaseService;
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
import org.zonales.errors.ZMessages;

/**
 *
 * @author nacho
 */
public class UpdateConfig extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setCharacterEncoding("UTF-8");
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();
        String name = request.getParameter("name");
        String newName = request.getParameter("newname");
        String newUri = request.getParameter("newuri");
        String newPlugins = request.getParameter("newplugins");
        String newParams = request.getParameter("newparams");
        Service service = new Service();
        Service newService = new Service();
        StringTokenizer paramToken;
        StringTokenizer pluginToken;
        ServiceDao serviceDao = new ServiceDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));

        if (name == null) {
            Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "nombre del servicio requerido");
            out.print(ZMessages.PARAM_REQUIRED_FAILED);
            return;
        }

        service = serviceDao.retrieve(name);
        if (State.PUBLISHED.equals(service.getState())) {
            Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "Estado previo erroneo");
            out.print(ZMessages.PREVIUS_STATE_WRONG);
            return;
        }

        if (newName != null) {
            newService.setName(newName);
        }
        if (newUri != null) {
            newService.setUri(newUri);
        }
        if (newPlugins != null) {
            pluginToken = new StringTokenizer(newPlugins, ",;");
            while (pluginToken.hasMoreTokens()) {
                String pluginName = pluginToken.nextToken();
                String pluginType = pluginToken.nextToken();
                newService.addPlugin(pluginName, pluginType);  //TODO: Manejar error de tipo
            }
        }
        if (newParams != null) {
            paramToken = new StringTokenizer(newParams, ",;");
            while (paramToken.hasMoreTokens()) {
                String paramName = paramToken.nextToken();
                Boolean paramRequired = Boolean.valueOf(paramToken.nextToken());
                newService.addParam(paramName, paramRequired);
            }
        }
        
        newService.setState(State.GENERATED);

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Actualizando configuración {0} con nuevos parametros {1}", new Object[]{name, newService});

        try {
            serviceDao.update(name, newService);
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Configuración Actualizada {0} con nuevos parametros {1}", new Object[]{name, newService});
            out.print(ZMessages.SUCCESS);
        } catch (MongoException ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "Error actualizando configuración {0} con nuevos parametros {1}", new Object[]{name, newService});
            out.print(ZMessages.UPDATE_FAILED);
        }
    }

}
