/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.tagsAndZones.daos;

import com.mongodb.BasicDBObject;
import com.mongodb.DBCollection;
import com.mongodb.DBObject;
import com.mongodb.MongoException;
import com.mongodb.WriteResult;
import com.mongodb.util.JSON;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.zonales.BaseDao;

/**
 *
 * @author rodrigo
 */
public class GeoZoneDao extends BaseDao {

    private DBCollection geozones;

    public GeoZoneDao(String db_host, Integer db_port, String db_name) {
        super(db_host, db_port, db_name);
        this.geozones = this.db.getCollection("geozones");
        this.geozones.ensureIndex(new BasicDBObject("ref.zone", 1), "uniqueName", true);
    }

    public void save(String zone) throws MongoException {

        BasicDBObject geozone = (BasicDBObject) JSON.parse(zone);
        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "GEOJSON: {0}", geozone);
        try {
            WriteResult wr = this.geozones.insert(geozone);
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "WRITERESULT: {0}", wr);
        } catch (Exception ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "GEOEX: {0}", ex);
        }
        
    }

    public String retrieveJsonById(String id) {
        BasicDBObject query = new BasicDBObject("id", id);
        DBObject resp = this.geozones.findOne(query);
        if (resp == null) {
            return null;
        }
        return resp.toString();
    }

    public String retrieveJsonByZone(String id) {
        BasicDBObject query = new BasicDBObject("ref.zone", id);
        DBObject resp = this.geozones.findOne(query);
        if (resp == null) {
            return null;
        }
        return resp.toString();
    }
    
}
