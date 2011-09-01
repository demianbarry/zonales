/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.zonales.feedSelector.daos;

import com.mongodb.BasicDBObject;
import com.mongodb.DBCollection;
import com.mongodb.DBObject;
import com.mongodb.MongoException;
import java.util.ArrayList;
import java.util.List;
import org.zonales.BaseDao;
import org.zonales.entities.FeedSelector;
import org.zonales.entities.FeedSelectors;

/**
 *
 * @author nacho
 */
public class FeedSelectorDao extends BaseDao {

    private DBCollection feedSelectors;

    public FeedSelectorDao(String db_host, Integer db_port, String db_name) {
        super(db_host, db_port, db_name);
        this.feedSelectors = this.db.getCollection("feedselector");
        this.feedSelectors.ensureIndex(new BasicDBObject("url", 1), "uniqueName", true);
    }

    public void save(FeedSelectors feedSelectors) throws MongoException {
        BasicDBObject feedSelectorsDoc = new BasicDBObject();

        feedSelectorsDoc.put("url", feedSelectors.getUrl());

        if (feedSelectors.getSelectors() != null) {
            List<FeedSelector> selectors = feedSelectors.getSelectors();
            ArrayList paramsToDoc = new ArrayList();
            for (FeedSelector selector : selectors) {
                BasicDBObject paramDoc = new BasicDBObject();
                paramDoc.put("type", selector.getType());
                paramDoc.put("selector", selector.getSelector());
                paramsToDoc.add(paramDoc);
            }
            feedSelectorsDoc.put("selectors", paramsToDoc);
        }

        System.out.println(feedSelectorsDoc.toString());

        this.feedSelectors.insert(feedSelectorsDoc);
    }

    public void update(String url, FeedSelectors feedSelector) throws MongoException {
        BasicDBObject query = new BasicDBObject("url", url);
        DBObject resp = this.feedSelectors.findOne(query);       

        if (resp != null) {
            BasicDBObject feedSelectorsDoc = new BasicDBObject();

            if (feedSelector.getUrl() != null) {
                feedSelectorsDoc.put("url", feedSelector.getUrl());
            } else {
                feedSelectorsDoc.put("url", (String)resp.get("url"));
            }

            List<FeedSelector> myFeedSelectors = feedSelector.getSelectors();

            if (myFeedSelectors != null) {
                ArrayList paramsToDoc = new ArrayList();

                for (FeedSelector param: myFeedSelectors) {
                    BasicDBObject paramDoc = new BasicDBObject();
                    paramDoc.put("type", param.getType());
                    paramDoc.put("selector", param.getSelector());
                    paramsToDoc.add(paramDoc);
                }
                feedSelectorsDoc.put("selectors", paramsToDoc);
            } else {
                feedSelectorsDoc.put("selectors", resp.get("selectors"));
            }
            this.feedSelectors.update(new BasicDBObject().append("url", url), feedSelectorsDoc);
        }
    }
    
    public String retrieveJson(String url) {
        BasicDBObject query = new BasicDBObject("url", url);
        DBObject resp = this.feedSelectors.findOne(query);

        resp.removeField("_id");
        //System.out.println(resp);

        return resp.toString();
    }

    public FeedSelectors retrieve(String url) {
        BasicDBObject query = new BasicDBObject("url", url);
        DBObject resp = this.feedSelectors.findOne(query);;
        FeedSelectors myFeedSelectors = new FeedSelectors();
        ArrayList<BasicDBObject> selectors;

        if(resp == null) {
            return null;
        }
            
        
        resp.removeField("_id");

        System.out.println(resp);

        myFeedSelectors.setUrl((String) resp.get("url"));


        selectors = (ArrayList<BasicDBObject>) resp.get("selectors");

        if (selectors != null) {
            myFeedSelectors.setSelectors(new ArrayList<FeedSelector>());
            for (BasicDBObject selector : selectors) {
                myFeedSelectors.getSelectors().add(new FeedSelector((String) selector.get("type"), (String) selector.get("selector")));
            }
        }

        return myFeedSelectors;

    }
}
