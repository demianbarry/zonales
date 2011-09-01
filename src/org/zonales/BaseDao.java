/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales;

import com.mongodb.DB;
import com.mongodb.Mongo;
import java.io.IOException;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author nacho
 */
public class BaseDao {

    protected static Mongo conn = null;
    protected DB db = null;   

    public BaseDao(String db_host, Integer db_port, String db_name) {
        try {
            if (conn == null) {
                conn = new Mongo(db_host, db_port);
            }
            db = conn.getDB(db_name);
        } catch (IOException ex) {
            Logger.getLogger(BaseDao.class.getName()).log(Level.SEVERE, "Base de datos no disponible {0}", new Object[]{ex.getMessage()});
        }
    }
}
