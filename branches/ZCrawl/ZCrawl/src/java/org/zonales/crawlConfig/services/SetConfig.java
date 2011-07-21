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
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.crawlConfig.daos.ServiceDao;
import org.zonales.crawlConfig.objets.Service;

/**
 *
 * @author nacho
 */
public class SetConfig {

    public SetConfig() {
    }

    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException {
        PrintWriter out = response.getWriter();
        response.setContentType("text/html");
        String name = request.getParameter("name");
        String uri = request.getParameter("uri");
        String params = request.getParameter("params");
        Service service = new Service(name, uri);
        StringTokenizer paramToken = new StringTokenizer(params, ",;");
        ServiceDao serviceDao = new ServiceDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));

        //out.print("Nombre: " + name + "<br>Uri: " + uri + "<br>Params: " + params + "<br>");
        
        while (paramToken.hasMoreTokens()) {
            String paramName = paramToken.nextToken();
            Boolean paramRequired = Boolean.valueOf(paramToken.nextToken());
            //out.print("Nombre parametro: " + paramName + "<br>Required: " + paramRequired + "<br>");
            service.addParam(paramName, paramRequired);
        }
        
        try {
            serviceDao.saveService(service);
            out.print(props.getProperty("success_message"));
        } catch (MongoException e) {
            out.print(props.getProperty("failed_message"));
        }
    }
}
