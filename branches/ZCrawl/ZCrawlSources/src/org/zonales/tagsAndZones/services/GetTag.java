/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.tagsAndZones.services;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.Properties;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.tagsAndZones.daos.TagDao;
/**
 *
 * @author rodrigo
 */
public class GetTag extends BaseService {


     public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("text/javascript");
        PrintWriter out = response.getWriter();
        String name = request.getParameter("name");
        TagDao tagDao = new TagDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));

        out.print(tagDao.retrieveJson(name));
    }
}
