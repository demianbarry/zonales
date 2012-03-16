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
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import java.util.regex.Pattern;
import org.zonales.BaseDao;
import org.zonales.crawlConfig.objets.State;
import org.zonales.tagsAndZones.objects.Place;
import org.zonales.tagsAndZones.objects.Type;

/**
 *
 * @author rodrigo
 */
public class PlaceDao extends BaseDao {

    private DBCollection places;

    public PlaceDao(String db_host, Integer db_port, String db_name) {
        super(db_host, db_port, db_name);
        this.places = this.db.getCollection("places");
        this.places.ensureIndex(new BasicDBObject("id", 1), "uniqueName", true);
    }

    public void save(Place place) throws MongoException {

        BasicDBObject ZoneDoc = new BasicDBObject();

        ZoneDoc.put("id", place.getId());
        ZoneDoc.put("name", place.getName());
        ZoneDoc.put("parent", place.getParent());
        ZoneDoc.put("type", place.getType());
        ZoneDoc.put("state", place.getState());
        ZoneDoc.put("address", place.getAddress());
        ZoneDoc.put("description", place.getDescription());
        ZoneDoc.put("extendedString", place.getExtendedString());
        ZoneDoc.put("geoData", place.getGeoData());
        ZoneDoc.put("image", place.getImage());
        ZoneDoc.put("links", place.getLinks());
        ZoneDoc.put("zone", place.getZone());

        System.out.println(ZoneDoc.toString());
        this.places.insert(ZoneDoc);
    }

    public void update(String name, Place newPlace) throws MongoException {

        BasicDBObject query = new BasicDBObject("name", name);
        DBObject resp;
        DBCursor cur;

        cur = this.places.find(query);

        resp = cur.next();

        if (resp != null) {
            BasicDBObject typeDoc = new BasicDBObject();

            if (newPlace.getId() < 0) {
                typeDoc.put("id", newPlace.getId());
            } else {
                typeDoc.put("id", (String) resp.get("id"));
            }

            if (newPlace.getName() != null) {
                typeDoc.put("name", newPlace.getName());
            } else {
                typeDoc.put("name", (String) resp.get("name"));
            }

            if (newPlace.getParent() != null) {
                typeDoc.put("parents", newPlace.getState());
            } else {
                typeDoc.put("parents", (String) resp.get("parents"));
            }

            if (newPlace.getType() != null) {
                typeDoc.put("type", newPlace.getType());
            } else {
                typeDoc.put("type", (String[]) resp.get("type"));
            }

            if (newPlace.getState() != null) {
                typeDoc.put("state", newPlace.getState());
            } else {
                typeDoc.put("state", (String) resp.get("state"));
            }

            if (newPlace.getAddress() != null) {
                typeDoc.put("address", newPlace.getAddress());
            } else {
                typeDoc.put("address", (String) resp.get("address"));
            }

            if (newPlace.getDescription() != null) {
                typeDoc.put("description", newPlace.getDescription());
            } else {
                typeDoc.put("description", (String) resp.get("description"));
            }

            if (newPlace.getExtendedString() != null) {
                typeDoc.put("extendedString", newPlace.getExtendedString());
            } else {
                typeDoc.put("extendedString", (String) resp.get("extendedString"));
            }

            if (newPlace.getGeoData() != null) {
                typeDoc.put("geoData", newPlace.getGeoData());
            } else {
                typeDoc.put("geoData", (String) resp.get("geoData"));
            }

            if (newPlace.getImage() != null) {
                typeDoc.put("image", newPlace.getImage());
            } else {
                typeDoc.put("image", (String) resp.get("image"));
            }

            if (newPlace.getLinks() != null) {
                typeDoc.put("links", newPlace.getLinks());
            } else {
                typeDoc.put("links", (String) resp.get("links"));
            }

            if (newPlace.getZone() != null) {
                typeDoc.put("zone", newPlace.getZone());
            } else {
                typeDoc.put("zone", (String) resp.get("zone"));
            }

            this.places.update(new BasicDBObject().append("id", name), typeDoc);
        }
    }

    public Boolean exists(String name) {
        BasicDBObject query = new BasicDBObject("name", name);
        return this.places.find(query).count() > 0 ? Boolean.TRUE : Boolean.FALSE;
    }

    public String retrieveJsonById(String id) {
        BasicDBObject query = new BasicDBObject("id", id);
        DBObject resp = this.places.findOne(query);
        if (resp == null) {
            return null;
        }

        //resp.removeField("_id");
        //System.out.println(resp);

        return resp.toString();
    }

    public String retrieveJsonByType(String type, Boolean onlyNames) {
        BasicDBObject query = new BasicDBObject("type", type);
        DBObject resp;
        DBCursor cur;
        Boolean nothing = true;

        cur = this.places.find(query);
        String ret = "[";

        while (cur.hasNext()) {
            resp = cur.next();
            if (onlyNames) {
                ret += "{\"id\": \"" + resp.get("id") + "\",";
                ret += "\"name\": \"" + resp.get("name") + "\"},";
            } else {
                ret += resp + ",";
            }
            if (nothing) {
                nothing = false;
            }
        }

        if (!nothing) {
            ret = ret.substring(0, ret.length() - 1);
        }

        ret += "]";

        return ret;
    }

