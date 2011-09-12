/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.ZGram.services;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.Properties;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.BaseService;
import org.zonales.ZGram.daos.ZGramDao;

/**
 *
 * @author nacho
 */
public class GetLastHit extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setCharacterEncoding("UTF-8");
        response.setContentType("text/javascript");
        PrintWriter out = response.getWriter();
        String id = request.getParameter("id");
        ZGramDao zGramDao = new ZGramDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));

        Long resp = zGramDao.retrieveLastExtractionHit(id);

        if (resp != null) {
            out.print(resp);
        } else {
            out.print("0");
        }
    }

}
