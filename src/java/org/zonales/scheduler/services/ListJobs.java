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
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();

        try {
            Scheduler sched = ZScheduler.getScheduler(this.getServletContext());

            for (JobExecutionContext jobExc : sched.getCurrentlyExecutingJobs()) {
                out.print("JobExc Id: " + jobExc.getJobDetail().getKey() + "<br>");
            }

            // enumerate each job group
            for(String group: sched.getJobGroupNames()) {
                // enumerate each job in group
                for(JobKey jobKey : sched.getJobKeys(groupEquals(group))) {
                    out.print("Found job identified by: " + jobKey + "<br>");
                }
            }

            out.print(ZMessages.SUCCESS);
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
