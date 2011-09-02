/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.scheduler;

import com.google.gson.Gson;
import java.io.IOException;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.util.Date;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.apache.solr.client.solrj.SolrServerException;
import org.quartz.Job;
import org.quartz.JobDetail;
import org.quartz.JobExecutionContext;
import org.quartz.JobExecutionException;
import org.zonales.entities.Posts;
import org.zonales.errors.ZMessage;
import org.zonales.errors.ZMessages;
import org.zonales.helpers.ConnHelper;
import org.zonales.scheduler.exceptions.ExtractException;

/**
 *
 * @author nacho
 */
public class ZPublisher implements Job {

    @Override
    public void execute(JobExecutionContext jec) throws JobExecutionException {
        String zGramId = "";
        String zCrawlSourcesURL = "";
        Integer timeout = 0;
        Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "SCHEDULER: Ejecutando Job: ");
        try {
            JobDetail jobDetail = jec.getJobDetail();
            zGramId = jobDetail.getJobDataMap().getString("zGramId");
            String solrUrl = jobDetail.getJobDataMap().getString("solrURL");
            ZSolrServer server = new ZSolrServer(solrUrl);
            String metadata = jobDetail.getJobDataMap().getString("metadata");
            zCrawlSourcesURL = jobDetail.getJobDataMap().getString("ZCrawlSourcesURL");
            timeout = jobDetail.getJobDataMap().getInt("timeout");
            ZExtractor extractor = new ZExtractor();
            Posts posts = extractor.extract(metadata, zCrawlSourcesURL, timeout);

            Long ultimoHitDeExtraccion = new Date().getTime();
            Integer ultimoCodigoDeExtraccion = ZMessages.SUCCESS.getCod();

            String parameters = "id=" + zGramId + "&ultimoHitDeExtraccion=" + ultimoHitDeExtraccion + "&ultimoCodigoDeExtraccion=" + ultimoCodigoDeExtraccion;

            if (!posts.getPost().isEmpty()) {
                Long ultimaExtraccionConDatos = new Date().getTime();
                parameters += "&ultimaExtraccionConDatos=" + ultimaExtraccionConDatos;
            }

            Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "Parámetros actualizacion ZGram: ", parameters);
            
            HttpURLConnection connection = ConnHelper.getURLConnection(zCrawlSourcesURL + "updateZGram" + parameters, timeout);
            String zMessageJson;
            Gson zMessageGson = new Gson();
            ZMessage zmessage = new ZMessage();

            int code = connection.getResponseCode();
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Actualización gramática - Código de respuesta: {0}", code);
            if (code == 200) {
                zMessageJson = ConnHelper.getStringFromInpurStream(connection.getInputStream());
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Actualización gramática - Respuesta: {0}", zMessageJson);
                zmessage = zMessageGson.fromJson(zMessageJson, ZMessage.class);
                connection.disconnect();
            } else {
                Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "Error de conección actualizando gramatica");
                return;
            }

            Gson postGson = new Gson();
            String postsJson = postGson.toJson(posts, Posts.class);
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Posts: {0}", postsJson);
            try {
                server.indexPosts(posts);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Posts indexados");
            } catch (SolrServerException ex) {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Error indexando posts: {0}", ex);
            }
        } catch (MalformedURLException ex) {
            Logger.getLogger(ZPublisher.class.getName()).log(Level.SEVERE, "Error en JOB Malformen URL: ", ex);
        } catch (IOException ex) {
            Logger.getLogger(ZPublisher.class.getName()).log(Level.SEVERE, "Error en JOB IO: ", ex);
        } catch (ExtractException ex) {
            Long ultimoHitDeExtraccion = new Date().getTime();
            Integer ultimoCodigoDeExtraccion = ex.getZmessage().getCod();

            String parameters = "id=" + zGramId + "&ultimoHitDeExtraccion=" + ultimoHitDeExtraccion + "&ultimoCodigoDeExtraccion=" + ultimoCodigoDeExtraccion;

            HttpURLConnection connection;
            try {
                connection = ConnHelper.getURLConnection(zCrawlSourcesURL + "updateZGram" + parameters, timeout);
                String zMessageJson;
                Gson zMessageGson = new Gson();
                ZMessage zmessage = new ZMessage();

                int code = connection.getResponseCode();
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Actualización gramática - Código de respuesta: {0}", code);
                if (code == 200) {
                    zMessageJson = ConnHelper.getStringFromInpurStream(connection.getInputStream());
                    Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Actualización gramática - Respuesta: {0}", zMessageJson);
                    zmessage = zMessageGson.fromJson(zMessageJson, ZMessage.class);
                    connection.disconnect();
                } else {
                    Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "Error de conección actualizando gramatica");
                    return;
                }
            } catch (MalformedURLException ex1) {
                Logger.getLogger(ZPublisher.class.getName()).log(Level.SEVERE, null, ex1);
            } catch (IOException ex1) {
                Logger.getLogger(ZPublisher.class.getName()).log(Level.SEVERE, null, ex1);
            }
            
        }
    }

}
