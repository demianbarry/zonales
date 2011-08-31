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
import org.zonales.BaseDao;
import org.zonales.crawlConfig.objets.State;
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
        this.zones.ensureIndex(new BasicDBObject("id", 1), "uniqueName", true);
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

    public Boolean exists(String name) {
        BasicDBObject query = new BasicDBObject("name", name);
        return this.zones.find(query).count() > 0 ? Boolean.TRUE : Boolean.FALSE;
    }

    public String retrieveJson(String name) {
        BasicDBObject query = new BasicDBObject("name", name);
        DBObject resp = this.zones.findOne(query);
        if (resp == null) {
            return null;
        }
        
        resp.removeField("_id");
        //System.out.println(resp);

        return resp.toString();
    }

      public String retrieveAll() {
        return retrieveAll(false);
    }

    public String retrieveAll(Boolean onlyNames) {
        String ret = "[";
        DBObject resp;
        DBCursor cur = this.zones.find();

        while (cur.hasNext()) {
            resp = cur.next();
            resp.removeField("_id");
            //System.out.println(resp);
            if (resp.get("state") == null || !((String)resp.get("state")).equals(State.VOID)) {
                if (onlyNames) {
                    ret += resp.get("name") + ",";
                } else {
                    ret += resp + ",";
                }
            } else {
                return null;
            }
        }

        ret = ret.substring(0, ret.length() - 1);
        ret += "]";

        return ret;
    }

    public Zone retrieve(Integer id) {
        BasicDBObject query = new BasicDBObject("id", id);
        return getZone(query);
    }

    public Zone retrieve(String name) {
        BasicDBObject query = new BasicDBObject("name", name);
        return getZone(query);
    }

    private Zone getZone(BasicDBObject query) {
        DBObject resp;

        resp = this.zones.findOne(query);
        if (resp == null) {
            return null;
        }
        resp.removeField("_id");
        Zone zone = new Zone();

        zone.setId(Integer.parseInt((String) resp.get("id")));

        zone.setName((String) resp.get("name"));

        zone.setState((String) resp.get("state"));

        if (resp.get("type") != null) {
            DBObject obj = this.db.getCollection("tagTypes").findOne(new BasicDBObject("name", (String) resp.get("type")));
            Type type = new Type((String) obj.get("name"), (ArrayList<String>) obj.get("parents"), (String) obj.get("state"));
            zone.setType(type);
        }

        if (resp.get("parent") != null) {
            zone.setParent(retrieve(Integer.valueOf(String.valueOf(resp.get("parent")))));
        }

        if (resp.get("centerLat") != null) {
            zone.setCenterLat(Float.parseFloat((String) resp.get("centerLat")));
        }

        if (resp.get("centerLon") != null) {
            zone.setCenterLon(Float.parseFloat((String) resp.get("centerLon")));
        }

        if (resp.get("zoomLevel") != null) {
            zone.setZoomLevel(Integer.parseInt((String) resp.get("zoomLevel")));
        }

        return zone;
    }
}
