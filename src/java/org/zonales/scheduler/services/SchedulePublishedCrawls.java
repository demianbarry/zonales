/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.scheduler.services;

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
import org.quartz.SchedulerException;
import org.zonales.ZGram.daos.ZGramDao;
import org.zonales.scheduler.ZScheduler;

/**
 *
 * @author nacho
 */
public class SchedulePublishedCrawls extends HttpServlet {

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

            new Inner().start();
        } catch (IOException ex) {
            Logger.getLogger(SchedulePublishedCrawls.class.getName()).log(Level.SEVERE, null, ex);
        } catch (Exception ex) {
            Logger.getLogger(SchedulePublishedCrawls.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    private class Inner extends Thread {

        @Override
        public void run() {
            try {
                for (String id : idList) {
                    ZScheduler.scheduleJob(id, props, contex);
                    Thread.sleep(60000);
                }
            } catch (MalformedURLException ex) {
                Logger.getLogger(SchedulePublishedCrawls.class.getName()).log(Level.SEVERE, null, ex);
            } catch (IOException ex) {
                Logger.getLogger(SchedulePublishedCrawls.class.getName()).log(Level.SEVERE, null, ex);
            } catch (SchedulerException ex) {
                Logger.getLogger(SchedulePublishedCrawls.class.getName()).log(Level.SEVERE, null, ex);
            } catch (NamingException ex) {
                Logger.getLogger(SchedulePublishedCrawls.class.getName()).log(Level.SEVERE, null, ex);
            } catch (InterruptedException ex) {
                Logger.getLogger(SchedulePublishedCrawls.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
    }
}
