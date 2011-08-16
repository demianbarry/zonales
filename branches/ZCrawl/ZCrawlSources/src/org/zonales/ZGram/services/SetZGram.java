/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.ZGram.services;

import com.google.gson.Gson;
import org.zonales.BaseService;
import com.mongodb.MongoException;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.zonales.ZGram.ZGram;
import org.zonales.ZGram.daos.ZGramDao;
import org.zonales.crawlConfig.objets.State;
import org.zonales.errors.ZMessages;
import org.zonales.errors.ZMessage;
import org.zonales.metadata.ZCrawling;

/**
 *
 * @author nacho
 */
public class SetZGram extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();
        int cod = Integer.valueOf(request.getParameter("cod"));
        String msg = request.getParameter("msg");
        String metadataJson = request.getParameter("metadata");
        String verbatim = request.getParameter("verbatim");
        ZGram zgram = new ZGram();
        ZCrawling metadata = new ZCrawling();
        Gson metadataGson = new Gson();
        ZMessage zMessage = new ZMessage(cod, msg);

        //Mapeo en un objeto ZCrawling la metadata que vienen en formato JSON en el request
        metadata = metadataGson.fromJson(metadataJson, ZCrawling.class);

        ZGram zGram = new ZGram(zMessage, metadata, verbatim, State.GENERATED);

        try {
            ZGramDao zGramDao = new ZGramDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
            String id = zGramDao.save(zGram);
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Extracción guardada {0}", new Object[]{zGram});
            out.print("{\"ZMessage\": " + ZMessages.SUCCESS + ", \"id\": \"" + id + "\"}");
        } catch (MongoException ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Error guardando extracción {0}: {1}", new Object[]{zgram,ex.getMessage()});
            out.print(ZMessages.SAVE_FAILED);
        } catch (Exception ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.SEVERE, "Base de datos no disponible {0}", new Object[]{ex.getMessage()});
            out.print(ZMessages.NO_DB_FAILED);
        }
    }
}
