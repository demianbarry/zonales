/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.scheduler;

import java.net.HttpURLConnection;
import java.net.URLEncoder;

import java.util.logging.Level;
import java.util.logging.Logger;
import javax.naming.NamingException;
import javax.servlet.ServletContext;
import org.quartz.Job;
import org.quartz.JobDetail;
import org.quartz.JobExecutionContext;
import org.quartz.JobExecutionException;
import org.quartz.JobKey;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;
import org.quartz.Trigger;
import org.quartz.impl.matchers.GroupMatcher;
import org.zonales.helpers.ConnHelper;

/**
 *
 * @author nacho
 */
public class MailSender implements Job {

    @Override
    public void execute(JobExecutionContext jec) throws JobExecutionException {

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "SCHEDULER: Ejecutando Job: {0} - Class: {1}", new Object[]{jec.getJobDetail().getKey(), jec.getJobDetail().getJobClass()});
        try {
            JobDetail jobDetail = jec.getJobDetail();
            //String[] to = jobDetail.getJobDataMap().getString("mail.to").split(",");
            String to = jec.getJobDetail().getJobDataMap().getString("mail.to");
            String subject = "Zonales Scheduler - Informe diario de Extracciones";
            ServletContext contex = (ServletContext) jec.getJobDetail().getJobDataMap().get("contex");
            String sendMailURL = jec.getJobDetail().getJobDataMap().getString("sendMailURL");
            String extractUtilURL = jec.getJobDetail().getJobDataMap().getString("extractUtilURL");
            Integer timeout = jec.getJobDetail().getJobDataMap().getInt("timeout");
            Scheduler scheduler = ZScheduler.getScheduler(contex);
            String mailContent = "";

            mailContent += "<h3>Zonales Scheduler - Informe diario de Extracciones</h3>"
                    + "Las siguientes extracciones se encuentran pausadas: <br><br>";

            mailContent += "<table id=\"jobTable\" style=\"border: 1px solid #CCCCCC;border-spacing: 0;border-collapse: separate;width: 1000px;"
                    + "td {border: 1px solid;padding: 5px;text-align: center;}\">"
                    + "<tr style=\"background-color: #58ACFA;\">"
                    + "<td>Grupo</td>"
                    + "<td>Job Id</td>"
                    + "<td>Descripción</td>"
                    + "<td>Localidad</td>"
                    + "<td>Fuente</td>"
                    + "<td>Tags</td>"
                    + "<td>Estado</td>"
                    + "</tr>";

            // enumerate each job group
            for (String group : scheduler.getJobGroupNames()) {
                // enumerate each job in group
                for (JobKey jobKey : scheduler.getJobKeys(GroupMatcher.groupEquals(group))) {
                    for (Trigger trigger : scheduler.getTriggersOfJob(jobKey)) {
                        if (scheduler.getTriggerState(trigger.getKey()) == Trigger.TriggerState.PAUSED) {
                            mailContent += "<tr id=\"" + jobKey.getName() + "\" style=\"background-color: #E0E0F8;font-size: small;\">"
                                    + "<td>" + jobKey.getGroup() + "</td>"
                                    + "<td><a href=\"" + extractUtilURL + "?id=" + jobKey.getName() + "\">" + jobKey.getName() + "</a></td>"
                                    + "<td>" + scheduler.getJobDetail(jobKey).getJobDataMap().getString("zGramDescription") + "</td>"
                                    + "<td>" + scheduler.getJobDetail(jobKey).getJobDataMap().getString("zGramLocalidad") + "</td>"
                                    + "<td>" + scheduler.getJobDetail(jobKey).getJobDataMap().getString("zGramFuente") + "</td>"
                                    + "<td>" + scheduler.getJobDetail(jobKey).getJobDataMap().getString("zGramTags") + "</td>"
                                    + "<td>" + scheduler.getTriggerState(scheduler.getTriggersOfJob(jobKey).get(0).getKey()) + "</td>"
                                    + "</tr>";
                            /*mailContent += "- " + jobKey.getName() + "<br>";
                            mailContent += "- " + scheduler.getJobDetail(jobKey).getJobDataMap().getString("zGramDescription") + "<br>";
                            mailContent += "- " + scheduler.getJobDetail(jobKey).getJobDataMap().getString("metadata") + "<br>";*/
                        }
                    }
                }
            }

            mailContent += "</table><br><br><br>Mail enviado automáticamente por el planificador de Zonales<br><br>"
                    + "Si rebibe por error este mail, comuniquese con el administrador de su zona<br>";

            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "MAIL: Antes de enviar mail");

            HttpURLConnection connection;

            connection = ConnHelper.getURLConnection(sendMailURL + "sendMail?to=" + to + "&subject=" + subject + "&message=" + URLEncoder.encode(mailContent, "UTF-8"), timeout);
            int code = connection.getResponseCode();

            if (code == 200) {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "MAIL: Mail enviado");
            } else {
                Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "MAIL: Mail no enviado");
            }

        } catch (SchedulerException ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.SEVERE, "MAIL: SchedulerException: ", ex);
        } catch (NamingException ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.SEVERE, "MAIL: NamingException: ", ex);
        } catch (Exception ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.SEVERE, "MAIL: Exception: ", ex);
        }
    }
}
