/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.tagsAndZones.services;

import com.mongodb.MongoException;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.Properties;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.crawlConfig.objets.State;
import org.zonales.BaseService;
import org.zonales.errors.Errors;
import org.zonales.tagsAndZones.daos.TypeDao;
import org.zonales.tagsAndZones.objects.Type;

/**
 *
 * @author rodrigo
 */
public class SetType extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {

        response.setContentType("text/html");
        PrintWriter out = response.getWriter();
        String name = request.getParameter("name");
        Type type = new Type(name);
        TypeDao typeDao = new TypeDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));

        if (typeDao == null) {
            out.print(Errors.NO_DB_FAILED);
            return;
        }
        try {
            typeDao.save(type);
            type.setState(State.GENERATED);
            out.print(props.getProperty("success_message"));
        } catch (MongoException e) {
           out.print(Errors.MONGODB_ERROR);
        }



    }
}
