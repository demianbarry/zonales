/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.crawlConfig.services;

import org.zonales.BaseService;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.crawlConfig.daos.ServiceDao;
import org.zonales.errors.ZMessages;

/**
 *
 * @author nacho
 */
public class GetConfig extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setCharacterEncoding("UTF-8");
        response.setContentType("text/javascript");
        PrintWriter out = response.getWriter();
        String name = request.getParameter("name");
        ServiceDao serviceDao = new ServiceDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
        String retrieve;

        if ("all".equals(name)) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo todas las configuraciones");
            retrieve = serviceDao.retrieveAll();
        } else if ("allNames".equals(name)) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo todos los nombres de las configuraciones");
            retrieve = serviceDao.retrieveAll(true);
        } else {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo la configuracion {0}", new Object[]{name});
            retrieve = serviceDao.retrieveJson(name);
        }
        if (retrieve != null) {
            out.print(retrieve);
        } else {
            Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "No se encontraron configuraciones");
            out.print(ZMessages.DATA_NOT_FOUND);
        }
    }

}
