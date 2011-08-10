/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.crawlConfig.services;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.Properties;
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
public class PublishConfig extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();
        String name = request.getParameter("name");
        Boolean publish = Boolean.valueOf(request.getParameter("publish"));
        ServiceDao serviceDao = new ServiceDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
        Service service = serviceDao.retrieve(name);

        if (publish == true) {
            if (service.getState().equals("Generada")) {
                service.setState("Publicada");
                serviceDao.update(name, service);
                Logger.getLogger(GetTestService.class.getName()).log(Level.INFO, "Configuración publicada {0}", new Object[]{service.getName()});
                out.print(Errors.SUCCESS);
            } else {
                Logger.getLogger(GetTestService.class.getName()).log(Level.WARNING, "Estado previo erroneo {0}", new Object[]{service.getState()});
                out.print(Errors.PREVIUS_STATE_WRONG);
            }
        } else {
            if (service.getState().equals("Publicada")) {
                service.setState("Despublicada");
                serviceDao.update(name, service);
                Logger.getLogger(GetTestService.class.getName()).log(Level.INFO, "Configuración despublicada {0}", new Object[]{service.getName()});
                out.print(Errors.SUCCESS);
            } else {
                Logger.getLogger(GetTestService.class.getName()).log(Level.WARNING, "Estado previo erroneo {0}", new Object[]{service.getState()});
                out.print(Errors.PREVIUS_STATE_WRONG);
            }
        }

    }

}
