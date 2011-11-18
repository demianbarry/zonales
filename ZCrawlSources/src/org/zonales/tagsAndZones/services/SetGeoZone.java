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
import org.zonales.tagsAndZones.daos.GeoZoneDao;

/**
 *
 * @author rodrigo
 */
public class SetGeoZone extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) {
        PrintWriter out = null;
        try {
            response.setCharacterEncoding("UTF-8");
            response.setContentType("text/javascript");
            out = response.getWriter();

            String json = request.getParameter("geoJson");

            GeoZoneDao geoZoneDao = new GeoZoneDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
            String retrieve = null;

            if (json != null) {
                geoZoneDao.save(json);
            }

            out.print(ZMessages.SUCCESS);

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
