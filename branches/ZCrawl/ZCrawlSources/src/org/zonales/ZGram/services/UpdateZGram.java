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
import java.util.Date;
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

/**
 *
 * @author nacho
 */
public class UpdateZGram extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setCharacterEncoding("UTF-8");
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();
        String id = request.getParameter("id");
        int cod = Integer.valueOf(request.getParameter("newcod"));
        String msg = request.getParameter("newmsg");
        String metadataJson = request.getParameter("newmetadata");
        String verbatim = request.getParameter("newverbatim");
        String state = request.getParameter("newstate");
        Long ultimaExtracciónConDatos = request.getParameter("ultimaExtraccionConDatos") != null ? Long.valueOf(request.getParameter("ultimaExtraccionConDatos")) : null;
        Long ultimoHitDeExtracción = request.getParameter("ultimoHitDeExtraccion") != null ? Long.valueOf(request.getParameter("ultimoHitDeExtraccion")) : null;
        Integer ultimoCodigoDeExtraccion = request.getParameter("ultimoCodigoDeExtraccion") != null ? Integer.valueOf(request.getParameter("ultimoCodigoDeExtraccion")) : null;
        Gson metadataGson = new Gson();
        ZMessage zMessage = new ZMessage(cod, msg);
        

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Verbatim {0}", new Object[]{verbatim});
        //Mapeo en un objeto ZCrawling la metadata que vienen en formato JSON en el request
        ZGram zgram = metadataGson.fromJson(metadataJson, ZGram.class);
        zgram.setZmessage(zMessage);
        zgram.setVerbatim(verbatim);
        zgram.setEstado(state != null && state.length() > 0 ? state : State.GENERATED);
        zgram.setModificado((new Date()).getTime());
        zgram.setPeriodicidad(20);  //TODO: correfir, por ahora está duro por defecto

        if (ultimaExtracciónConDatos != null) {
            zgram.setUltimaExtraccionConDatos(ultimaExtracciónConDatos);
        }

        if (ultimoHitDeExtracción != null) {
            zgram.setUltimoHitDeExtraccion(ultimoHitDeExtracción);
        }

        if (ultimoCodigoDeExtraccion != null) {
            zgram.setUltimoCodigoDeExtraccion(ultimoCodigoDeExtraccion);
        }

        try {
            ZGramDao zGramDao = new ZGramDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
            zGramDao.update(id, zgram);
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Extracción actualizada {0}", new Object[]{zgram});
            out.print(ZMessages.SUCCESS.toString().replace("}", "") + ", \"id\": \"" + id + "\"}");
        } catch (MongoException ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Error actualizando extracción {0}: {1}", new Object[]{zgram, ex.getMessage()});
            out.print(ZMessages.SAVE_FAILED);
        } catch (Exception ex) {
            StringBuilder stacktrace = new StringBuilder();
            for (StackTraceElement line : ex.getStackTrace()) {
                stacktrace.append(line.toString());
                stacktrace.append("\n");
            }
            Logger.getLogger(this.getClass().getName()).log(Level.SEVERE,
                    "EXCEPCION: {0}\nTRACE: {1}", new Object[]{ex, stacktrace.toString()});

            out.print(ZMessages.NO_DB_FAILED);
        }
    }
}
