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
import org.zonales.BaseDao;
import org.zonales.crawlConfig.objets.CrawlsTest;

/**
 *
 * @author nacho
 */
public class CrawlsTestDao extends BaseDao {
    
    private DBCollection crawlsTest;

    public CrawlsTestDao(String db_host, Integer db_port, String db_name) {
        super(db_host, db_port, db_name);
        this.crawlsTest = this.db.getCollection("crawlsTest");
        this.crawlsTest.ensureIndex(new BasicDBObject("query", 1), "uniqueName", true);
    }

    public void save(CrawlsTest crawlTest) throws MongoException {
        BasicDBObject crawlTestDoc = new BasicDBObject();

        crawlTestDoc.put("query", crawlTest.getQuery());
        crawlTestDoc.put("metadata", crawlTest.getMetadata());
        crawlTestDoc.put("results", crawlTest.getResults());

        System.out.println(crawlTestDoc.toString());

        this.crawlsTest.insert(crawlTestDoc);
    }

    public String retrieveJson(String queryStr) {
        BasicDBObject query = new BasicDBObject("query", queryStr);
        DBObject resp;
        DBCursor cur;

        cur = this.crawlsTest.find(query);

        resp = cur.next();
        resp.removeField("_id");
        //System.out.println(resp);

        return resp.toString();
    }

    public CrawlsTest retrieve(String queryStr) {
        BasicDBObject query = new BasicDBObject("query", queryStr);
        DBObject resp;
        DBCursor cur;
        CrawlsTest crawlTest = new CrawlsTest();

        cur = this.crawlsTest.find(query);

        resp = cur.next();
        resp.removeField("_id");

        crawlTest.setQuery((String)resp.get("query"));
        crawlTest.setMetadata((String)resp.get("metadata"));
        crawlTest.setResults((String)resp.get("results"));

        //System.out.println(service);

        return crawlTest;
    }

}
