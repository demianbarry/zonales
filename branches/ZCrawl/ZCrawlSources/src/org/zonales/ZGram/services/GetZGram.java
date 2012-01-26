/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.ZGram.services;

import com.google.gson.Gson;
import org.zonales.BaseService;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.ZGram.ZGramFilter;
import org.zonales.ZGram.daos.ZGramDao;
import org.zonales.errors.ZMessages;

/**
 *
 * @author nacho
 */
public class GetZGram extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setCharacterEncoding("UTF-8");
        response.setContentType("text/javascript");
        PrintWriter out = response.getWriter();
        String id = request.getParameter("id");
        String filtrosJson = request.getParameter("filtros");
        ZGramDao zGramDao = new ZGramDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
        String retrieve = null;
        ZGramFilter filtros = null;

        if (filtrosJson != null) {
            Gson filtrosGson = new Gson();
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo extracción segun filtros JSON {0}", new Object[]{filtrosJson});
            filtros = filtrosGson.fromJson(filtrosJson.replace("\\\"", "\"").replace("\\'", "\""), ZGramFilter.class);
        }

        if (id != null) {
            if ("all".equals(id)) {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo todas las extracciones");
                retrieve = zGramDao.retrieveAll();
            } else if ("allNames".equals(id)) {
                if (filtrosJson == null) {
                    Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo todos los datos básicos de las extracciones");
                    retrieve = zGramDao.retrieveAll(true);
                } else {
                    Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo todos los datos básicos de las extracciones segun filtros");
                    retrieve = zGramDao.retrieveJson(filtros, true);
                }
            } else {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo la extracción {0}", new Object[]{id});
                retrieve = zGramDao.retrieveJson(id);
            }
        } else if (filtrosJson != null) {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Obteniendo extracción segun filtros {0}", new Object[]{filtrosJson});
                retrieve = zGramDao.retrieveJson(filtros, false);
        } else {
            retrieve = null;
            Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "Debe especificarse id o filtros");
            out.print(ZMessages.PARAM_REQUIRED_FAILED);
            return;
        }

        if (retrieve != null) {
            out.print(retrieve);
        } else {
            Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "No se encontraron configuraciones");
            out.print(ZMessages.DATA_NOT_FOUND);
        }
    }

}
