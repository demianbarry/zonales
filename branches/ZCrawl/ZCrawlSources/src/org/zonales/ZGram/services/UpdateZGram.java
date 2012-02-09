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
import org.zonales.errors.ZMessages;

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
        String codStr = request.getParameter("newcod"); // != null ? Integer.valueOf(request.getParameter("newcod")) : null;
        String msg = request.getParameter("newmsg");
        String metadataJson = request.getParameter("newmetadata");
        String verbatim = request.getParameter("newverbatim");
        String state = request.getParameter("newstate");
        String modificadoPor = request.getParameter("modificadoPor");
        Long ultimaExtraccionConDatos = request.getParameter("ultimaExtraccionConDatos") != null ? Long.valueOf(request.getParameter("ultimaExtraccionConDatos")) : null;
        Long ultimoHitDeExtraccion = request.getParameter("ultimoHitDeExtraccion") != null ? Long.valueOf(request.getParameter("ultimoHitDeExtraccion")) : null;
        ZGramDao zGramDao = new ZGramDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));

        Gson metadataGson = new Gson();     

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Verbatim {0}", new Object[]{verbatim});
        //Mapeo en un objeto ZCrawling la metadata que vienen en formato JSON en el request
        Gson zGramGson = new Gson();
        ZGram zgram;

        if (metadataJson != null) {
            zgram = metadataGson.fromJson(metadataJson.replace("\\\"", "\""), ZGram.class);
        } else {
            zgram = new ZGram();
            zgram.setIncluyeComentarios(null);
            zgram.setTagsFuente(null);
            zgram.setSiosi(null);
            zgram.setNocriterio(null);
        }

        if (codStr != null) {
            Integer cod = Integer.valueOf(request.getParameter("newcod"));
            zgram.setCod(cod);
        }

        if (msg != null) {
            zgram.setMsg(msg);
        }

        if (verbatim != null) {
            zgram.setVerbatim(verbatim);
        }

        if (state != null) {
            zgram.setEstado(state);
        }

        if (ultimaExtraccionConDatos != null) {
            zgram.setUltimaExtraccionConDatos(ultimaExtraccionConDatos);
        }

        if (ultimoHitDeExtraccion != null) {
            zgram.setUltimoHitDeExtraccion(ultimoHitDeExtraccion);
        }

        if (modificadoPor != null) {
            zgram.setModificadoPor(modificadoPor);
        }


        //zgram.setPeriodicidad(20);  //TODO: correfir, por ahora está duro por defecto, siempre que actualizo la seteo en 20

        try {
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
