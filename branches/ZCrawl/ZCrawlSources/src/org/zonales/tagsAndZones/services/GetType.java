/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.tagsAndZones.services;

import java.io.PrintWriter;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.crawlConfig.services.BaseService;
import org.zonales.errors.Errors;
import org.zonales.tagsAndZones.daos.TypeDao;

/**
 *
 * @author rodrigo
 */
public class GetType extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) {
        PrintWriter out = null;
        try {
            response.setContentType("text/javascript");
            out = response.getWriter();
            String name = request.getParameter("name");
            TypeDao typeDao = new TypeDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));

            if (typeDao == null) {
                out.print(Errors.NO_DB_FAILED);
                return;
            }

            if (typeDao.retrieve(name) == null) {
                out.print(Errors.DATA_NOT_FOUND);
            } else {
                out.print(typeDao.retrieveJson(name));
            }

        } catch (Exception ex) {
            StringBuilder stacktrace = new StringBuilder();
            for (StackTraceElement line : ex.getStackTrace()) {
                stacktrace.append(line.toString());
                stacktrace.append("\n");
            }
            Logger.getLogger(this.getClass().getName()).log(Level.SEVERE,
                    "EXCEPCION: {0}\nTRACE: {1}", new Object[]{ex, stacktrace.toString()});

            out.print(Errors.MONGODB_ERROR);
        } finally {
            if (out != null) {
                out.close();
            }
        }
    }
}
