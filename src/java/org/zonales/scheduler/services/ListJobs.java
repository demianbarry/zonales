/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.scheduler.services;
import static org.quartz.impl.matchers.GroupMatcher.groupEquals;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.naming.NamingException;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.quartz.JobExecutionContext;
import org.quartz.JobKey;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;
import org.zonales.BaseService;
import org.zonales.errors.ZMessages;
import org.zonales.scheduler.ZScheduler;

/**
 *
 * @author nacho
 */
public class ListJobs extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setCharacterEncoding("UTF-8");
        response.setContentType("text/html");
        response.setHeader("link", "type=\"text/css\" rel=\"stylesheet\" href=\"/css/content.css\">");

        PrintWriter out = response.getWriter();

        String pathVar = "css/content.css";

        out.println("<html>");
        out.println("<head>");
        out.println("<title>SimpleServlet</title>");
        out.println("<link rel='stylesheet' type='text/css' href= '" + pathVar + "' />");
        out.println("</head>");
        out.println("<body>");

        //String result = "<link type=\"text/css\" rel=\"stylesheet\" href=\"/css/content.css\">";

        String result = "<table id=\"jobTable\" class=\"resultTable\">"
                + "<tr class\"tableRowHeader\">"
                + "<td>Grupo</td>"
                + "<td>Job Id</td>"
                + "<td>Estado</td>"
                + "</tr>";

        try {
            Scheduler sched = ZScheduler.getScheduler(this.getServletContext());

            for (JobExecutionContext jobExc : sched.getCurrentlyExecutingJobs()) {
                result += "<tr id=\"" + jobExc.getJobDetail().getKey().getName() + "\" class=\"tableRow\">"
                        + "<td>" + jobExc.getJobDetail().getKey().getGroup().substring(0, jobExc.getJobDetail().getKey().getGroup().length() - 1) +"</td>"
                        + "<td><a href=\"" + props.getProperty("extractUtilURL") + "?id=" + jobExc.getJobDetail().getKey().getName() + "\">" + jobExc.getJobDetail().getKey().getName() + "</a></td>"
                        + "<td>En Ejecución</td>"
                        + "</tr>";
                //out.print("JobExc Id: " + jobExc.getJobDetail().getKey() + "<br>");
            }

            // enumerate each job group
            for(String group: sched.getJobGroupNames()) {
                // enumerate each job in group
                for(JobKey jobKey : sched.getJobKeys(groupEquals(group))) {
                    result += "<tr id=\"" + jobKey.getName() + "\" class=\"tableRow\">"
                        + "<td>" + jobKey.getGroup().substring(0, jobKey.getGroup().length() - 1) +"</td>"
                        + "<td><a href=\"" + props.getProperty("extractUtilURL") + "?id=" + jobKey.getName() + "\">" + jobKey.getName() + "</a></td>"
                        + "<td>Programado</td>"
                        + "</tr>";
                    //out.print("Found job identified by: " + jobKey + "<br>");
                }
            }

            result += "</table>";

            out.println(result);

            out.println("</body>");
            out.println("</html>");

            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Success: Schedules successfully listed");

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
