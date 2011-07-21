/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.crawlConfig.daos;

import com.mongodb.BasicDBObject;
import com.mongodb.DBCollection;
import com.mongodb.DBCursor;
import com.mongodb.DBObject;
import com.mongodb.MongoException;
import java.util.ArrayList;
import org.zonales.crawlConfig.objets.Param;
import org.zonales.crawlConfig.objets.Service;

/**
 *
 * @author nacho
 */
public class ServiceDao extends BaseDao {

    private DBCollection services;

    public ServiceDao(String db_host, Integer db_port, String db_name) {
        super(db_host, db_port, db_name);
        this.services = this.db.getCollection("services");
        this.services.ensureIndex(new BasicDBObject("name", 1), "uniqueName", true);
    }

    public void saveService(Service service) throws MongoException {
        BasicDBObject serviceDoc = new BasicDBObject();
        
        ArrayList<Param> params = service.getParams();
        ArrayList paramsToDoc = new ArrayList();

        for (Param param: params) {
            paramsToDoc.add(new BasicDBObject(param.getName(), param.getRequired()));
        }

        serviceDoc.put("name", service.getName());
        serviceDoc.put("uri", service.getUri());

        serviceDoc.put("params", paramsToDoc);

        System.out.println(serviceDoc.toString());

        this.services.insert(serviceDoc);
    }

    public String retrieveService(String name) {
        BasicDBObject query = new BasicDBObject("name", name);
        DBObject resp;
        DBCursor cur;

        cur = this.services.find(query);

        resp = cur.next();
        resp.removeField("_id");
        //System.out.println(resp);
        
        return resp.toString();
    }

}
