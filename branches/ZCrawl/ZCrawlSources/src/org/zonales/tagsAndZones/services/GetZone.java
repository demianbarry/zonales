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
import org.zonales.errors.ZMessages;
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
            response.setCharacterEncoding("UTF-8");
            response.setContentType("text/javascript");
            out = response.getWriter();

            String name = request.getParameter("name");
            ZoneDao zoneDao = new ZoneDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
            String retrieve;
            if (zoneDao == null) {
                out.print(ZMessages.NO_DB_FAILED);
                return;
            }

             if ("all".equals(name)) {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo todos las zonas");
                retrieve = zoneDao.retrieveAll();
            } else if ("allNames".equals(name)) {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo todos los nombres de las zonas");
                retrieve = zoneDao.retrieveAll(true);
            } else {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo la zona {0}", new Object[]{name});
                retrieve = zoneDao.retrieveJson(name);
            }
            if (retrieve != null) {
                out.print(retrieve);
            } else {
                Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "No se encontraron zonas");
                out.print(ZMessages.DATA_NOT_FOUND);
            }

        } catch (Exception ex) {
            StringBuilder stacktrace = new StringBuilder();
            for (StackTraceElement line : ex.getStackTrace()) {
                stacktrace.append(line.toString());
                stacktrace.append("\n");
            }
            Logger.getLogger(this.getClass().getName()).log(Level.SEVERE,
                    "EXCEPCION: {0}\nTRACE: {1}", new Object[]{ex, stacktrace.toString()});

            out.print(ZMessages.MONGODB_ERROR);            
        } finally {
            if (out != null) {
                out.close();
            }
        }
    }
}
