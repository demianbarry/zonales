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
import java.util.logging.Level;
import java.util.logging.Logger;
import org.zonales.BaseDao;
import org.zonales.crawlConfig.objets.State;
import org.zonales.tagsAndZones.objects.Tag;
import org.zonales.tagsAndZones.objects.Type;

/**
 *
 * @author rodrigo
 */
public class TagDao extends BaseDao {

    private DBCollection tags = null;

    public TagDao(String db_host, Integer db_port, String db_name) {
        super(db_host, db_port, db_name);
        this.tags = this.db.getCollection("tags");
        this.tags.ensureIndex(new BasicDBObject("id", 1), "uniqueName", true);
    }

    public void save(Tag tag) throws MongoException {

        BasicDBObject tagDoc = new BasicDBObject();
        tagDoc.put("id", tag.getId());
        tagDoc.put("name", tag.getName());
        tagDoc.put("state", tag.getState());

        if (tag.getType() != null) {

            tagDoc.put("type", tag.getType());
        }

        if (tag.getParent() != null) {

            tagDoc.put("parent", tag.getParent());
        }

        Logger.getLogger(this.getClass().getName()).log(Level.SEVERE, "Tag doc: {0}", new Object[]{tagDoc.toString()});
        this.tags.insert(tagDoc);
    }

    public void update(String name, Tag newTag) throws MongoException {
        BasicDBObject query = new BasicDBObject("name", name);
        DBObject resp;
        DBCursor cur;

        cur = this.tags.find(query);

        resp = cur.next();

        if (resp != null) {
            BasicDBObject tagDoc = new BasicDBObject();

            if (newTag.getId() < 0) {
                tagDoc.put("id", newTag.getId());
            } else {
                tagDoc.put("id", (String) resp.get("id"));
            }

            if (newTag.getName() != null) {
                tagDoc.put("name", newTag.getName());
            } else {
                tagDoc.put("name", (String) resp.get("name"));
            }

            if (newTag.getState() != null) {
                tagDoc.put("state", newTag.getState());
            } else {
                tagDoc.put("state", (String) resp.get("state"));
            }

            if (newTag.getType() != null) {
                tagDoc.put("type", newTag.getParent());
            } else {
                tagDoc.put("type", (String) resp.get("type"));
            }

            if (newTag.getParent() != null) {
                tagDoc.put("parent", newTag.getParent());
            } else {
                tagDoc.put("parent", (String) resp.get("parent"));
            }



            this.tags.update(new BasicDBObject().append("name", name), tagDoc);
        }
    }

    public Boolean exists(String name) {
        BasicDBObject query = new BasicDBObject("name", name);
        return this.tags.find(query).count() > 0 ? Boolean.TRUE : Boolean.FALSE;
    }

    public String retrieveJson(String name) {

        BasicDBObject query = new BasicDBObject("name", name);

        DBObject resp;

        DBCursor cur;

        cur = this.tags.find(query);

        resp = cur.next();
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
        DBCursor cur = this.tags.find();

        while (cur.hasNext()) {
            resp = cur.next();
            resp.removeField("_id");            
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

    public Tag retrieve(Integer id) {
        BasicDBObject query = new BasicDBObject("id", id);
        return getTag(query);
    }

    public Tag retrieve(String name) {
        BasicDBObject query = new BasicDBObject("name", name);
        return getTag(query);
    }

    private Tag getTag(BasicDBObject query) {
        DBObject resp;

        resp = this.tags.findOne(query);
        if (resp == null) {
            return null;
        }
        resp.removeField("_id");

        Tag tag = new Tag();
        tag.setId(Integer.parseInt((String) resp.get("id")));
        tag.setName((String) resp.get("name"));
        tag.setState((String) resp.get("state"));
        if (resp.get("parent") != null) {
            tag.setParent(retrieve(Integer.valueOf(String.valueOf(resp.get("parent")))));
        }

        if (resp.get("type") != null) {
            DBObject obj = this.db.getCollection("tagTypes").findOne(new BasicDBObject("name", (String) resp.get("type")));
            Type type = new Type((String) obj.get("name"), (ArrayList<String>) obj.get("parents"), (String) obj.get("state"));
            tag.setType(type);
        }

        return tag;
    }
}
