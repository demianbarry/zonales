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
import org.zonales.errors.Errors;

/**
 *
 * @author nacho
 */
public class GetConfig extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("text/javascript");
        PrintWriter out = response.getWriter();
        String name = request.getParameter("name");
        ServiceDao serviceDao = new ServiceDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
        String retrieve;

        if ("all".equals(name)) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo todas las configuraciones");
            retrieve = serviceDao.retrieveAll();
            if (retrieve != null) {
                out.print(retrieve);
            } else {
                Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "No se encontraron configuraciones");
                out.print(Errors.DATA_NOT_FOUND);
            }
        } else {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo la configuracion {0}", new Object[]{name});
            retrieve = serviceDao.retrieveJson(name);
            if (retrieve != null) {
                out.print(retrieve);
            } else {
                Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "No se encontró la configuracion {0}", new Object[]{name});
                out.print(Errors.DATA_NOT_FOUND);
            }
        }
    }

}
