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
import org.zonales.crawlConfig.daos.BaseDao;
import org.zonales.tagsAndZones.objects.Type;

/**
 *
 * @author rodrigo
 */
public class TypeDao extends BaseDao {

    private DBCollection types;

    public TypeDao(String db_host, Integer db_port, String db_name) {
        super(db_host, db_port, db_name);
        this.types = this.db.getCollection("tagTypes");
        this.types.ensureIndex(new BasicDBObject("name", 1), "uniqueName", true);
    }

    public void save(Type type) throws MongoException {

        BasicDBObject typeDoc = new BasicDBObject();
        typeDoc.put("name", type.getName());
        typeDoc.put("state", type.getState());

        if (type.getParents() != null) {

            typeDoc.put("parents", type.getParents());
        }

        System.out.println(typeDoc.toString());
        this.types.insert(typeDoc);
    }

    public void update(String name, Type newType) throws MongoException {
        BasicDBObject query = new BasicDBObject("name", name);
        DBObject resp;
        DBCursor cur;

        cur = this.types.find(query);

        resp = cur.next();

        if (resp != null) {
            BasicDBObject typeDoc = new BasicDBObject();

            if (newType.getName() != null) {
                typeDoc.put("name", newType.getName());
            } else {
                typeDoc.put("name", (String) resp.get("name"));
            }

            if (newType.getState() != null) {
                typeDoc.put("state", newType.getState());
            } else {
                typeDoc.put("state", (String) resp.get("state"));
            }

            if (newType.getParents() != null) {
                typeDoc.put("parents", newType.getState());
            } else {
                typeDoc.put("parents", (String[]) resp.get("parents"));
            }



            this.types.update(new BasicDBObject().append("name", name), typeDoc);
        }
    }

    public String retrieveJson(String name) {
        BasicDBObject query = new BasicDBObject("name", name);
        DBObject resp;
        DBCursor cur;

        cur = this.types.find(query);

        resp = cur.next();
        resp.removeField("_id");
        //System.out.println(resp);

        return resp.toString();


    }
    
    public Boolean exists(String name) {
        BasicDBObject query = new BasicDBObject("name", name);
        return this.types.find(query).count() > 0 ? Boolean.TRUE : Boolean.FALSE;
    }

    public Type retrieve(String name) {
        BasicDBObject query = new BasicDBObject("name", name);
        DBObject resp;
        DBCursor cur;
        Type type = new Type();

        cur = this.types.find(query);

        resp = cur.next();
        resp.removeField("_id");

        type.setName((String) resp.get("name"));
        type.setState((String) resp.get("state"));
        type.setParents((ArrayList<String>) resp.get("parents"));


        return type;
    }
}
