/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.tagsAndZones.services;

import com.mongodb.MongoException;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.List;
import java.util.Properties;
import java.util.StringTokenizer;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.crawlConfig.objets.State;
import org.zonales.BaseService;
import org.zonales.errors.Errors;
import org.zonales.tagsAndZones.daos.TagDao;
import org.zonales.tagsAndZones.daos.TypeDao;
import org.zonales.tagsAndZones.daos.ZoneDao;


import org.zonales.tagsAndZones.objects.Zone;

/**
 *
 * @author rodrigo
 */
public class SetZone extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {

        response.setContentType("text/html");
        PrintWriter out = response.getWriter();
        String name = request.getParameter("name");
        String parent = request.getParameter("parent");
        String type = request.getParameter("type");
        String centerlat = request.getParameter("centerlat");
        String centerlon = request.getParameter("centerlon");
        String zoomlevel = request.getParameter("zoomlevel");
        Zone zone = null;

        ZoneDao zoneDao = new ZoneDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));

        if (parent != null) {

            TagDao tagDao = new TagDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
            if (tagDao.retrieve(parent) == null) {
                out.print(Errors.DATA_NOT_FOUND);
            }

        }

        if (type != null) {
            TypeDao typeDao = new TypeDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
            if (typeDao.retrieve(name) == null) {
                out.print(Errors.DATA_NOT_FOUND);
            }
        }

        if (centerlat != null && centerlon != null && zoomlevel != null) {
            zone = new Zone(Float.parseFloat(centerlat), Float.parseFloat(centerlon), Integer.parseInt(zoomlevel));
           
        } else {
            zone = new Zone(name);
           
        }

        try {
            zoneDao.save(zone);
            zone.setState(State.GENERATED);
            out.print(props.getProperty("success_message"));
        } catch (MongoException e) {
            out.print(Errors.MONGODB_ERROR);
        }



    }
}
