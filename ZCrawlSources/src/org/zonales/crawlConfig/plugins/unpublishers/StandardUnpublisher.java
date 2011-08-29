/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.crawlConfig.plugins.unpublishers;

import com.mongodb.MongoException;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.zonales.ZGram.ZGram;
import org.zonales.ZGram.daos.ZGramDao;
import org.zonales.crawlConfig.objets.State;
import org.zonales.errors.ZMessages;

/**
 *
 * @author juanma
 */
public class StandardUnpublisher implements Unpublisher {

    @Override
    public String unpublish(ZGram zgram, String id, Properties props) {
        try {
        zgram.setEstado(State.UNPUBLISHED);
        ZGramDao zGramDao = new ZGramDao(props.getProperty("db_host"), Integer.valueOf(props.getProperty("db_port")), props.getProperty("db_name"));
        zGramDao.update(id, zgram);
        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Extracción actualizada {0}", new Object[]{zgram});
        return ZMessages.SUCCESS.toString();            
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
