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
import org.quartz.SchedulerException;
import org.zonales.entities.Post;
import org.zonales.entities.Posts;
import org.zonales.errors.ZMessage;
import org.zonales.errors.ZMessages;
import org.zonales.helpers.ConnHelper;
import org.zonales.metadata.ZCrawling;
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
        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "SCHEDULER: Ejecutando Job: {0} - Class: {1}", new Object[]{jec.getJobDetail().getKey(), jec.getJobDetail().getJobClass()});
        try {
            JobDetail jobDetail = jec.getJobDetail();
            zGramId = jobDetail.getJobDataMap().getString("zGramId");
            String solrUrl = jobDetail.getJobDataMap().getString("solrURL");
            ZSolrServer server = new ZSolrServer(solrUrl);
            String metadata = jobDetail.getJobDataMap().getString("metadata");
            zCrawlSourcesURL = jobDetail.getJobDataMap().getString("ZCrawlSourcesURL");
            timeout = jobDetail.getJobDataMap().getInt("timeout");
            ZExtractor extractor = new ZExtractor();
            Posts posts = extractor.extract(zGramId, metadata, zCrawlSourcesURL, timeout);

            Long ultimoHitDeExtraccion = 0L;
            // Si la fuente es Twitter, el since_id es el mayor id de tweet recuperado
            if("Twitter".equalsIgnoreCase(((ZCrawling)(new Gson()).fromJson(metadata, ZCrawling.class)).getFuente())) {
                for(Post post : posts.getPost()){
                    ultimoHitDeExtraccion = Integer.valueOf(post.getId()) > ultimoHitDeExtraccion ? Integer.valueOf(post.getId()) : ultimoHitDeExtraccion;
                }
            } else {
                ultimoHitDeExtraccion = new Date().getTime();   
            }
             
            String parameters = "id=" + zGramId + "&ultimoHitDeExtraccion=" + ultimoHitDeExtraccion + "&newcod=" + ZMessages.SUCCESS.getCod() + "&newmsg=" + ZMessages.SUCCESS.getMsg();

            if (!posts.getPost().isEmpty()) {
                Long ultimaExtraccionConDatos = new Date().getTime();
                parameters += "&ultimaExtraccionConDatos=" + ultimaExtraccionConDatos;
            }

            String url = zCrawlSourcesURL + "updateZGram?" + parameters;
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Actualizacion ZGram URL: {0}", url);

            HttpURLConnection connection = ConnHelper.getURLConnection(url, timeout);
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

            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Posts recuperados: {0}", posts.getPost().size());

            server.indexPosts(posts);
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Posts indexados");

        } catch (SolrServerException ex) {
            //Error en solr, el JOB no pasa a Stand-by
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Error indexando posts: {0}", ex);
        } catch (MalformedURLException ex) {
            //Error en la URL de solr, el JOB no pasa a Stand-by
            Logger.getLogger(ZPublisher.class.getName()).log(Level.SEVERE, "Error en JOB Malformen URL: ", ex);
        } catch (IOException ex) {
            //Error de URLs, el JOB no pasa a Stand-by
            Logger.getLogger(ZPublisher.class.getName()).log(Level.SEVERE, "Error en JOB IO: ", ex);
        } catch (ExtractException ex) {

            try {
                //Analizo los códigos de mensaje obtenidos del extractor en casos de error, trato los que generan stand-by
                if (ex.getZmessage().getCod() == ZMessages.ZSOURCES_GETURL_ERROR.getCod() ||   //Si la URL de extracción es incorercta
                        ex.getZmessage().getCod() == ZMessages.GSON_CONVERTION_ERROR.getCod() ||  //Si el Json de los post es incorrecto o malformado
                        ex.getZmessage().getCod() >= 400 || ex.getZmessage().getCod() < 500  //Si el extractor dio error TODO: falta contemplar el caso de error de conexión
                        ) {
                    ZScheduler.pauseJob(jec.getJobDetail().getKey());
                }
            } catch (SchedulerException ex1) {
                Logger.getLogger(ZPublisher.class.getName()).log(Level.SEVERE, null, ex1);
            }

            //Actualizado la gramática con el intento de extracción
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Extract Exeption: {0}", ex.getZmessage());
            Long ultimoHitDeExtraccion = new Date().getTime();
            Integer ultimoCodigoDeExtraccion = ex.getZmessage().getCod();
            String parameters = "id=" + zGramId + "&ultimoHitDeExtraccion=" + ultimoHitDeExtraccion + "&newcod=" + ex.getZmessage().getCod() + "&newmsg=" + ex.getZmessage().getMsg();
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
