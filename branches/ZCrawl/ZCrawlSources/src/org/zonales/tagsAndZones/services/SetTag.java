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
import org.zonales.tagsAndZones.daos.TagDao;
import org.zonales.tagsAndZones.objects.Tag;
/**
 *
 * @author rodrigo
 */
public class SetTag extends BaseService {

   
    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {

        response.setContentType("text/html");
        PrintWriter out = response.getWriter();
        String name = request.getParameter("name");
        Tag tag = new Tag(name);
        TagDao tagDao = new TagDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
        try {
            tagDao.save(tag);
            tag.setState(State.GENERATED);
            out.print(props.getProperty("success_message"));
        } catch (MongoException e) {
            out.print(Errors.MONGODB_ERROR);
        }



    }

}
