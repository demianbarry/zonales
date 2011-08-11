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
import org.zonales.tagsAndZones.objects.Tag;
import org.zonales.tagsAndZones.objects.Type;

/**
 *
 * @author rodrigo
 */
public class TagDao extends BaseDao {

    private DBCollection tags;

    public TagDao(String db_host, Integer db_port, String db_name) {
        super(db_host, db_port, db_name);
        this.tags = this.db.getCollection("tags");
        this.tags.ensureIndex(new BasicDBObject("name", 1), "uniqueName", true);
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

        System.out.println(tagDoc.toString());
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

    public Tag retrieve(String name) {
        BasicDBObject query = new BasicDBObject("name", name);
        DBObject resp;
        DBCursor cur;
        Tag tag = new Tag();

        cur = this.tags.find(query);

        resp = cur.next();
        resp.removeField("_id");

        tag.setId(Integer.parseInt((String) resp.get("id")));
        tag.setName((String) resp.get("name"));
        tag.setParent((Tag) resp.get("parent"));
        tag.setType((Type) resp.get("type"));
        tag.setState((String) resp.get("state"));



        return tag;
    }
}
