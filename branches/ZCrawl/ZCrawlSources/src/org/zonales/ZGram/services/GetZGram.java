/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.ZGram.services;

import org.zonales.BaseService;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.ZGram.daos.ZGramDao;
import org.zonales.errors.ZMessages;

/**
 *
 * @author nacho
 */
public class GetZGram extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("text/javascript");
        PrintWriter out = response.getWriter();
        String id = request.getParameter("id");
        ZGramDao zGramDao = new ZGramDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
        String retrieve;

        if ("all".equals(id)) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo todas las extracciones");
            retrieve = zGramDao.retrieveAll();
        } else if ("allNames".equals(id)) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo todos datos básicos de las extracciones");
            retrieve = zGramDao.retrieveAll(true);
        } else {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo la extracción {0}", new Object[]{id});
            retrieve = zGramDao.retrieveJson(id);
        }
        if (retrieve != null) {
            out.print(retrieve);
        } else {
            Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "No se encontraron configuraciones");
            out.print(ZMessages.DATA_NOT_FOUND);
        }
    }

}
