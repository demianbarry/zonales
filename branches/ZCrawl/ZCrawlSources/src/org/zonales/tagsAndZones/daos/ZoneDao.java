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
import java.util.ArrayList;
import org.zonales.tagsAndZones.objects.Tag;
import org.zonales.tagsAndZones.objects.Type;
import org.zonales.tagsAndZones.objects.Zone;

/**
 *
 * @author rodrigo
 */
public class ZoneDao extends BaseDao {

    private DBCollection zones;

    public ZoneDao(String db_host, Integer db_port, String db_name) {
        super(db_host, db_port, db_name);
        this.zones = this.db.getCollection("zones");
        this.zones.ensureIndex(new BasicDBObject("name", 1), "uniqueName", true);
    }

    public void save(Zone zone) throws MongoException {

        BasicDBObject ZoneDoc = new BasicDBObject();

        ZoneDoc.put("id", zone.getId());
        ZoneDoc.put("name", zone.getName());
        ZoneDoc.put("parent", zone.getParent());
        ZoneDoc.put("type", zone.getType());
        ZoneDoc.put("state", zone.getState());
        ZoneDoc.put("centerLat", zone.getCenterLat());
        ZoneDoc.put("centerLon", zone.getCenterLon());
        ZoneDoc.put("zoomLevel", zone.getZoomLevel());

        /*        if(zone.getParent() != null){

        ZoneDoc.put("parents", zone.getParent());
        }*/

        System.out.println(ZoneDoc.toString());
        this.zones.insert(ZoneDoc);
    }

    public void update(String name, Zone newZone) throws MongoException {

        BasicDBObject query = new BasicDBObject("name", name);
        DBObject resp;
        DBCursor cur;

        cur = this.zones.find(query);

        resp = cur.next();

        if (resp != null) {
            BasicDBObject typeDoc = new BasicDBObject();

            if (newZone.getId() < 0) {
                typeDoc.put("id", newZone.getId());
            } else {
                typeDoc.put("id", (String) resp.get("id"));
            }

            if (newZone.getName() != null) {
                typeDoc.put("name", newZone.getName());
            } else {
                typeDoc.put("name", (String) resp.get("name"));
            }

            if (newZone.getParent() != null) {
                typeDoc.put("parents", newZone.getState());
            } else {
                typeDoc.put("parents", (String) resp.get("parents"));
            }

            if (newZone.getType() != null) {
                typeDoc.put("type", newZone.getType());
            } else {
                typeDoc.put("type", (String[]) resp.get("type"));
            }

            if (newZone.getState() != null) {
                typeDoc.put("state", newZone.getState());
            } else {
                typeDoc.put("state", (String) resp.get("state"));
            }

            if (newZone.getCenterLat() < 0) {
                typeDoc.put("centerLat", newZone.getCenterLat());
            } else {
                typeDoc.put("centerLat", (String) resp.get("centerLat"));
            }

            if (newZone.getCenterLon() < 0) {
                typeDoc.put("centerLon", newZone.getCenterLon());
            } else {
                typeDoc.put("centerLon", (String) resp.get("centerLon"));
            }

            if (newZone.getZoomLevel() < 0) {
                typeDoc.put("zoomLevel", newZone.getCenterLon());
            } else {
                typeDoc.put("zoomLevel", (String) resp.get("zoomLevel"));
            }

            this.zones.update(new BasicDBObject().append("id", name), typeDoc);
        }
    }

    public String retrieveJson(String name) {
        BasicDBObject query = new BasicDBObject("name", name);
        DBObject resp;
        DBCursor cur;

        cur = this.zones.find(query);

        resp = cur.next();
        resp.removeField("_id");
        //System.out.println(resp);

        return resp.toString();
    }

    public Zone retrieve(String name) {

        BasicDBObject query = new BasicDBObject("name", name);
        DBObject resp;
        DBCursor cur;
        Zone zone = new Zone();

        cur = this.zones.find(query);

        resp = cur.next();
        resp.removeField("_id");

        zone.setId(Integer.parseInt((String) resp.get("id")));

        zone.setName((String) resp.get("name"));

        zone.setState((String) resp.get("state"));

        zone.setParent((Tag) resp.get("tag"));

        zone.setCenterLat(Float.parseFloat((String) resp.get("centerLat")));

        zone.setCenterLon(Float.parseFloat((String) resp.get("centerLon")));

        zone.setZoomLevel(Integer.parseInt((String) resp.get("zoomLevel")));

        return zone;
    }
}
