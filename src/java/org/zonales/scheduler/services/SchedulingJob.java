/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.scheduler.services;

import com.google.gson.Gson;
import static org.quartz.SimpleScheduleBuilder.simpleSchedule;
import static org.quartz.TriggerBuilder.newTrigger;
import static org.quartz.JobBuilder.newJob;

import java.io.IOException;
import java.io.PrintWriter;
import java.net.HttpURLConnection;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.naming.NamingException;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.quartz.JobDetail;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;
import org.quartz.Trigger;
import org.zonales.BaseService;
import org.zonales.ZGram.ZGram;
import org.zonales.crawlConfig.objets.State;
import org.zonales.errors.ZMessages;
import org.zonales.scheduler.ZPublisher;
import org.zonales.helpers.ConnHelper;
import org.zonales.metadata.ZCrawling;
import org.zonales.scheduler.ZScheduler;

/**
 *
 * @author nacho
 */
public class SchedulingJob extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("text/javascript");
        PrintWriter out = response.getWriter();
        try {
            String zGramId = request.getParameter("id");

            //Recupero la gramática a partir del id
            HttpURLConnection connection = ConnHelper.getURLConnection(props.getProperty("ZCrawlSourcesURL") + "getZGram?id=" + zGramId, Integer.valueOf(props.getProperty("timeout")));
            String zGramJson;
            Gson zGramGson = new Gson();
            ZGram zgram = new ZGram();

            int code = connection.getResponseCode();
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Recuperación gramática - Código de respuesta: {0}", code);
            if (code == 200) {
                zGramJson = ConnHelper.getStringFromInpurStream(connection.getInputStream());
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Recuperación gramática - Respuesta: {0}", zGramJson);
                zgram = zGramGson.fromJson(zGramJson, ZGram.class);
                connection.disconnect();
            } else {
                out.print(ZMessages.ZSOURCES_CONN_ERROR);
                Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "Error de conección recuperando gramatica");
                return;
            }

            //Hago un mínimo control, porque si no está compilada la gramática no puedo extraer, por ende tampo puedo crear el Job
            if (zgram.getEstado().equals(State.COMPILED)) {
                ZCrawling zcrawling = zgram;
                Gson metadataGson = new Gson();
                //Obtengo la URL para realizar la extracción
                String metadata = metadataGson.toJson(zcrawling, ZCrawling.class);

                Scheduler sched = ZScheduler.getScheduler(this.getServletContext());

                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Creando Job para ID: {0}", zGramId);

                // define the job and tie it to our ZPublisher class
                JobDetail job = newJob(ZPublisher.class)
                    .withIdentity(zGramId, props.getProperty("schedulerJobsGroup"))
                    .usingJobData("zGramId", zGramId)
                    .usingJobData("metadata", metadata)
                    .usingJobData("ZCrawlSourcesURL", props.getProperty("ZCrawlSourcesURL"))
                    .usingJobData("timeout", Integer.valueOf(props.getProperty("timeout")))
                    .usingJobData("solrURL", props.getProperty("solr_url"))
                    .build();


                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Job creado para ID: {0}", zGramId);

                Trigger trigger = newTrigger()
                    .withIdentity(zGramId, props.getProperty("schedulerJobsGroup"))
                    .startNow()
                    .withSchedule(simpleSchedule()
                        .withIntervalInMinutes(zgram.getPeriodicidad()) //zgram.getPeriodicidad() ** Acá se debe recupearar la periodicidad desde la ZGram **
                        .repeatForever())
                    .build();

                sched.scheduleJob(job, trigger);
                out.print(ZMessages.SUCCESS);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Success: Schedule successfully created");
            }
        } catch (SchedulerException e) {
            out.print(ZMessages.ZSCHEDULER_SCHEDULER_ERROR);
            Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "Error: {0} Ex: {1}",  new Object[]{ZMessages.ZSCHEDULER_SCHEDULER_ERROR, e.getMessage()});
        } catch (NamingException e) {
            out.print(ZMessages.ZSCHEDULER_NAMING_ERROR);
            Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "Error: {0} Ex: {1}",  new Object[]{ZMessages.ZSCHEDULER_NAMING_ERROR, e.getMessage()});
        } catch (Exception e) {
            out.print(ZMessages.ZSCHEDULER_UNKNOW_ERROR);
            Logger.getLogger(this.getClass().getName()).log(Level.SEVERE, "Error: {0} Ex: {1}",  new Object[]{ZMessages.ZSCHEDULER_UNKNOW_ERROR, e.getMessage()});
        }
    }

}
