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
import java.util.StringTokenizer;
import org.zonales.crawlConfig.objets.Param;
import org.zonales.crawlConfig.objets.Plugin;
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

    public void save(Service service) throws MongoException {
        BasicDBObject serviceDoc = new BasicDBObject();
        
        if (service.getParams() != null) {
            ArrayList<Param> params = service.getParams();
            ArrayList paramsToDoc = new ArrayList();
            for (Param param: params) {
                paramsToDoc.add(new BasicDBObject(param.getName(), param.getRequired()));
            }
            serviceDoc.put("params", paramsToDoc);
        }

        if (service.getPlugins() != null) {
            ArrayList<Plugin> plugins = service.getPlugins();
            ArrayList pluginsToDoc = new ArrayList();
            for (Plugin plugin: plugins) {
                pluginsToDoc.add(new BasicDBObject(plugin.getClassName(), plugin.getType()));
            }
            serviceDoc.put("plugins", pluginsToDoc);
        }

        serviceDoc.put("name", service.getName());
        serviceDoc.put("uri", service.getUri());
        serviceDoc.put("state", service.getState());

        System.out.println(serviceDoc.toString());

        this.services.insert(serviceDoc);
    }

    public void update(String name, Service newService) throws MongoException {
        BasicDBObject query = new BasicDBObject("name", name);
        DBObject resp;
        DBCursor cur;

        cur = this.services.find(query);

        resp = cur.next();

        if (resp != null) {
            BasicDBObject serviceDoc = new BasicDBObject();

            if (newService.getName() != null) {
                serviceDoc.put("name", newService.getName());
            } else {
                serviceDoc.put("name", (String)resp.get("name"));
            }

            if (newService.getUri() != null) {
                serviceDoc.put("uri", newService.getUri());
            } else {
                serviceDoc.put("uri", (String)resp.get("uri"));
            }

            if (newService.getState() != null) {
                serviceDoc.put("state", newService.getState());
            } else {
                serviceDoc.put("state", (String)resp.get("state"));
            }

            ArrayList<Param> params = newService.getParams();

            if (params != null) {
                ArrayList paramsToDoc = new ArrayList();

                for (Param param: params) {
                    paramsToDoc.add(new BasicDBObject(param.getName(), param.getRequired()));
                }
                serviceDoc.put("params", paramsToDoc);
            } else {
                serviceDoc.put("params", resp.get("params"));
            }

            ArrayList<Plugin> plugins = newService.getPlugins();

            if (plugins != null) {
                ArrayList pluginsToDoc = new ArrayList();

                for (Plugin plugin: plugins) {
                    pluginsToDoc.add(new BasicDBObject(plugin.getClassName(), plugin.getType()));
                }
                serviceDoc.put("plugins", pluginsToDoc);
            } else {
                serviceDoc.put("plugins", resp.get("plugins"));
            }

            this.services.update(new BasicDBObject().append("name", name), serviceDoc);
        }
    }

    public String retrieveJson(String name) {
        BasicDBObject query = new BasicDBObject("name", name);
        DBObject resp;
        DBCursor cur;

        cur = this.services.find(query);

        resp = cur.next();
        resp.removeField("_id");
        //System.out.println(resp);
        
        return resp.toString();
    }

    public String retrieveAll() {
        String ret = "";
        DBObject resp;
        DBCursor cur = this.services.find();

        while (cur.hasNext()) {
            resp = cur.next();
            resp.removeField("_id");
            ret += resp;
        }
        
        return ret;
    }

    public Service retrieve(String name) {
        BasicDBObject query = new BasicDBObject("name", name);
        DBObject resp;
        DBCursor cur;
        Service service = new Service();
        ArrayList<BasicDBObject> paramsJson = new ArrayList<BasicDBObject>();
        String paramName = "";
        String token;
        Boolean paramRequired = false;
        StringTokenizer paramToken;
        int tokenCount = 0;

        cur = this.services.find(query);

        resp = cur.next();
        resp.removeField("_id");

        service.setName((String)resp.get("name"));
        service.setUri((String)resp.get("uri"));
        //service.setPluginName((String)resp.get("pluginName"));

        paramsJson = (ArrayList<BasicDBObject>)resp.get("params");

        for (BasicDBObject paramJson: paramsJson) {
            paramToken = new StringTokenizer(paramJson.toString(), "\" }");
            while (paramToken.hasMoreTokens()) {
                token = paramToken.nextToken();
                tokenCount++;
                if (tokenCount == 2) {
                    paramName = token;
                }
                if (tokenCount == 4) {
                    paramRequired = Boolean.valueOf(token);
                }
            }
            service.addParam(paramName, paramRequired);
        }

        //System.out.println(service);
        
        return service;
    }

}
