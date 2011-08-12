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
import org.zonales.BaseService;
import org.zonales.errors.Errors;
import org.zonales.tagsAndZones.daos.ZoneDao;

/**
 *
 * @author rodrigo
 */
public class GetZone extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) {
        PrintWriter out = null;
        try {
            response.setContentType("text/javascript");
            out = response.getWriter();

            String name = request.getParameter("name");
            ZoneDao zoneDao = new ZoneDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));

            if (zoneDao == null) {
                out.print(Errors.NO_DB_FAILED);
                return;
            }

            if (zoneDao.retrieve(name) == null) {
                out.print(Errors.DATA_NOT_FOUND);
            } else {
                out.print(zoneDao.retrieveJson(name));
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
