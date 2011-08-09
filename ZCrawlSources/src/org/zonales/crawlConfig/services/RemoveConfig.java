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

/**
 *
 * @author nacho
 */
public class RemoveConfig extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();
        String name = request.getParameter("name");
        ServiceDao serviceDao = new ServiceDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
        Service service = serviceDao.retrieve(name);

        Logger.getLogger(GetTestService.class.getName()).log(Level.INFO, "Anulando Configuración {0}", new Object[]{service.getName()});

        if (service.getState().equals("Despublicada") || service.getState().equals("Generada")) {
            service.setState("Anulada");
            serviceDao.update(name, service);
            Logger.getLogger(GetTestService.class.getName()).log(Level.INFO, "Configuración anulada {0}", new Object[]{service.getName()});
        } else {
            Logger.getLogger(GetTestService.class.getName()).log(Level.WARNING, "Estado previo erroneo {0}", new Object[]{service.getState()});
            out.print(props.getProperty("failed_message") + ": previus state wrong");
        }

    }

}
