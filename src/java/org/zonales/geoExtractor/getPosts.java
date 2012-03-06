/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.zonales.geoExtractor;

import com.google.gson.Gson;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.apache.solr.client.solrj.SolrQuery;
import org.apache.solr.client.solrj.impl.CommonsHttpSolrServer;
import org.apache.solr.client.solrj.impl.XMLResponseParser;
import org.apache.solr.client.solrj.response.QueryResponse;
import org.apache.solr.common.SolrDocument;
import org.apache.solr.common.SolrDocumentList;
import org.zonales.BaseService;
import org.zonales.entities.Post;

/**
 *
 * @author nacho
 */
public class getPosts extends BaseService {

    @Override
    public void serve(HttpServletRequest request, HttpServletResponse response, Properties props) throws ServletException, IOException, Exception {
        response.setContentType("application/vnd.google-earth.kml+xml");
        //response.setContentType("text/plain");
        Integer cant = Integer.valueOf(request.getParameter("cant"));
        String sortField = request.getParameter("sortField");
        String sortOrder = request.getParameter("sortOrder");
        String minLat = request.getParameter("minLat") != null ? request.getParameter("minLat") : "-300.0";
        String maxLat = request.getParameter("maxLat") != null ? request.getParameter("maxLat") : "300.0";
        String minLon = request.getParameter("minLon") != null ? request.getParameter("minLon") : "-300.0";
        String maxLon = request.getParameter("maxLon") != null ? request.getParameter("maxLon") : "300.0";
        String ignoredSources = request.getParameter("ignoredSources");
        String since = request.getParameter("since");

        response.setCharacterEncoding("UTF-8");
        PrintWriter out = response.getWriter();
        String url = props.getProperty("solr_url");
        String ret = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><kml xmlns=\"http://www.opengis.net/kml/2.2\">";

        try {
            CommonsHttpSolrServer server = new CommonsHttpSolrServer(url);

            server.setSoTimeout(1000);  // socket read timeout
            server.setConnectionTimeout(100);
            server.setDefaultMaxConnectionsPerHost(100);
            server.setMaxTotalConnections(100);
            server.setFollowRedirects(false);  // defaults to false
            // allowCompression defaults to false.
            // Server side must support gzip or deflate for this to have any effect.
            server.setAllowCompression(true);
            server.setMaxRetries(1); // defaults to 0.  > 1 not recommended.
            server.setParser(new XMLResponseParser()); // binary parser is used by default

            SolrQuery query = new SolrQuery();
            query.setQueryType("zonalesContent");
            query.setFields("*");
            query.setRows(cant);
            query.setFilterQueries("modified:[" + since + " TO *]");
            query.setSortField(sortField, sortOrder.equals("desc") ? SolrQuery.ORDER.desc : SolrQuery.ORDER.asc);

            String queryStr = "";

            queryStr += "(fromUserLatitude:[" + minLat + " TO " + maxLat + "] AND fromUserLongitude:[" + minLon + " TO " + maxLon + "])"
                    + "OR (sourceLatitude:[" + minLat + " TO " + maxLat + "] AND sourceLongitude:[" + minLon + " TO " + maxLon + "])";

            if (ignoredSources != null) {
                String[] ignoredSourcesList = ignoredSources.split(",");
                for (String ignoredSource : ignoredSourcesList) {
                    queryStr += " AND -source:" + ignoredSource;
                }
            }

            Logger.getLogger(this.getClass().getName()).log(Level.INFO, "Query: {0}", queryStr);

            query.setQuery(queryStr);

            QueryResponse rsp = server.query( query );

            SolrDocumentList postList = rsp.getResults();
            String postJSON = "";
            Gson postGSON = new Gson();
            Post post;

            for  (SolrDocument solrdoc : postList) {
                    postJSON = (String) solrdoc.getFieldValue("verbatim");
                    post = postGSON.fromJson(postJSON, Post.class);

                    ret += "<Placemark>"
                            + "<name>" + post.getSource() + "</name>"
                            + "<description>";

                    ret += "{\"tags\": [";
                    for (String tag : post.getTags()) {
                        ret += "\"" + tag + "\",";
                    }
                    ret = ret.substring(0, ret.length() - 1);

                    ret += "], \"zone\": \"" + post.getZone() + "\", \"id\": \"" + post.getId() +"\"}";

                    Double lat = null;
                    Double lon = null;

                    if (post.getFromUser().getLongitude() != null) lon = post.getFromUser().getLongitude();
                    else if (post.getSourceLongitude() != null) lon = post.getSourceLongitude();

                    if (post.getFromUser().getLatitude() != null) lat = post.getFromUser().getLatitude();
                    else if (post.getSourceLatitude() != null) lat = post.getSourceLatitude();

                    ret += "</description>"
                            + "<Point><coordinates>"
                            + lon + "," + lat
                            + "</coordinates></Point>"
                            + "</Placemark>";
            }


            ret += "</kml>";

            out.print(ret);
            
        } catch (Exception ex) {
            Logger.getLogger(this.getClass().getName()).log(Level.SEVERE, "getPost: ", ex);
        }
    }

}
