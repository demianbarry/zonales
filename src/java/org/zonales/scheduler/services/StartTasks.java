/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.scheduler.services;

import static org.quartz.TriggerBuilder.newTrigger;
import static org.quartz.JobBuilder.newJob;
import static org.quartz.SimpleScheduleBuilder.simpleSchedule;

import java.io.IOException;
import java.io.InputStream;
import java.net.MalformedURLException;
import java.util.List;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.naming.NamingException;
import javax.servlet.ServletContext;
import javax.servlet.http.HttpServlet;
import org.quartz.JobDetail;
import org.quartz.SchedulerException;
import org.quartz.Trigger;
import org.zonales.ZGram.daos.ZGramDao;
import org.zonales.scheduler.MailSender;
import org.zonales.scheduler.ZPublisher;
import org.zonales.scheduler.ZScheduler;

/**
 *
 * @author nacho
 */
public class StartTasks extends HttpServlet {

    InputStream stream;
    static Properties props = null;
    ZGramDao zGramDao;
    List<String> idList;
    ServletContext contex;

    @Override
    public void init() {
        try {
            stream = getServletContext().getResourceAsStream("/WEB-INF/servlet.properties");
            if (props == null) {
                props = new Properties();
                props.load(stream);
            }
            zGramDao = new ZGramDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
            idList = zGramDao.getPublished();
            contex = this.getServletContext();

            new SchedulePublishedCrawls().start();
            new MailSenderLauncher().start();
        } catch (IOException ex) {
            Logger.getLogger(StartTasks.class.getName()).log(Level.SEVERE, null, ex);
        } catch (Exception ex) {
            Logger.getLogger(StartTasks.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    private class SchedulePublishedCrawls extends Thread {

        @Override
        public void run() {
            try {
                for (String id : idList) {
                    String resp = ZScheduler.scheduleJob(id, props, contex);
                    Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Schedule Job, respuesta: {0}", resp);
                    Thread.sleep(60000);
                }
            } catch (MalformedURLException ex) {
                Logger.getLogger(StartTasks.class.getName()).log(Level.SEVERE, null, ex);
            } catch (IOException ex) {
                Logger.getLogger(StartTasks.class.getName()).log(Level.SEVERE, null, ex);
            } catch (SchedulerException ex) {
                Logger.getLogger(StartTasks.class.getName()).log(Level.SEVERE, null, ex);
            } catch (NamingException ex) {
                Logger.getLogger(StartTasks.class.getName()).log(Level.SEVERE, null, ex);
            } catch (InterruptedException ex) {
                Logger.getLogger(StartTasks.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
    }

    private class MailSenderLauncher extends Thread {
         @Override
        public void run() {
            try {
                Logger.getLogger("ZCheduler").log(Level.INFO, "Building Mail Sender Job");

                // define the job and tie it to our ZPublisher class
                JobDetail job = newJob(MailSender.class)
                    .withIdentity("mailSender", props.getProperty("MailSenderGroup"))
                    .usingJobData("mail.to", props.getProperty("mail.to"))
                    .usingJobData("zGramDescription", props.getProperty("MailSenderDescription"))
                    .usingJobData("sendMailURL", props.getProperty("sendMailURL"))
                    .usingJobData("extractUtilURL", props.getProperty("extractUtilURL"))
                    .usingJobData("timeout", props.getProperty("timeout"))
                    .build();

                Logger.getLogger("ZCheduler").log(Level.INFO, "Mail Sender Job builded");

                Trigger trigger = newTrigger()
                    .withIdentity("mailSender", props.getProperty("MailSenderGroup"))
                    .startNow()
                    .withSchedule(simpleSchedule()
                        //.withIntervalInHours(Integer.valueOf(props.getProperty("mailsInterval")))
                        .withIntervalInMinutes(Integer.valueOf(props.getProperty("mailsInterval"))) //Para testing, en minutos
                        .repeatForever())
                    .build();

                ZScheduler.scheduleJob(job, trigger, contex);
            } catch (SchedulerException ex) {
                Logger.getLogger(StartTasks.class.getName()).log(Level.SEVERE, null, ex);
            } catch (NamingException ex) {
                Logger.getLogger(StartTasks.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
    }
}