    public String retrieveJson(String name) {
        BasicDBObject query = new BasicDBObject("name", name);
        DBObject resp = this.places.findOne(query);
        if (resp == null) {
            return null;
        }

        resp.removeField("_id");
        //System.out.println(resp);

        return resp.toString();
    }

    public String retrieveJson(String zone, String state, Boolean onlyNames) {
        BasicDBObject query = new BasicDBObject();
        Pattern name = Pattern.compile("^.*" + zone + ".*$", Pattern.CASE_INSENSITIVE);
        DBObject resp;
        DBCursor cur;

        if (zone != null) {
            query.put("name", name);
        }

        if (state != null && !state.equals("all")) {
            query.put("state", state);
        }

        Logger.getLogger(this.getClass().getName()).log(Level.INFO, "QUERY: {0}", new Object[]{query.toString()});

        cur = this.places.find(query);

        String ret = "[";
        Boolean nothing = true;
        while (cur.hasNext()) {
            resp = cur.next();
            if (onlyNames) {
                ret += "{\"id\": \"" + resp.get("id") + "\",";
                ret += "\"name\": \"" + resp.get("name") + "\",";
                ret += "\"type\": \"" + resp.get("type") + "\",";
                ret += "\"state\": \"" + resp.get("state") + "\"},";
            } else {
                ret += resp + ",";
            }
            if (nothing) {
                nothing = false;
            }
        }
        if (!nothing) {
            ret = ret.substring(0, ret.length() - 1);
        }
        ret += "]";

        return ret;
    }

    public String retrieveAll() {
        return retrieveAll(false);
    }

    public String retrieveAll(Boolean onlyNames) {
        String ret = "[";
        DBObject resp;
        DBCursor cur = this.places.find();

        while (cur.hasNext()) {
            resp = cur.next();
            resp.removeField("_id");
            //System.out.println(resp);
            if (resp.get("state") == null || !((String) resp.get("state")).equals(State.VOID)) {
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

    public String retrieveAllExtendedStrings() {
        String ret = "[";
        DBObject resp;
        DBCursor cur = this.places.find();

        while (cur.hasNext()) {
            resp = cur.next();
            resp.removeField("_id");
            //System.out.println(resp);
            if (resp.get("state") == null || !((String) resp.get("state")).equals(State.VOID)) {
                ret += "'" + resp.get("extendedString") + "',";
            } else {
                return null;
            }
        }

        ret = ret.substring(0, ret.length() - 1);
        ret += "]";

        return ret;
    }

    public Place retrieve(Integer id) {
        BasicDBObject query = new BasicDBObject("id", id);
        return getPlace(query);
    }

    public Place retrieve(String name) {
        Logger.getLogger(this.getClass().getName()).log(Level.SEVERE, "Buscando zone por nombre: {0}", new Object[]{name});
        BasicDBObject query = new BasicDBObject("name", name);
        return getPlace(query);
    }

    public Place retrieveByExtendedString(String extendedString) {
        Logger.getLogger(this.getClass().getName()).log(Level.SEVERE, "Buscando zone por cadena extendida: {0}", new Object[]{extendedString});
        BasicDBObject query = new BasicDBObject("extendedString", extendedString.replace(", ", ",+").replace(" ", "_").replace(",+", ", ").toLowerCase());
        return getPlace(query);
    }

    public Place retrieve(String name, String type) {
        Logger.getLogger(this.getClass().getName()).log(Level.SEVERE, "Buscando zone por nombre y tipo: {0}, {1}", new Object[]{name, type});
        BasicDBObject query = new BasicDBObject();
        query.put("name", name);
        query.put("type", type);
        return getPlace(query);
    }

    private Place getPlace(BasicDBObject query) {
        DBObject resp;

        resp = this.places.findOne(query);
        Logger.getLogger(this.getClass().getName()).log(Level.SEVERE, "Place encontrado: {0}", new Object[]{resp});
        if (resp == null) {
            return null;
        }
        resp.removeField("_id");
        Place place = new Place();

        place.setId(Integer.parseInt((String) resp.get("id")));

        place.setName((String) resp.get("name"));

        place.setState((String) resp.get("state"));

        place.setExtendedString((String) resp.get("extendedString"));

        if (resp.get("type") != null) {
            DBObject obj = this.db.getCollection("placetypes").findOne(new BasicDBObject("name", (String) resp.get("type")));
            if (obj != null) {
                Type type = new Type((String) obj.get("name"), (ArrayList<String>) obj.get("parents"), (String) obj.get("state"));
                place.setType(type);
            }
        }

        if (resp.get("parent") != null && !"".equals(resp.get("parent"))) {
            place.setParent(retrieve(Integer.valueOf(String.valueOf(resp.get("parent")))));
        }

        if (resp.get("address") != null) {
            place.setAddress(String.valueOf(resp.get("address")));
        }

        if (resp.get("description") != null) {
            place.setDescription(String.valueOf(resp.get("description")));
        }

        if (resp.get("geoData") != null) {
            place.setGeoData(String.valueOf(resp.get("geoData")));
        }

        if (resp.get("image") != null) {
            place.setImage(String.valueOf(resp.get("image")));
        }

        if (resp.get("links") != null) {
            place.setLinks((List) (resp.get("links")));
        }

        if (resp.get("zone") != null) {
            place.setZone(String.valueOf(resp.get("zone")));
        }

        return place;
    }
}
