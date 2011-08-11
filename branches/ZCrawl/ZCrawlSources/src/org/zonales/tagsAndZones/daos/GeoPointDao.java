/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.tagsAndZones.daos;

import com.mongodb.BasicDBObject;
import com.mongodb.DBCollection;
import com.mongodb.DBCursor;
import com.mongodb.DBObject;
import com.mongodb.MongoException;
import org.zonales.crawlConfig.daos.BaseDao;
import org.zonales.tagsAndZones.objects.GeoPoint;

/**
 *
 * @author rodrigo
 */
public class GeoPointDao extends BaseDao {

    private DBCollection geopoint;

    public GeoPointDao(String db_host, Integer db_port, String db_name) {
        super(db_host, db_port, db_name);
        this.geopoint = this.db.getCollection("geopoints");
        //  this.geopoint.ensureIndex(new BasicDBObject("street_name", 1), "uniqueName", true);
        //  this.geopoint.ensureIndex(new BasicDBObject("street_number", 1), "uniqueName", true);
        this.geopoint.ensureIndex(new BasicDBObject("street_name", 1), new BasicDBObject("street_number", 1));
    }

    public void save(GeoPoint geopoint) throws MongoException {

        BasicDBObject geopointDoc = new BasicDBObject();

        geopointDoc.put("street_name", geopoint.getStreet_name());

        geopointDoc.put("street_number", geopoint.getStreet_number());

        geopointDoc.put("longitude", geopoint.getLongitude());

        geopointDoc.put("latitude", geopoint.getLatitude());

        geopointDoc.put("ext_zip_code", geopoint.getExt_zip_code());

        System.out.println(geopointDoc.toString());
        this.geopoint.insert(geopointDoc);
    }

    public void update(String street_name, int street_number, GeoPoint newGeoPoint) throws MongoException {

        BasicDBObject query = new BasicDBObject();

        query.put("street_name", street_name);
        query.put("street_number", street_number);

        DBObject resp;

        DBCursor cur;

        cur = this.geopoint.find(query);

        resp = cur.next();

        if (resp != null) {
            BasicDBObject geoPointDoc = new BasicDBObject();

            if (newGeoPoint.getStreet_name() != null) {
                geoPointDoc.put("street_name", newGeoPoint.getStreet_name());
            } else {
                geoPointDoc.put("street_name", (String) resp.get("street_name"));
            }

            if (newGeoPoint.getStreet_number() < 0) {
                geoPointDoc.put("street_number", newGeoPoint.getStreet_number());
            } else {
                geoPointDoc.put("street_number", (String) resp.get("street_number"));
            }

            if (newGeoPoint.getLatitude() < 0) {
                geoPointDoc.put("latitude", newGeoPoint.getLatitude());
            } else {
                geoPointDoc.put("latitude", (String) resp.get("latitude"));
            }

            if (newGeoPoint.getLongitude() < 0) {
                geoPointDoc.put("longitude", newGeoPoint.getLongitude());
            } else {
                geoPointDoc.put("longitude", (String) resp.get("longitude"));
            }

            if (newGeoPoint.getExt_zip_code() != null) {
                geoPointDoc.put("ext_zip_code", newGeoPoint.getExt_zip_code());
            } else {
                geoPointDoc.put("longitude", (String) resp.get("longitude"));
            }


            this.geopoint.update(new BasicDBObject().append("name", street_name).append("street_number", street_number), geoPointDoc);

        }
    }

    public String retrieveJson(String street_name, int street_number) {

        BasicDBObject query = new BasicDBObject();

        query.put("street_name", street_name);
        query.put("street_number", street_number);

        DBObject resp;
        DBCursor cur;

        cur = this.geopoint.find(query);

        resp = cur.next();
        resp.removeField("_id");
        //System.out.println(resp);

        return resp.toString();
    }

    public GeoPoint retrieve(String street_name, int street_number) {

        BasicDBObject query = new BasicDBObject();

        query.put("street_name", street_name);
        query.put("street_number", street_number);

        DBObject resp;
        DBCursor cur;
        GeoPoint geopoint = new GeoPoint();

        cur = this.geopoint.find(query);

        resp = cur.next();
        resp.removeField("_id");

        geopoint.setStreet_name((String) resp.get("street_name"));
        geopoint.setStreet_number(Integer.parseInt((String) resp.get("street_number")));
        geopoint.setLongitude(Float.parseFloat((String) resp.get("longitude")));
        geopoint.setLatitude(Float.parseFloat((String) resp.get("latitude")));
        geopoint.setExt_zip_code((String) resp.get("ext_zip_code"));


        return geopoint;
    }
}
