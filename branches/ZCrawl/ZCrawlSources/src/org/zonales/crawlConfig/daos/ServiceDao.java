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

        serviceDoc.put("name", service.getName());
        serviceDoc.put("uri", service.getUri());
        serviceDoc.put("state", service.getState());

        if (service.getParams() != null) {
            ArrayList<Param> params = service.getParams();
            ArrayList paramsToDoc = new ArrayList();
            for (Param param: params) {
                BasicDBObject paramDoc = new BasicDBObject();
                paramDoc.put("name", param.getName());
                paramDoc.put("required", param.getRequired());
                paramsToDoc.add(paramDoc);
            }
            serviceDoc.put("params", paramsToDoc);
        }

        if (service.getPlugins() != null) {
            ArrayList<Plugin> plugins = service.getPlugins();
            ArrayList pluginsToDoc = new ArrayList();
            for (Plugin plugin: plugins) {
                BasicDBObject pluginDoc = new BasicDBObject();
                pluginDoc.put("class_name", plugin.getClassName());
                pluginDoc.put("type", plugin.getType());
                pluginsToDoc.add(pluginDoc);
            }
            serviceDoc.put("plugins", pluginsToDoc);
        }

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
                    BasicDBObject paramDoc = new BasicDBObject();
                    paramDoc.put("name", param.getName());
                    paramDoc.put("required", param.getRequired());
                    paramsToDoc.add(paramDoc);
                }
                serviceDoc.put("params", paramsToDoc);
            } else {
                serviceDoc.put("params", resp.get("params"));
            }

            ArrayList<Plugin> plugins = newService.getPlugins();

            if (plugins != null) {
                ArrayList pluginsToDoc = new ArrayList();

                for (Plugin plugin: plugins) {
                    BasicDBObject pluginDoc = new BasicDBObject();
                    pluginDoc.put("class_name", plugin.getClassName());
                    pluginDoc.put("type", plugin.getType());
                    pluginsToDoc.add(pluginDoc);
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
        System.out.println(resp);

        if (resp.get("state") == null || !((String)resp.get("state")).equals("Anulada")) {
            return resp.toString();
        } else {
            return null;
        }
    }

    public String retrieveAll() {
        String ret = "[";
        DBObject resp;
        DBCursor cur = this.services.find();

        while (cur.hasNext()) {
            resp = cur.next();
            resp.removeField("_id");
            System.out.println(resp);
            if (resp.get("state") == null || !((String)resp.get("state")).equals("Anulada")) {
                ret += resp + ",";
            } else {
                return null;
            }
        }

        ret = ret.substring(0, ret.length() - 1);
        ret += "]";
        
        return ret;
    }

    public Service retrieve(String name) {
        BasicDBObject query = new BasicDBObject("name", name);
        DBObject resp;
        DBCursor cur;
        Service service = new Service();
        ArrayList<BasicDBObject> paramsJson, pluginsJson;

        cur = this.services.find(query);

        resp = cur.next();
        resp.removeField("_id");
        System.out.println(resp);

        if (resp.get("state") == null || !((String)resp.get("state")).equals("Anulada")) {
            service.setName((String)resp.get("name"));
            service.setUri((String)resp.get("uri"));
            service.setState((String)resp.get("state"));
            //service.setPluginName((String)resp.get("pluginName"));

            paramsJson = (ArrayList<BasicDBObject>)resp.get("params");

            for (BasicDBObject paramJson: paramsJson) {
                service.addParam((String)paramJson.get("name"), (Boolean)paramJson.get("required"));
            }

            pluginsJson = (ArrayList<BasicDBObject>)resp.get("plugins");

            for (BasicDBObject pluginJson: pluginsJson) {
                service.addPlugin((String)pluginJson.get("class_name"), (String)pluginJson.get("type"));
            }

            return service;
        } else {
            return null;
        }

    }

}
