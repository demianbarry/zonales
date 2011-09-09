/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.scheduler;

import static org.quartz.TriggerBuilder.newTrigger;
import static org.quartz.JobBuilder.newJob;
import static org.quartz.SimpleScheduleBuilder.simpleSchedule;

import com.google.gson.Gson;
import java.io.IOException;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.naming.NamingException;
import javax.servlet.ServletContext;
import org.quartz.JobDetail;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;
import org.quartz.Trigger;
import org.quartz.ee.servlet.QuartzInitializerListener;
import org.quartz.impl.StdSchedulerFactory;
import org.zonales.ZGram.ZGram;
import org.zonales.crawlConfig.objets.State;
import org.zonales.errors.ZMessages;
import org.zonales.helpers.ConnHelper;
import org.zonales.metadata.ZCrawling;

/**
 *
 * @author nacho
 */
public class ZScheduler {

    private static Scheduler zScheduler;

    public static Scheduler getScheduler(ServletContext context) throws SchedulerException, NamingException {
        if (zScheduler == null) {
            createScheduler(context);
        }
        startScheduler(context);
        return zScheduler;
    }
    
    public static void createScheduler(ServletContext context) throws SchedulerException, NamingException {
        StdSchedulerFactory sf = (StdSchedulerFactory) context.getAttribute(QuartzInitializerListener.QUARTZ_FACTORY_KEY);
        zScheduler = sf.getScheduler();
    }

    public static void startScheduler(ServletContext context) throws SchedulerException, NamingException {
        if (zScheduler == null) {
            createScheduler(context);
        }
        if (!zScheduler.isStarted())
            zScheduler.start();
    }

    public static void stopScheduler() throws SchedulerException {
        if (zScheduler != null && !zScheduler.isShutdown()) {
            zScheduler.shutdown(true);
        }
    }

    public static String scheduleJob(String zGramId, Properties props, ServletContext contex) throws MalformedURLException, IOException, SchedulerException, NamingException {
        String resp = "";

        if (zScheduler == null) {
            createScheduler(contex);
        }
        
        //Recupero la gramática a partir del id
        HttpURLConnection connection = ConnHelper.getURLConnection(props.getProperty("ZCrawlSourcesURL") + "getZGram?id=" + zGramId, Integer.valueOf(props.getProperty("timeout")));
        String zGramJson;
        Gson zGramGson = new Gson();
        ZGram zgram = new ZGram();

        int code = connection.getResponseCode();
        Logger.getLogger("ZCheduler").log(Level.INFO, "Recuperación gramática - Código de respuesta: {0}", code);
        if (code == 200) {
            zGramJson = ConnHelper.getStringFromInpurStream(connection.getInputStream());
            Logger.getLogger("ZCheduler").log(Level.INFO, "Recuperación gramática - Respuesta: {0}", zGramJson);
            zgram = zGramGson.fromJson(zGramJson, ZGram.class);
            connection.disconnect();
        } else {
            Logger.getLogger("ZCheduler").log(Level.WARNING, "Error de conección recuperando gramatica");
            return ZMessages.ZSOURCES_CONN_ERROR.toString();
        }

        //Hago un mínimo control, porque si no está compilada la gramática no puedo extraer, por ende tampo puedo crear el Job
        if (zgram.getEstado().equals(State.COMPILED) || zgram.getEstado().equals(State.PUBLISHED)) {
            ZCrawling zcrawling = zgram;
            Gson metadataGson = new Gson();
            //Obtengo la URL para realizar la extracción
            String metadata = metadataGson.toJson(zcrawling, ZCrawling.class);

            Logger.getLogger("ZCheduler").log(Level.INFO, "Creando Job: metadata: {0}", metadata);

            //Scheduler sched = ZScheduler.getScheduler(this.getServletContext());

            Logger.getLogger("ZCheduler").log(Level.INFO, "Creando Job para ID: {0}", zGramId);

            // define the job and tie it to our ZPublisher class
            JobDetail job = newJob(ZPublisher.class)
                .withIdentity(zGramId, props.getProperty("schedulerJobsGroup"))
                .usingJobData("zGramId", zGramId)
                .usingJobData("metadata", metadata)
                .usingJobData("ZCrawlSourcesURL", props.getProperty("ZCrawlSourcesURL"))
                .usingJobData("timeout", Integer.valueOf(props.getProperty("timeout")))
                .usingJobData("solrURL", props.getProperty("solr_url"))
                .build();


            Logger.getLogger("ZCheduler").log(Level.INFO, "Job creado para ID: {0}", zGramId);

            Trigger trigger = newTrigger()
                .withIdentity(zGramId, props.getProperty("schedulerJobsGroup"))
                .startNow()
                .withSchedule(simpleSchedule()
                    .withIntervalInMinutes(zgram.getPeriodicidad()) //zgram.getPeriodicidad() ** Acá se debe recupearar la periodicidad desde la ZGram **
                    .repeatForever())
                .build();

            zScheduler.scheduleJob(job, trigger);
            resp = ZMessages.SUCCESS.toString();
        }

        return resp;
    }

}
