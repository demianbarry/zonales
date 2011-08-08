/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.crawlConfig.services;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.Properties;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.crawlConfig.daos.ServiceDao;
import org.zonales.crawlConfig.objets.Service;

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
            } else {
                out.print(props.getProperty("failed_message") + ": previus state wrong");
            }
        } else {
            if (service.getState().equals("Publicada")) {
                service.setState("Despublicada");
                serviceDao.update(name, service);
            } else {
                out.print(props.getProperty("failed_message") + ": previus state wrong");
            }
        }

    }

}
