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
import org.zonales.crawlConfig.services.BaseService;
import org.zonales.tagsAndZones.daos.TagDao;
import org.zonales.tagsAndZones.objects.Tag;
/**
 *
 * @author rodrigo
 */
public class SetTag extends BaseService {

   
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {

        response.setContentType("text/html");
        PrintWriter out = response.getWriter();
        String name = request.getParameter("name");
        //String parents = request.getParameter("parents");
        Tag tag = new Tag(name);
        //StringTokenizer parentsToken = new StringTokenizer(parents, ",");
        TagDao tagDao = new TagDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
        //out.print("Nombre: " + name + "<br>Parents: " + parents + "<br>");

        /*while (parentsToken.hasMoreTokens()) {
            String parent = parentsToken.nextToken();
            type.addParent(parent);
        }*/

        tag.setState("Generada");

        try {
            tagDao.save(tag);
            out.print(props.getProperty("success_message"));
        } catch (MongoException e) {
            out.print(props.getProperty("failed_message"));
        }



    }

}
