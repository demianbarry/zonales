/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.crawlConfig.plugins.publishers;

import com.google.gson.Gson;
import com.mongodb.MongoException;
import java.net.HttpURLConnection;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.zonales.ZGram.ZGram;
import org.zonales.ZGram.daos.ZGramDao;
import org.zonales.crawlConfig.objets.State;
import org.zonales.errors.ZMessage;
import org.zonales.errors.ZMessages;
import org.zonales.helpers.ConnHelper;

/**
 *
 * @author juanma
 */
public class StandardPublisher implements Publisher {

    @Override
    public String publish(ZGram zgram, String id, Properties props) {
        try {

            //Pongo en el Scheduler la extracción publicada
            HttpURLConnection connection = ConnHelper.getURLConnection(props.getProperty("ZCrawlSchedulerURL") + "schedulingJob?id=" + zgram.getId().get$oid(), Integer.valueOf(props.getProperty("timeout")));
            String zMessageJson;
            Gson zMessageGson = new Gson();
            ZMessage zmessage = new ZMessage();

            int code = connection.getResponseCode();
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Scheduling Job - Código de respuesta: {0}", code);
            if (code == 200) {
                zMessageJson = ConnHelper.getStringFromInpurStream(connection.getInputStream());
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Scheduling Job - Respuesta: {0}", zMessageJson);
                zmessage = zMessageGson.fromJson(zMessageJson, ZMessage.class);
                connection.disconnect();
            } else {
                Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "Error creando Job en el Scheduler");
                return ZMessages.ZSCHEDULER_CONN_ERROR.toString();
            }

            if (zmessage.getCod() == 100) {
                zgram.setEstado(State.PUBLISHED);
                ZGramDao zGramDao = new ZGramDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
                zGramDao.update(id, zgram);
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Extracción actualizada {0}", new Object[]{zgram});
                return ZMessages.SUCCESS.toString();
            } else {
                Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Error en Scheduler");
                return zmessage.toString();
            }
        } catch (MongoException ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Error actualizando extracción {0}: {1}", new Object[]{zgram, ex.getMessage()});
            return ZMessages.MONGODB_ERROR.toString();
        } catch (Exception ex) {
            StringBuilder stacktrace = new StringBuilder();
            for (StackTraceElement line : ex.getStackTrace()) {
                stacktrace.append(line.toString());
                stacktrace.append("\n");
            }
            Logger.getLogger(this.getClass().getName()).log(Level.SEVERE,
                    "EXCEPCION: {0}\nTRACE: {1}", new Object[]{ex, stacktrace.toString()});

            return ZMessages.UNKNOWN_ERROR.toString();
        }
    }
}
