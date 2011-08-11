/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.tagsAndZones.services;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.crawlConfig.services.BaseService;
import org.zonales.errors.Errors;
import org.zonales.tagsAndZones.daos.ZoneDao;

/**
 *
 * @author rodrigo
 */
public class GetZone extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
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

        } catch (IOException ex) {
            out.print(Errors.MONGODB_ERROR);
        } finally {
            out.close();
        }
    }
}
