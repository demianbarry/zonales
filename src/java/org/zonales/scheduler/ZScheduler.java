/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.scheduler;

import javax.naming.NamingException;
import javax.servlet.ServletContext;
import org.quartz.Scheduler;
import org.quartz.SchedulerException;
import org.quartz.ee.servlet.QuartzInitializerListener;
import org.quartz.impl.StdSchedulerFactory;

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


}
